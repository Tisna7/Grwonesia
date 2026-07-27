<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Ai\GeminiClient;
use App\Services\PlatformMetricsService;
use Illuminate\View\View;

class AiCenterController extends Controller
{
    public function __invoke(PlatformMetricsService $metrics, GeminiClient $gemini): View
    {
        return view('admin.ai-center', [
            'ai' => $metrics->aiStats(7),
            'integrations' => $metrics->integrations(),
            'aiConfigured' => $gemini->isConfigured(),
        ]);
    }
}
