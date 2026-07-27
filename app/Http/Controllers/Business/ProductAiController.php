<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Ai\ProductOptimizerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductAiController extends Controller
{
    public function __construct(private readonly ProductOptimizerService $optimizer) {}

    public function photo(Request $request, Product $product): JsonResponse
    {
        $this->authorizeProduct($request, $product);

        if (! $product->photo_path) {
            return response()->json(['error' => 'Unggah foto produk terlebih dahulu.'], 422);
        }

        $result = $this->optimizer->analyzePhoto($product);

        return $result !== null
            ? response()->json(['data' => $result])
            : response()->json(['error' => 'AI belum tersedia. Periksa GEMINI_API_KEY atau kuota API.'], 503);
    }

    public function seo(Request $request, Product $product): JsonResponse
    {
        $this->authorizeProduct($request, $product);

        $result = $this->optimizer->suggestSeoTitles($product);

        return $result !== null
            ? response()->json(['data' => $result])
            : response()->json(['error' => 'AI belum tersedia. Periksa GEMINI_API_KEY atau kuota API.'], 503);
    }

    public function description(Request $request, Product $product): JsonResponse
    {
        $this->authorizeProduct($request, $product);

        $result = $this->optimizer->generateDescription($product);

        return $result !== null
            ? response()->json(['data' => ['description' => $result]])
            : response()->json(['error' => 'AI belum tersedia. Periksa GEMINI_API_KEY atau kuota API.'], 503);
    }

    private function authorizeProduct(Request $request, Product $product): void
    {
        abort_unless($product->business_id === $request->user()->business?->id, 404);
    }
}
