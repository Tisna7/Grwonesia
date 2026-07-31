<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Get order history for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'orders' => []], 401);
        }

        $orders = Order::where('user_id', $user->id)
            ->with(['items.product', 'business', 'histories', 'payment', 'shipment', 'reviews'])
            ->orderByDesc('ordered_at')
            ->orderByDesc('id')
            ->get();

        $formatted = $orders->map(function ($order) {
            $firstItem = $order->items->first();
            $hasReview = $order->reviews()->exists();

            $itemsSummary = $order->items->map(function ($item) {
                $pName = $item->product_name ?: ($item->product?->name ?? 'Produk');
                return "{$pName} ({$item->quantity}x)";
            })->join(', ');

            return [
                'id' => $order->order_number ?: ('GRW-' . $order->id),
                'db_id' => $order->id,
                'date' => $order->ordered_at ? $order->ordered_at->translatedFormat('d M Y') : 'Hari ini',
                'productId' => $firstItem?->product_id ?? 1,
                'productName' => $firstItem?->product_name ?: ($firstItem?->product?->name ?? 'Produk UMKM'),
                'items' => $itemsSummary ?: 'Product UMKM',
                'total' => (float) ($order->grand_total ?: $order->total),
                'status' => ucfirst($order->order_status ?: (is_object($order->status) && isset($order->status->value) ? $order->status->value : (is_string($order->status) ? $order->status : 'pending'))),
                'paymentStatus' => $order->payment_status ?: 'Paid',
                'paymentMethod' => $order->payment_method ?: 'QRIS',
                'impact' => 'Pemberdayaan UMKM & Pekerja Lokal',
                'reviewed' => $hasReview,
                'courier' => $order->courier ?: 'JNE Express',
                'trackingNumber' => $order->tracking_number ?: ('GRW-TRK-' . $order->id),
                'shippingStatus' => ucfirst($order->shipping_status ?: 'pending'),
                'currentLocation' => $order->current_location ?: 'Gudang Penjual',
                'estimatedArrival' => $order->estimated_arrival ?: '2-3 Hari Kerja',
                'shippingAddress' => $order->shipping_address ?: 'Jl. Sudirman No. 45, Jakarta',
                'businessName' => $order->business?->name ?? 'UMKM Mitra',
                'productImage' => $firstItem?->product?->image_url ?? asset('images/products/kopi_gula_aren.webp'),
                'timeline' => $this->formatTimeline($order),
            ];
        });

        return response()->json([
            'success' => true,
            'orders' => $formatted,
        ]);
    }

    /**
     * Show order details.
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $order->load(['items.product', 'business', 'histories', 'payment', 'shipment', 'reviews']);

        return response()->json([
            'success' => true,
            'order' => $order,
            'timeline' => $this->formatTimeline($order),
        ]);
    }

    /**
     * Format timeline from order_histories table or shipping_timeline.
     */
    private function formatTimeline(Order $order): array
    {
        $histories = $order->histories;

        if ($histories->isNotEmpty()) {
            return $histories->map(function ($h) {
                return [
                    'done' => true,
                    'desc' => $h->description ?: "Status: {$h->status}",
                    'time' => $h->created_at ? $h->created_at->translatedFormat('d M Y, H:i') : '',
                    'location' => "Oleh: " . ($h->created_by ?: 'System'),
                ];
            })->toArray();
        }

        // Fallback if histories is empty
        return [
            [
                'done' => true,
                'desc' => 'Pesanan berhasil dibuat & dikonfirmasi oleh sistem.',
                'time' => $order->ordered_at ? $order->ordered_at->translatedFormat('d M Y, H:i') : now()->translatedFormat('d M Y, H:i'),
                'location' => 'Jakarta (Pusat Logistik)',
            ]
        ];
    }

    /**
     * Mark order as received/delivered by user.
     */
    public function confirmReceived(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required',
        ]);

        $orderId = $request->input('order_id');

        $order = Order::where('id', $orderId)
            ->orWhere('order_number', $orderId)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        $order->update([
            'shipping_status' => 'delivered',
            'order_status' => 'completed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dikonfirmasi diterima! Anda sekarang dapat memberikan ulasan produk.',
            'order' => [
                'id' => $order->order_number ?: ('GRW-' . $order->id),
                'db_id' => $order->id,
                'status' => 'Selesai',
            ],
        ]);
    }
}
