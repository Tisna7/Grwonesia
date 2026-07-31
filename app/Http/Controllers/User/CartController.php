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
  public function index(Request $request)
  {
    if ($request->wantsJson() || $request->ajax()) {
      $userId = Auth::id();

      $items = CartItem::with('product.business')
        ->where('user_id', $userId)
        ->get();

      $formatted = $items->map(function ($item) {
        $p = $item->product;
        if (!$p) {
          return null;
        }

        $categorySlug = match (strtolower($p->category ?? '')) {
          'minuman', 'kopi' => 'kopi',
          'batik', 'fashion', 'pakaian' => 'batik',
          'kerajinan', 'craft' => 'kerajinan',
          default => 'makanan',
        };

        return [
          'product' => [
            'id' => $p->id,
            'name' => $p->name,
            'category' => $categorySlug,
            'umkm' => $p->business?->name ?? 'UMKM Mitra',
            'price' => (float) $p->price,
            'image' => $p->image_url ?: $p->image,
            'image_url' => $p->image_url ?: $p->image,
            'photo_path' => $p->photo_path,
          ],
          'qty' => $item->qty,
        ];
      })->filter()->values();

      return response()->json($formatted);
    }

    return app(\App\Http\Controllers\User\DashboardController::class)->index($request);
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
