<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Ai\GeminiClient;
use App\Services\Ai\PlatformInsightService;
use App\Services\PlatformMetricsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(
        PlatformMetricsService $metrics,
        PlatformInsightService $insightService,
        GeminiClient $gemini,
    ): View {
        return view('admin.dashboard', [
            'stats' => $metrics->platformStats(),
            'ai' => $metrics->aiStats(7),
            'insight' => $insightService->getForToday(),
            'aiConfigured' => $gemini->isConfigured(),
        ]);
    }
}
