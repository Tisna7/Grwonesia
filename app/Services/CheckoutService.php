<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    /**
     * Process user checkout with stock validation and DB transaction.
     */
    public function processCheckout(User $user, array $cartItemsData, array $checkoutOptions = []): array
    {
        return DB::transaction(function () use ($user, $cartItemsData, $checkoutOptions) {
            $paymentMethod = $checkoutOptions['payment_method'] ?? 'QRIS';
            $shippingAddress = $checkoutOptions['shipping_address'] ?? ($user->address ?: 'Jl. Sudirman No. 45, Jakarta');
            $notes = $checkoutOptions['notes'] ?? 'Order dari customer portal';

            // Group cart items by business_id to support multi-merchant or single-merchant checkout
            $itemsByBusiness = [];

            foreach ($cartItemsData as $item) {
                $productId = $item['product_id'] ?? ($item['id'] ?? null);
                if (!$productId && isset($item['product']['id'])) {
                    $productId = $item['product']['id'];
                }

                $qty = (int) ($item['quantity'] ?? ($item['qty'] ?? 1));
                if ($qty <= 0) {
                    $qty = 1;
                }

                $product = Product::lockForUpdate()->find($productId);

                if (!$product) {
                    throw ValidationException::withMessages([
                        'cart' => ["Produk dengan ID {$productId} tidak ditemukan."],
                    ]);
                }

                if ($product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'stock' => ["Stok produk '{$product->name}' tidak mencukupi (Tersisa: {$product->stock})."],
                    ]);
                }

                $businessId = $product->business_id;
                if (!isset($itemsByBusiness[$businessId])) {
                    $itemsByBusiness[$businessId] = [];
                }

                $itemsByBusiness[$businessId][] = [
                    'product' => $product,
                    'qty' => $qty,
                ];
            }

            $createdOrders = [];

            foreach ($itemsByBusiness as $businessId => $groupItems) {
                $subtotal = 0;
                $totalCost = 0;

                foreach ($groupItems as $gItem) {
                    $prod = $gItem['product'];
                    $q = $gItem['qty'];
                    $subtotal += (float) $prod->price * $q;
                    $totalCost += (float) $prod->cost_price * $q;
                }

                $shippingCost = 0; // Standardized subsidy or 15000
                $discount = 0;
                $grandTotal = $subtotal + $shippingCost - $discount;
                $orderNumber = Order::generateOrderNumber();

                // 1. Create Order
                $order = Order::create([
                    'business_id' => $businessId,
                    'user_id' => $user->id,
                    'order_number' => $orderNumber,
                    'status' => 'paid',
                    'order_status' => 'Processing',
                    'channel' => 'marketplace',
                    'total' => $subtotal,
                    'total_price' => $subtotal,
                    'total_cost' => $totalCost,
                    'shipping_cost' => $shippingCost,
                    'discount' => $discount,
                    'grand_total' => $grandTotal,
                    'payment_method' => $paymentMethod,
                    'payment_status' => 'Paid',
                    'shipping_address' => $shippingAddress,
                    'courier' => 'JNE Express',
                    'tracking_number' => 'GRW-TRK-' . strtoupper(substr(uniqid(), -6)),
                    'shipping_status' => 'pending',
                    'estimated_arrival' => '2-3 Hari Kerja',
                    'current_location' => 'Gudang Penjual',
                    'notes' => $notes,
                    'ordered_at' => now(),
                ]);

                // 2. Create Order Items & Decrement Stock
                foreach ($groupItems as $gItem) {
                    $prod = $gItem['product'];
                    $q = $gItem['qty'];
                    $itemSubtotal = (float) $prod->price * $q;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $prod->id,
                        'business_id' => $businessId,
                        'product_name' => $prod->name,
                        'quantity' => $q,
                        'unit_price' => $prod->price,
                        'price' => $prod->price,
                        'unit_cost' => $prod->cost_price,
                        'subtotal' => $itemSubtotal,
                    ]);

                    $prod->decrement('stock', $q);
                }

                // 3. Create Payment record
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $paymentMethod,
                    'payment_reference' => 'PAY-' . strtoupper(substr(uniqid(), -8)),
                    'payment_status' => 'Paid',
                    'paid_at' => now(),
                ]);

                // 4. Create Shipment record
                Shipment::create([
                    'order_id' => $order->id,
                    'courier_service' => 'JNE Express (Reguler)',
                    'tracking_number' => $order->tracking_number,
                    'shipment_status' => 'pending',
                    'estimated_delivery' => '2-3 Hari Kerja',
                ]);

                // 5. Create initial OrderHistories
                OrderHistory::create([
                    'order_id' => $order->id,
                    'status' => 'Pending',
                    'description' => 'Pesanan berhasil dibuat oleh pembeli.',
                    'created_by' => $user->name,
                ]);

                OrderHistory::create([
                    'order_id' => $order->id,
                    'status' => 'Paid',
                    'description' => "Pembayaran sebesar Rp " . number_format($grandTotal, 0, ',', '.') . " telah dikonfirmasi via {$paymentMethod}.",
                    'created_by' => 'System Payment Gateway',
                ]);

                OrderHistory::create([
                    'order_id' => $order->id,
                    'status' => 'Processing',
                    'description' => 'Pesanan telah diteruskan ke UMKM Penjual untuk dikemas.',
                    'created_by' => 'System Marketplace',
                ]);

                $createdOrders[] = $order;
            }

            // 6. Clear user cart
            CartItem::where('user_id', $user->id)->delete();

            return $createdOrders;
        });
    }
}
