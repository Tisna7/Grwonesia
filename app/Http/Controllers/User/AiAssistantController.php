<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Ai\ShoppingAssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
  public function __construct(private readonly ShoppingAssistantService $assistantService)
  {
  }

  public function chat(Request $request): JsonResponse
  {
    $request->validate([
      'message' => ['required', 'string', 'max:1000'],
    ]);

    $result = $this->assistantService->chatShopper(
      $request->input('message'),
      $request->input('history', [])
    );

    return response()->json($result);
  }

  public function recommendGift(Request $request): JsonResponse
  {
    $request->validate([
      'query' => ['required', 'string', 'max:500'],
      'budget' => ['nullable', 'numeric', 'min:0'],
    ]);

    $budget = $request->input('budget') ? (float) $request->input('budget') : null;

    $result = $this->assistantService->recommendGiftBundle(
      $request->input('query'),
      $budget
    );

    return response()->json($result);
  }

  public function compare(Request $request): JsonResponse
  {
    $request->validate([
      'product1_id' => ['required', 'exists:products,id'],
      'product2_id' => ['required', 'exists:products,id'],
    ]);

    $result = $this->assistantService->compareProducts(
      (int) $request->input('product1_id'),
      (int) $request->input('product2_id')
    );

    return response()->json($result);
  }
}
