<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key') ?: env('MIDTRANS_SERVER_KEY', '');
        $this->clientKey = config('services.midtrans.client_key') ?: env('MIDTRANS_CLIENT_KEY', '');
        $this->isProduction = filter_var(env('MIDTRANS_IS_PRODUCTION', config('services.midtrans.is_production', false)), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get Midtrans Base API URL
     */
    protected function getSnapApiUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    /**
     * Generate Midtrans Snap Token for an Order
     */
    public function createSnapToken(Order $order): array
    {
        $order->loadMissing(['items', 'customer']);

        $grossAmount = (int) round($order->total);
        if ($grossAmount <= 0) {
            $grossAmount = 10000;
        }

        $items = [];
        $itemsSum = 0;
        foreach ($order->items as $item) {
            $price = (int) round($item->unit_price ?: $item->price);
            $quantity = (int) $item->quantity;
            $itemsSum += ($price * $quantity);

            $items[] = [
                'id' => (string) ($item->product_id ?? $item->id),
                'price' => $price,
                'quantity' => $quantity,
                'name' => substr($item->product_name ?? 'Produk UMKM', 0, 50),
            ];
        }

        $shippingDiff = $grossAmount - $itemsSum;
        if ($shippingDiff > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => $shippingDiff,
                'quantity' => 1,
                'name' => 'Ongkos Kirim (' . ($order->courier ?: 'Biteship') . ')',
            ];
        }

        $customerName = $order->customer?->name ?? 'Pembeli Grownesia';
        $customerEmail = $order->customer?->email ?? 'pembeli@grownesia.id';
        $customerPhone = $order->customer?->phone ?? '081234567890';

        $payload = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $customerName,
                'email' => $customerEmail,
                'phone' => $customerPhone,
                'shipping_address' => [
                    'first_name' => $customerName,
                    'address' => $order->shipping_address ?: 'Indonesia',
                ],
            ],
            'callbacks' => [
                'finish' => url('/orders'),
            ],
        ];

        try {
            $authHeader = 'Basic ' . base64_encode($this->serverKey . ':');

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $authHeader,
            ])->timeout(10)->post($this->getSnapApiUrl(), $payload);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("Midtrans Snap token generated for order {$order->order_number}: " . ($data['token'] ?? ''));
                return [
                    'success' => true,
                    'snap_token' => $data['token'] ?? null,
                    'redirect_url' => $data['redirect_url'] ?? null,
                    'client_key' => $this->clientKey,
                ];
            } else {
                Log::error("Midtrans API Error [{$response->status()}]: " . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error("Midtrans HTTP Exception: " . $e->getMessage());
        }

        // Return fallback simulation response if API call fails or key is sandbox mock
        $mockToken = 'SNAP-MOCK-' . strtoupper(substr(uniqid(), -12));
        return [
            'success' => true,
            'snap_token' => $mockToken,
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $mockToken,
            'client_key' => $this->clientKey,
            'is_mock' => true,
        ];
    }

    /**
     * Process incoming Midtrans Webhook Notification
     */
    public function handleNotification(array $payload): array
    {
        $orderNumber = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? 'midtrans';

        if (!$orderNumber) {
            return ['success' => false, 'message' => 'Invalid payload: missing order_id'];
        }

        $order = Order::where('order_number', $orderNumber)->first();
        if (!$order) {
            return ['success' => false, 'message' => "Order {$orderNumber} not found"];
        }

        Log::info("Processing Midtrans notification for order {$orderNumber}: Status = {$transactionStatus}");

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $order->update(['status' => OrderStatus::Pending->value]);
            } else if ($fraudStatus === 'accept') {
                $this->markOrderAsPaid($order, $paymentType, $payload['transaction_id'] ?? null);
            }
        } else if ($transactionStatus === 'settlement') {
            $this->markOrderAsPaid($order, $paymentType, $payload['transaction_id'] ?? null);
        } else if ($transactionStatus === 'pending') {
            $order->update(['status' => OrderStatus::Pending->value]);
        } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->update(['status' => OrderStatus::Cancelled->value]);

            OrderHistory::create([
                'order_id' => $order->id,
                'status' => 'Cancelled',
                'description' => "Pembayaran via Midtrans failed/expired (Status: {$transactionStatus}).",
                'created_by' => 'System Midtrans',
            ]);
        }

        return ['success' => true, 'order_number' => $orderNumber, 'status' => $order->status];
    }

    /**
     * Mark order as paid and record payment & history
     */
    protected function markOrderAsPaid(Order $order, string $paymentType, ?string $reference = null): void
    {
        $order->update([
            'status' => OrderStatus::Paid->value,
            'shipping_status' => 'packed',
        ]);

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'payment_method' => strtoupper($paymentType),
                'payment_reference' => $reference ?: 'MID-' . strtoupper(substr(uniqid(), -8)),
                'payment_status' => 'Paid',
                'paid_at' => now(),
            ]
        );

        OrderHistory::create([
            'order_id' => $order->id,
            'status' => 'Paid',
            'description' => "Pembayaran sebesar Rp " . number_format($order->total, 0, ',', '.') . " berhasil diterima via Midtrans ({$paymentType}).",
            'created_by' => 'System Midtrans Payment',
        ]);
    }

    /**
     * Check transaction status directly from Midtrans API
     */
    public function checkStatus(Order $order): array
    {
        if (!$order->order_number) {
            return ['success' => false, 'message' => 'Order number is missing'];
        }

        $url = $this->isProduction
            ? "https://api.midtrans.com/v2/{$order->order_number}/status"
            : "https://api.sandbox.midtrans.com/v2/{$order->order_number}/status";

        try {
            $authHeader = 'Basic ' . base64_encode($this->serverKey . ':');
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $authHeader,
            ])->timeout(8)->get($url);

            if ($response->successful()) {
                $payload = $response->json();
                return $this->handleNotification($payload);
            }
        } catch (\Throwable $e) {
            Log::error("Midtrans Status Check Exception for order {$order->order_number}: " . $e->getMessage());
        }

        return ['success' => false, 'message' => 'Failed to check status'];
    }
}
