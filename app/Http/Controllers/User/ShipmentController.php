<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * Get tracking info for a specific order.
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        $order->load(['shipment', 'histories', 'items.product', 'business']);

        return response()->json([
            'success' => true,
            'shipment' => $order->shipment,
            'tracking_number' => $order->tracking_number,
            'courier' => $order->courier,
            'status' => $order->shipping_status,
            'timeline' => $order->histories,
        ]);
    }
}
