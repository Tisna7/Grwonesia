<?php

namespace App\Services\Ai;

use App\Models\AiInsight;
use App\Models\Business;
use App\Services\BusinessMetricsService;
use Illuminate\Support\Carbon;

class DailyInsightService
{
    public function __construct(
        private readonly GeminiClient $gemini,
        private readonly BusinessMetricsService $metrics,
    ) {}

    /**
     * Insight harian — maksimal satu panggilan AI per bisnis per hari (cache di ai_insights).
     */
    public function getForToday(Business $business): ?array
    {
        $cached = AiInsight::where('business_id', $business->id)
            ->where('type', 'daily_insight')
            ->whereDate('date', Carbon::today())
            ->first();

        if ($cached) {
            return $cached->payload;
        }

        if (! $this->gemini->isConfigured()) {
            return null;
        }

        $stats = $this->metrics->dashboardStats($business);
        $health = $this->metrics->healthScore($business);

        $context = [
            'nama_bisnis' => $business->name,
            'kategori' => $business->category,
            'kota' => $business->city,
            'omzet_bulan_ini' => $stats['omzet_month'],
            'perubahan_omzet_pct' => $stats['omzet_month_delta'],
            'order_bulan_ini' => $stats['orders_month'],
            'pesanan_pending' => $stats['pending_orders'],
            'produk_terlaris_7hari' => $stats['top_products'],
            'produk_menurun_7hari' => $stats['declining_products'],
            'health_score' => $health['score'],
            'komponen_health' => $health['components'],
            'margin_pct' => $health['margin_pct'],
        ];

        $payload = $this->gemini->generateJson(
            "Berikut data performa bisnis UMKM hari ini:\n".json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).
            "\n\nBuat insight harian singkat untuk pemilik bisnis.",
            [
                'type' => 'object',
                'properties' => [
                    'headline' => ['type' => 'string', 'description' => 'Satu kalimat insight paling penting hari ini'],
                    'insights' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                        'description' => '2-3 poin observasi dari data',
                    ],
                    'recommendations' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                        'description' => '2-3 rekomendasi aksi konkret',
                    ],
                ],
                'required' => ['headline', 'insights', 'recommendations'],
            ],
            'Kamu adalah AI Business Coach untuk UMKM Indonesia di platform Grownesia. Jawab dalam Bahasa Indonesia yang ramah, singkat, dan konkret. Fokus pada aksi praktis yang bisa langsung dilakukan pemilik usaha kecil.',
        );

        if ($payload === null) {
            return null;
        }

        AiInsight::create([
            'business_id' => $business->id,
            'type' => 'daily_insight',
            'date' => Carbon::today(),
            'payload' => $payload,
        ]);

        return $payload;
    }
}
