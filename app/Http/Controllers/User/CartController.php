<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
  public function index(): JsonResponse
  {
    $userId = Auth::id();

    $items = CartItem::with('product')
      ->where('user_id', $userId)
      ->get();

    $formatted = $items->map(function ($item) {
      return [
        'product' => $item->product,
        'qty' => $item->qty,
      ];
    });

    return response()->json($formatted);
  }

  public function sync(Request $request): JsonResponse
  {
    $request->validate([
      'cart' => 'array',
      'cart.*.product.id' => 'required|integer',
      'cart.*.qty' => 'required|integer|min:1',
    ]);

    $userId = Auth::id();

    // Clear existing cart items for this user
    CartItem::where('user_id', $userId)->delete();

    foreach ($request->input('cart', []) as $item) {
      $prodId = $item['product']['id'];
      if (Product::where('id', $prodId)->exists()) {
        CartItem::create([
          'user_id' => $userId,
          'session_id' => null,
          'product_id' => $prodId,
          'qty' => (int) $item['qty'],
        ]);
      }
    }

    return response()->json(['success' => true]);
  }
}
