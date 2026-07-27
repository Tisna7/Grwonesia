<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use App\Services\Ai\EconomicInsightService;
use App\Services\Ai\GeminiClient;
use App\Services\GovernmentMetricsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(
        GovernmentMetricsService $metrics,
        EconomicInsightService $insightService,
        GeminiClient $gemini,
    ): View {
        return view('government.dashboard', [
            'stats' => $metrics->regionalStats(),
            'chart' => $metrics->dailyRegionalRevenue(30),
            'workforce' => $metrics->estimatedWorkforce(),
            'insight' => $insightService->getForToday(),
            'aiConfigured' => $gemini->isConfigured(),
        ]);
    }
}
