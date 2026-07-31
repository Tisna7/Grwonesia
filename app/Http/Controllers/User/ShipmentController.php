<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\BiteshipService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    protected BiteshipService $biteshipService;

    public function __construct(BiteshipService $biteshipService)
    {
        $this->biteshipService = $biteshipService;
    }

    /**
     * Get real-time Biteship tracking info for a specific user order.
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        $order->loadMissing(['shipment', 'items.product', 'business', 'customer']);

        $waybill = $order->tracking_number ?: 'BITESHIP-JNE-' . $order->id;
        $courier = $order->courier ?: 'JNE';

        // Query real-time tracking data from Biteship API
        $biteshipData = $this->biteshipService->trackWaybill($waybill, $courier);

        if (!empty($biteshipData['current_location'])) {
            $order->update(['current_location' => $biteshipData['current_location']]);
        }

        $formattedTimeline = [];
        if (!empty($biteshipData['history'])) {
            $formattedTimeline = $biteshipData['history'];
        } else {
            $formattedTimeline = [
                [
                    'time' => $order->ordered_at ? $order->ordered_at->format('d M, H:i') : now()->format('d M, H:i'),
                    'location' => $order->business?->city ?? 'Gudang Penjual UMKM',
                    'desc' => 'Pesanan dikemas & diproses via Biteship Express',
                    'status' => 'packed',
                    'icon' => 'package',
                    'done' => true,
                ],
                [
                    'time' => now()->format('d M, H:i'),
                    'location' => $order->current_location ?: 'Hub Logistik Biteship',
                    'desc' => 'Status Pengiriman: ' . ucfirst($order->shipping_status ?? 'packed'),
                    'status' => $order->shipping_status ?? 'packed',
                    'icon' => 'truck',
                    'done' => true,
                ],
            ];
        }

        return response()->json([
            'success' => true,
            'biteship' => $biteshipData,
            'tracking_number' => $waybill,
            'courier' => strtoupper($courier),
            'courier_name' => $order->courier ?: 'JNE Express',
            'status' => $biteshipData['status'] ?? $order->shipping_status,
            'current_location' => $biteshipData['current_location'] ?? ($order->current_location ?: 'Gudang Penjual'),
            'timeline' => $formattedTimeline,
            'estimated_arrival' => $order->estimated_arrival ?: '2-3 Hari Kerja',
        ]);
    }
}
