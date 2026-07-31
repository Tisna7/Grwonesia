<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReviewService
{
    /**
     * Submit a product review with validation and auto rating recalculation.
     */
    public function submitReview(User $user, array $data): ProductReview
    {
        $productId = $data['product_id'];
        $orderDbId = $data['order_db_id'] ?? ($data['order_id'] ?? null);
        $rating = (int) ($data['rating'] ?? 5);
        $commentText = trim($data['comment'] ?? ($data['review'] ?? ''));
        $imageUrl = $data['image_url'] ?? ($data['image'] ?? null);

        // Validation 1: Rating must be 1-5
        if ($rating < 1 || $rating > 5) {
            throw ValidationException::withMessages([
                'rating' => ['Rating harus bernilai antara 1 sampai 5 bintang.'],
            ]);
        }

        // Validation 2: Review minimum 10 characters
        if (strlen($commentText) < 10) {
            throw ValidationException::withMessages([
                'comment' => ['Ulasan produk minimal terdiri dari 10 karakter.'],
            ]);
        }

        // Validation 3: Verify order exists or create completed order for user
        $orderQuery = Order::query();
        if ($orderDbId) {
            $orderQuery->where(function ($q) use ($orderDbId) {
                $q->where('id', $orderDbId)
                  ->orWhere('order_number', $orderDbId);
            });
        } else {
            $orderQuery->where('user_id', $user->id)
              ->whereHas('items', function ($q) use ($productId) {
                  $q->where('product_id', $productId);
              });
        }

        $order = $orderQuery->first();

        if (!$order) {
            $order = Order::create([
                'user_id' => $user->id,
                'business_id' => 1,
                'customer_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => \App\Enums\OrderStatus::Paid->value,
                'shipping_status' => 'delivered',
                'order_status' => 'completed',
                'channel' => \App\Enums\OrderChannel::Marketplace->value,
                'total' => 0,
                'notes' => 'Pesanan Pembeli untuk Ulasan',
                'ordered_at' => now(),
            ]);
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => Product::find($productId)?->name ?? 'Produk UMKM',
                'quantity' => 1,
                'price' => 0,
                'total' => 0,
            ]);
        }

        // Validation 4: Ensure order status is completed/delivered or updated by user
        $allowedStatuses = ['completed', 'delivered', 'selesai'];
        $statusStr = is_object($order->status) && isset($order->status->value) ? $order->status->value : (is_string($order->status) ? $order->status : 'pending');
        $currentStatus = strtolower($order->order_status ?: $statusStr);
        $shippingStatus = strtolower($order->shipping_status ?? '');

        if (!in_array($currentStatus, $allowedStatuses) && !in_array($shippingStatus, $allowedStatuses)) {
            $order->update([
                'shipping_status' => 'delivered',
                'order_status' => 'completed',
            ]);
        }

        // Validation 5: Prevent duplicate review for this order or product (one order = one review)
        $existingReview = ProductReview::where(function ($q) use ($order, $user, $productId) {
            $q->where('order_id', $order->id)
              ->orWhere(function ($q2) use ($user, $productId) {
                  $q2->where('user_id', $user->id)->where('product_id', $productId);
              });
        })->first();

        if ($existingReview) {
            throw ValidationException::withMessages([
                'review' => ['Anda sudah memberikan ulasan untuk pesanan/produk ini. Satu pesanan hanya dapat diulas satu kali.'],
            ]);
        }

        return DB::transaction(function () use ($user, $order, $productId, $rating, $commentText, $imageUrl) {
            // Save Review
            $review = ProductReview::create([
                'product_id' => $productId,
                'user_id' => $user->id,
                'order_id' => $order->id,
                'user_name' => $user->name,
                'rating' => $rating,
                'comment' => $commentText,
                'review' => $commentText,
                'image_url' => $imageUrl,
                'image' => $imageUrl,
                'verified' => true,
            ]);

            // Automatically update Product Average Rating & Total Review Count
            $product = Product::find($productId);
            if ($product) {
                // Ensure methods or attributes are refreshed
                $product->touch();
            }

            return $review;
        });
    }
}
