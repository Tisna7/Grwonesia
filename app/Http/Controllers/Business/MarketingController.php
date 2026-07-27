<?php

namespace App\Http\Controllers\Business;

use App\Enums\MarketingContentType;
use App\Http\Controllers\Controller;
use App\Services\Ai\GeminiClient;
use App\Services\Ai\MarketingContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function index(Request $request, GeminiClient $gemini): View
    {
        $business = $request->user()->business;

        return view('business.marketing', [
            'types' => MarketingContentType::cases(),
            'products' => $business->products()->active()->orderBy('name')->get(),
            'history' => $business->marketingContents()->with('product')->latest()->take(20)->get(),
            'aiConfigured' => $gemini->isConfigured(),
        ]);
    }

    public function generate(Request $request, MarketingContentService $service): JsonResponse
    {
        $business = $request->user()->business;

        $validated = $request->validate([
            'type' => ['required', Rule::enum(MarketingContentType::class)],
            'brief' => ['required', 'string', 'max:2000'],
            'product_id' => ['nullable', 'integer'],
        ]);

        $product = $request->filled('product_id')
            ? $business->products()->find($validated['product_id'])
            : null;

        $content = $service->generate(
            $business,
            MarketingContentType::from($validated['type']),
            $validated['brief'],
            $product,
        );

        if ($content === null) {
            return response()->json(['error' => 'AI belum tersedia. Periksa GEMINI_API_KEY atau kuota API.'], 503);
        }

        return response()->json([
            'data' => [
                'id' => $content->id,
                'type_label' => $content->type->label(),
                'content' => $content->content,
            ],
        ]);
    }
}
