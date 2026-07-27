<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\GovProgram;
use App\Services\Ai\DailyInsightService;
use App\Services\Ai\GeminiClient;
use App\Services\BusinessMetricsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(
        Request $request,
        BusinessMetricsService $metrics,
        DailyInsightService $insightService,
        GeminiClient $gemini,
    ): View {
        $business = $request->user()->business;

        $registeredIds = $business->programRegistrations()->pluck('gov_program_id');

        return view('business.dashboard', [
            'business' => $business,
            'stats' => $metrics->dashboardStats($business),
            'health' => $metrics->healthScore($business),
            'chart' => $metrics->dailyRevenueSeries($business, 30),
            'insight' => $insightService->getForToday($business),
            'aiConfigured' => $gemini->isConfigured(),
            'govPrograms' => GovProgram::relevantFor($business)->latest('starts_at')->take(3)->get(),
            'registeredProgramIds' => $registeredIds,
        ]);
    }
}
