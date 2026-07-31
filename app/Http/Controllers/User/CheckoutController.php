<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Models\Business;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\CartItem;
use App\Services\MidtransService;
use App\Services\BiteshipService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
  protected MidtransService $midtransService;
  protected BiteshipService $biteshipService;

  public function __construct(MidtransService $midtransService, BiteshipService $biteshipService)
  {
    $this->midtransService = $midtransService;
    $this->biteshipService = $biteshipService;
  }

  public function store(Request $request): JsonResponse
  {
    $request->validate([
      'cart' => 'required|array|min:1',
      'cart.*.product.id' => 'required|integer',
      'cart.*.qty' => 'required|integer|min:1',
      'payment_method' => 'nullable|string',
      'shipping_address' => 'nullable|string',
    ]);

    $user = Auth::user();
    $cartItems = $request->input('cart');
    $courier = $request->input('courier', 'JNE Express (REG)');
    $shippingCost = (float) $request->input('shipping_cost', 12000);
    $shippingAddress = $request->input('shipping_address', $user?->address ?: 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan');

    // Extract product IDs
    $productIds = array_column(array_column($cartItems, 'product'), 'id');
    $products = Product::with('business')->whereIn('id', $productIds)->get()->keyBy('id');

    // Group cart items by business_id
    $itemsByBusiness = [];
    foreach ($cartItems as $item) {
      $pId = $item['product']['id'];
      $qty = (int) $item['qty'];

      $product = $products->get($pId);
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
    $firstOrder = null;

    DB::transaction(function () use ($itemsByBusiness, $user, $shippingAddress, $courier, $shippingCost, &$createdOrders, &$firstOrder) {
      foreach ($itemsByBusiness as $businessId => $items) {
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
          'user_id' => $user?->id,
          'business_id' => $businessId,
          'customer_id' => $customer->id,
          'order_number' => Order::generateOrderNumber(),
          'status' => OrderStatus::Pending->value,
          'channel' => OrderChannel::Marketplace->value,
          'total' => 0,
          'total_cost' => 0,
          'courier' => $courier,
          'tracking_number' => null,
          'shipping_status' => 'pending',
          'shipping_address' => $shippingAddress,
          'shipping_cost' => $shippingCost,
          'current_location' => null,
          'notes' => 'Pesanan Pembeli via Grownesia Marketplace with Midtrans & Biteship Integration',
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
          'total' => $total + $shippingCost,
          'total_cost' => $totalCost,
        ]);

        $createdOrders[] = $order->order_number;
        if (!$firstOrder) {
          $firstOrder = $order;
        }
      }
    });

    // Clear checked-out cart items from database
    if ($user && !empty($productIds)) {
      CartItem::where('user_id', $user->id)->whereIn('product_id', $productIds)->delete();
    }

    // Generate Midtrans Snap Token
    $snapResult = [];
    if ($firstOrder) {
      $snapResult = $this->midtransService->createSnapToken($firstOrder);
    }

    return response()->json([
      'success' => true,
      'message' => 'Pesanan berhasil dibuat dan terintegrasi dengan Midtrans & Biteship.',
      'order_numbers' => $createdOrders,
      'snap_token' => $snapResult['snap_token'] ?? null,
      'redirect_url' => $snapResult['redirect_url'] ?? null,
      'client_key' => $snapResult['client_key'] ?? config('services.midtrans.client_key'),
    ]);
  }

  /**
   * Midtrans Webhook Notification Callback
   */
  public function handleMidtransNotification(Request $request): JsonResponse
  {
    $payload = $request->all();
    Log::info('Midtrans Webhook Received:', $payload);

    $result = $this->midtransService->handleNotification($payload);

    return response()->json($result);
  }

  /**
   * Calculate Shipping Rates via Biteship API (address-based)
   */
  public function getShippingRates(Request $request): JsonResponse
  {
    $address = $request->input('address', '');
    $postalCode = $request->input('postal_code');
    $areaId = $request->input('area_id');
    $city = $request->input('city', '');
    $items = $request->input('items', []);

    // If no postal code or area_id provided, try to extract from address text
    if (!$postalCode && !$areaId && $address) {
      // Try to extract postal code from address string (5 digit number)
      if (preg_match('/\b(\d{5})\b/', $address, $m)) {
        $postalCode = $m[1];
      }
      // Try to extract city name from address
      if (!$city) {
        $city = $address;
      }
    }

    $ratesResult = $this->biteshipService->getShippingRates([
      'destination_postal_code' => $postalCode,
      'destination_area_id' => $areaId,
      'destination_city' => $city,
      'items' => $items,
    ]);

    return response()->json($ratesResult);
  }

  /**
   * Search Biteship Areas (for address autocomplete)
   */
  public function searchArea(Request $request): JsonResponse
  {
    $query = $request->input('query', '');
    $result = $this->biteshipService->searchArea($query);
    return response()->json($result);
  }
}
