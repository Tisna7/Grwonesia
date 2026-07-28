<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->business;

        $query = $business->orders()->with(['customer', 'items.product']);

        if ($request->filled('status')) {
            $query->where('shipping_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhere('courier', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->orderByDesc('ordered_at')->paginate(15)->withQueryString();

        // Calculate KPI Metrics
        $allOrders = $business->orders()->get();
        $totalShipment = $allOrders->count();
        $pendingShipment = $allOrders->filter(fn ($o) => in_array($o->shipping_status, ['pending', null]))->count();
        $inDelivery = $allOrders->filter(fn ($o) => in_array($o->shipping_status, ['packed', 'shipped', 'in_transit', 'out_for_delivery']))->count();
        $delivered = $allOrders->where('shipping_status', 'delivered')->count();
        $returnShipment = $allOrders->filter(fn ($o) => in_array($o->shipping_status, ['return', 'cancelled']))->count();

        $kpis = [
            'total' => [
                'count' => $totalShipment,
                'percentage' => '+14.2%',
                'trend' => 'up',
            ],
            'pending' => [
                'count' => $pendingShipment,
                'percentage' => '-3.5%',
                'trend' => 'down',
            ],
            'in_delivery' => [
                'count' => $inDelivery,
                'percentage' => '+8.7%',
                'trend' => 'up',
            ],
            'delivered' => [
                'count' => $delivered,
                'percentage' => '+18.4%',
                'trend' => 'up',
            ],
            'return' => [
                'count' => $returnShipment,
                'percentage' => '-1.2%',
                'trend' => 'down',
            ],
        ];

        $couriers = [
            'JNE',
            'J&T',
            'SiCepat',
            'POS Indonesia',
            'AnterAja',
            'Ninja Xpress',
            'Custom Courier',
        ];

        return view('business.shipping.index', compact('orders', 'kpis', 'couriers'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->business_id === $request->user()->business?->id, 404);

        $request->validate([
            'courier' => ['required', 'string', 'max:50'],
            'tracking_number' => ['nullable', 'string', 'max:50'],
            'shipping_status' => ['required', 'in:pending,packed,shipped,delivered,cancelled,return'],
            'estimated_arrival' => ['nullable', 'string', 'max:50'],
            'current_location' => ['nullable', 'string', 'max:100'],
            'shipping_address' => ['nullable', 'string'],
        ]);

        $status = $request->shipping_status;
        $timeline = $order->shipping_timeline ?? [];

        $timestamp = now()->translatedFormat('d M Y, H:i');
        $location = $request->current_location ?: ($order->business?->city ?: 'Gudang Penjual');

        // Append new step if status changed or timeline empty
        $stepDescriptions = [
            'pending' => ['title' => 'Menunggu Pengiriman', 'icon' => 'clock', 'desc' => 'Pesanan menunggu diproses oleh penjual.'],
            'packed' => ['title' => 'Order Packed', 'icon' => 'package', 'desc' => 'Paket telah dikemas rapi oleh seller.'],
            'shipped' => ['title' => 'Courier Picked Up / In Transit', 'icon' => 'truck', 'desc' => "Paket diambil oleh kurir {$request->courier} dan dalam perjalanan."],
            'delivered' => ['title' => 'Delivered', 'icon' => 'check-circle', 'desc' => 'Paket telah berhasil diterima oleh penerima.'],
            'return' => ['title' => 'Return Shipment', 'icon' => 'rotate-ccw', 'desc' => 'Paket dikembalikan ke pengirim.'],
            'cancelled' => ['title' => 'Pengiriman Dibatalkan', 'icon' => 'x-circle', 'desc' => 'Pengiriman pesanan dibatalkan.'],
        ];

        $descInfo = $stepDescriptions[$status] ?? ['title' => ucfirst($status), 'icon' => 'info', 'desc' => 'Status pengiriman diperbarui.'];

        $newStep = [
            'status' => $status,
            'title' => $descInfo['title'],
            'icon' => $descInfo['icon'],
            'timestamp' => $timestamp,
            'location' => $location,
            'description' => $descInfo['desc'],
        ];

        // Ensure step is not duplicated consecutively
        if (empty($timeline) || end($timeline)['status'] !== $status) {
            $timeline[] = $newStep;
        }

        $updateData = [
            'courier' => $request->courier,
            'tracking_number' => $request->tracking_number,
            'shipping_status' => $status,
            'estimated_arrival' => $request->estimated_arrival ?: '2-3 Hari Kerja',
            'current_location' => $location,
            'shipping_address' => $request->shipping_address ?: $order->customer?->city,
            'shipping_timeline' => $timeline,
        ];

        // Sync main order status
        if ($status === 'shipped') {
            $updateData['status'] = 'shipped';
        } elseif ($status === 'delivered') {
            $updateData['status'] = 'completed';
        } elseif ($status === 'cancelled') {
            $updateData['status'] = 'cancelled';
        }

        $order->update($updateData);

        return back()->with('success', "Informasi pengiriman pesanan {$order->order_number} berhasil diperbarui.");
    }
}
