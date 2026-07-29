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

        // Validation 3: Verify order exists, belongs to user, and includes the product
        $orderQuery = Order::where('user_id', $user->id);
        if ($orderDbId) {
            $orderQuery->where('id', $orderDbId);
        } else {
            $orderQuery->whereHas('items', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            });
        }

        $order = $orderQuery->first();

        if (!$order) {
            throw ValidationException::withMessages([
                'order' => ['Anda tidak memiliki riwayat pembelian untuk produk ini.'],
            ]);
        }

        // Validation 4: Order status must be completed/delivered
        $allowedStatuses = ['completed', 'delivered', 'selesai'];
        $statusStr = is_object($order->status) && isset($order->status->value) ? $order->status->value : (is_string($order->status) ? $order->status : 'pending');
        $currentStatus = strtolower($order->order_status ?: $statusStr);
        $shippingStatus = strtolower($order->shipping_status ?? '');

        if (!in_array($currentStatus, $allowedStatuses) && $shippingStatus !== 'delivered') {
            throw ValidationException::withMessages([
                'status' => ['Ulasan hanya dapat ditulis jika barang sudah diterima (status pesanan Selesai / Delivered).'],
            ]);
        }

        // Validation 5: Prevent duplicate review for this order (one order = one review)
        $existingReview = ProductReview::where('order_id', $order->id)->first();

        if ($existingReview) {
            throw ValidationException::withMessages([
                'review' => ['Pesanan ini sudah pernah diberikan ulasan. Satu pesanan hanya dapat diulas satu kali.'],
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
