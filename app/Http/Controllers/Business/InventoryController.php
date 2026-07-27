<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Services\Ai\GeminiClient;
use App\Services\Ai\InventoryPredictionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __invoke(
        Request $request,
        InventoryPredictionService $service,
        GeminiClient $gemini,
    ): View {
        $business = $request->user()->business;
        $predictions = $service->predictions($business);

        return view('business.inventory', [
            'predictions' => $predictions,
            'narrative' => $service->narrative($business, $predictions),
            'aiConfigured' => $gemini->isConfigured(),
        ]);
    }
}
