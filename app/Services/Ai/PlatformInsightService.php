<?php

namespace App\Services\Ai;

use App\Models\AiInsight;
use App\Services\PlatformMetricsService;
use Illuminate\Support\Carbon;

class PlatformInsightService
{
    public function __construct(
        private readonly GeminiClient $gemini,
        private readonly PlatformMetricsService $metrics,
    ) {}

    /**
     * Insight operasional platform untuk manajemen Grownesia (F-ADM-04) — cache harian.
     */
    public function getForToday(): ?array
    {
        $cached = AiInsight::whereNull('business_id')
            ->where('type', 'platform')
            ->whereDate('date', Carbon::today())
            ->first();

        if ($cached) {
            return $cached->payload;
        }

        if (! $this->gemini->isConfigured()) {
            return null;
        }

        $stats = $this->metrics->platformStats();
        $ai = $this->metrics->aiStats(7);

        $context = [
            'total_user' => $stats['total_users'],
            'user_per_role' => $stats['users_by_role'],
            'total_bisnis' => $stats['total_businesses'],
            'verifikasi_pending' => $stats['pending_verifications'],
            'gmv_30_hari' => $stats['gmv_30'],
            'order_30_hari' => $stats['orders_30'],
            'ai_request_7_hari' => $ai['total'],
            'ai_success_rate_pct' => $ai['success_rate'],
            'ai_avg_latency_ms' => $ai['avg_latency_ms'],
            'ai_total_tokens_7_hari' => $ai['total_tokens'],
        ];

        $payload = $this->gemini->generateJson(
            "Metrik operasional platform Grownesia:\n".
            json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).
            "\n\nBerikan insight operasional untuk manajemen platform.",
            [
                'type' => 'object',
                'properties' => [
                    'headline' => ['type' => 'string'],
                    'observations' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'action_items' => ['type' => 'array', 'items' => ['type' => 'string']],
                ],
                'required' => ['headline', 'observations', 'action_items'],
            ],
            'Kamu adalah Platform AI Insight Grownesia — penasihat operasional untuk tim manajemen platform. '.
            'Fokus pada pertumbuhan pengguna, kesehatan infrastruktur AI (latency, error rate, biaya token), dan antrean verifikasi. Bahasa Indonesia ringkas.',
        );

        if ($payload === null) {
            return null;
        }

        AiInsight::create([
            'business_id' => null,
            'type' => 'platform',
            'date' => Carbon::today(),
            'payload' => $payload,
        ]);

        return $payload;
    }
}
