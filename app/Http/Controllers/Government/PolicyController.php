<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use App\Services\Ai\GeminiClient;
use App\Services\GovernmentMetricsService;
use App\Services\PolicySimulatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PolicyController extends Controller
{
    public function index(GovernmentMetricsService $metrics, GeminiClient $gemini): View
    {
        return view('government.policy', [
            'sectors' => collect($metrics->categoryTrends())->pluck('category'),
            'aiConfigured' => $gemini->isConfigured(),
        ]);
    }

    public function simulate(Request $request, PolicySimulatorService $simulator): JsonResponse
    {
        $params = $request->validate([
            'subsidi_ongkir' => ['nullable', 'numeric', 'min:0', 'max:50'],
            'bantuan_alat' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'pelatihan_batch' => ['nullable', 'integer', 'min:0', 'max:10'],
            'durasi_bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'sector' => ['nullable', 'string', 'max:50'],
            'with_narrative' => ['nullable', 'boolean'],
        ]);

        $result = $simulator->simulate($params);

        $narrative = null;
        if ($request->boolean('with_narrative')) {
            $narrative = $simulator->narrative($params, $result);
        }

        return response()->json([
            'data' => $result,
            'narrative' => $narrative,
        ]);
    }
}
