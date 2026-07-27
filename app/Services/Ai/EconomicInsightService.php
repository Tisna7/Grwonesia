<?php

namespace App\Services\Ai;

use App\Models\AiInsight;
use App\Services\GovernmentMetricsService;
use Illuminate\Support\Carbon;

class EconomicInsightService
{
    public function __construct(
        private readonly GeminiClient $gemini,
        private readonly GovernmentMetricsService $metrics,
    ) {}

    /**
     * Insight ekonomi regional harian — cache di ai_insights (business_id null).
     */
    public function getForToday(): ?array
    {
        $cached = AiInsight::whereNull('business_id')
            ->where('type', 'gov_economic')
            ->whereDate('date', Carbon::today())
            ->first();

        if ($cached) {
            return $cached->payload;
        }

        if (! $this->gemini->isConfigured()) {
            return null;
        }

        $stats = $this->metrics->regionalStats();

        $context = [
            'total_umkm_terdaftar' => $stats['total_businesses'],
            'umkm_aktif_30_hari' => $stats['active_businesses'],
            'omzet_regional_30_hari' => $stats['omzet_30'],
            'pertumbuhan_omzet_pct' => $stats['omzet_30_delta'],
            'volume_transaksi_30_hari' => $stats['transactions_30'],
            'hotspot_kota' => $stats['city_hotspots'],
            'tren_per_kategori' => $stats['category_trends'],
            'produk_terlaris_regional' => array_slice($stats['top_products'], 0, 5),
        ];

        $payload = $this->gemini->generateJson(
            "Data ekonomi UMKM regional (agregat platform Grownesia):\n".
            json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).
            "\n\nAnalisis tren dan anomali untuk pembuat kebijakan daerah.",
            [
                'type' => 'object',
                'properties' => [
                    'headline' => ['type' => 'string', 'description' => 'Temuan paling penting, 1 kalimat'],
                    'trends' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                        'description' => '2-4 tren/anomali sektor atau wilayah yang terdeteksi dari data',
                    ],
                    'policy_recommendations' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                        'description' => '2-3 rekomendasi program/kebijakan konkret (pelatihan, bantuan alat, subsidi) dengan target wilayah/sektor spesifik',
                    ],
                ],
                'required' => ['headline', 'trends', 'policy_recommendations'],
            ],
            'Kamu adalah AI Economic Insight Grownesia — analis ekonomi untuk dinas pemerintah daerah Indonesia. '.
            'Fokus pada tren sektor, disparitas antar wilayah, dan rekomendasi kebijakan tepat sasaran. Bahasa Indonesia formal namun jelas.',
        );

        if ($payload === null) {
            return null;
        }

        AiInsight::create([
            'business_id' => null,
            'type' => 'gov_economic',
            'date' => Carbon::today(),
            'payload' => $payload,
        ]);

        return $payload;
    }

    /**
     * Rekomendasi program dinas dari data riil (untuk halaman Program & Event).
     */
    public function recommendPrograms(): ?array
    {
        $stats = $this->metrics->regionalStats();

        return $this->gemini->generateJson(
            "Data ekonomi regional:\n".
            json_encode([
                'hotspot_kota' => $stats['city_hotspots'],
                'tren_per_kategori' => $stats['category_trends'],
                'produk_terlaris' => array_slice($stats['top_products'], 0, 5),
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).
            "\n\nUsulkan 3 program dinas (pelatihan/bantuan/event/pameran) yang paling dibutuhkan berdasarkan data.",
            [
                'type' => 'object',
                'properties' => [
                    'programs' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'title' => ['type' => 'string'],
                                'type' => ['type' => 'string', 'enum' => ['pelatihan', 'bantuan', 'event', 'pameran']],
                                'sector' => ['type' => 'string'],
                                'city' => ['type' => 'string'],
                                'reason' => ['type' => 'string', 'description' => 'Alasan berbasis data, 1-2 kalimat'],
                            ],
                            'required' => ['title', 'type', 'sector', 'city', 'reason'],
                        ],
                    ],
                ],
                'required' => ['programs'],
            ],
            'Kamu adalah penasihat program pemberdayaan UMKM untuk dinas pemerintah daerah Indonesia.',
        );
    }
}
