<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Services\Ai\FinancialAssistantService;
use App\Services\Ai\GeminiClient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function __invoke(
        Request $request,
        FinancialAssistantService $service,
        GeminiClient $gemini,
    ): View {
        $business = $request->user()->business;

        $analysis = $service->analysis($business);

        return view('business.finance', [
            'business' => $business,
            'analysis' => $analysis,
            'narrative' => $service->narrative($business, $analysis),
            'aiConfigured' => $gemini->isConfigured(),
        ]);
    }
}
