<?php

namespace App\Http\Controllers\User;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Models\Business;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
  public function store(Request $request): JsonResponse
  {
    $request->validate([
      'cart' => 'required|array|min:1',
      'cart.*.product.id' => 'required|integer',
      'cart.*.qty' => 'required|integer|min:1',
    ]);

    $user = Auth::user();
    $cartItems = $request->input('cart');

    // Extract product IDs
    $productIds = array_column(array_column($cartItems, 'product'), 'id');
    $products = Product::with('business')->whereIn('id', $productIds)->get()->keyBy('id');

    // Group cart items by business_id
    $itemsByBusiness = [];
    foreach ($cartItems as $item) {
      $pId = $item['product']['id'];
      $qty = (int) $item['qty'];

      $product = $products->get($pId);

      // If product exists in DB, use its business_id, else fallback to first demo business
      $businessId = $product?->business_id ?? Business::first()?->id;

      if (!$businessId) {
        continue;
      }

      $itemsByBusiness[$businessId][] = [
        'product' => $product,
        'product_id' => $product?->id,
        'product_name' => $product?->name ?? ($item['product']['name'] ?? 'Produk UMKM'),
        'qty' => $qty,
        'unit_price' => $product ? (float) $product->price : (float) ($item['product']['price'] ?? 50000),
        'unit_cost' => $product ? (float) $product->cost_price : (float) ($item['product']['price'] ?? 50000) * 0.6,
      ];
    }

    $createdOrders = [];

    DB::transaction(function () use ($itemsByBusiness, $user, &$createdOrders) {
      foreach ($itemsByBusiness as $businessId => $items) {
        // Find or create customer record for this business
        $customer = Customer::firstOrCreate(
          [
            'business_id' => $businessId,
            'email' => $user?->email ?? 'budi@grownesia.id',
          ],
          [
            'name' => $user?->name ?? 'Budi Santoso (Pembeli)',
            'phone' => $user?->phone ?? '081234567890',
            'city' => 'Jakarta Selatan',
          ]
        );

        $order = Order::create([
          'business_id' => $businessId,
          'customer_id' => $customer->id,
          'order_number' => Order::generateOrderNumber(),
          'status' => OrderStatus::Paid->value,
          'channel' => OrderChannel::Marketplace->value,
          'total' => 0,
          'total_cost' => 0,
          'notes' => 'Pesanan Pembeli dari Marketplace Grownesia',
          'ordered_at' => now(),
        ]);

        $total = 0;
        $totalCost = 0;

        foreach ($items as $item) {
          $subtotal = $item['unit_price'] * $item['qty'];
          $subtotalCost = $item['unit_cost'] * $item['qty'];

          OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['product_id'],
            'product_name' => $item['product_name'],
            'quantity' => $item['qty'],
            'unit_price' => $item['unit_price'],
            'unit_cost' => $item['unit_cost'],
            'subtotal' => $subtotal,
          ]);

          if ($item['product']) {
            $item['product']->decrement('stock', min($item['qty'], $item['product']->stock));
          }

          $total += $subtotal;
          $totalCost += $subtotalCost;
        }

        $order->update([
          'total' => $total,
          'total_cost' => $totalCost,
        ]);

        $createdOrders[] = $order->order_number;
      }
    });

    // Clear cart items from database after successful checkout
    if ($user) {
      CartItem::where('user_id', $user->id)->delete();
    }

    return response()->json([
      'success' => true,
      'message' => 'Pesanan berhasil dibuat dan tersimpan ke database UMKM.',
      'order_numbers' => $createdOrders,
    ]);
  }
}
