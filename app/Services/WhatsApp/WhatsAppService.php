<?php

namespace App\Services\WhatsApp;

use App\Models\Business;
use App\Models\Customer;
use App\Models\Order;
use App\Models\WaMessage;
use Illuminate\Support\Collection;

class WhatsAppService
{
    public function __construct(private readonly WhatsAppGateway $gateway) {}

    public function isLive(): bool
    {
        return $this->gateway->isLive();
    }

    /**
     * Konfirmasi otomatis saat pesanan dibuat (jika pelanggan punya nomor).
     */
    public function sendOrderConfirmation(Order $order): ?WaMessage
    {
        $order->loadMissing(['customer', 'business', 'items']);

        $phone = $order->customer?->phone;

        if (blank($phone)) {
            return null;
        }

        $itemLines = $order->items
            ->map(fn ($i) => "• {$i->product_name} x{$i->quantity} = ".rupiah($i->subtotal))
            ->implode("\n");

        $body = "Halo {$order->customer->name}! 👋\n\n".
            "Pesanan Anda di *{$order->business->name}* telah kami terima:\n\n".
            "No. Pesanan: {$order->order_number}\n{$itemLines}\n\n".
            'Total: *'.rupiah($order->total)."*\n\nTerima kasih sudah berbelanja produk lokal! 🌱";

        return $this->dispatch($order->business, $phone, 'order_confirmation', $body, [
            'customer_id' => $order->customer_id,
            'order_id' => $order->id,
        ]);
    }

    /**
     * Broadcast massal ke pelanggan terpilih.
     *
     * @param  Collection<int, Customer>  $customers
     * @return array{sent: int, skipped: int}
     */
    public function broadcast(Business $business, string $body, Collection $customers): array
    {
        $sent = 0;
        $skipped = 0;

        foreach ($customers as $customer) {
            if (blank($customer->phone)) {
                $skipped++;

                continue;
            }

            $this->dispatch($business, $customer->phone, 'broadcast', $body, [
                'customer_id' => $customer->id,
            ]);
            $sent++;
        }

        return ['sent' => $sent, 'skipped' => $skipped];
    }

    private function dispatch(Business $business, string $phone, string $type, string $body, array $extra = []): WaMessage
    {
        $message = WaMessage::create([
            'business_id' => $business->id,
            'customer_id' => $extra['customer_id'] ?? null,
            'order_id' => $extra['order_id'] ?? null,
            'to_number' => $phone,
            'type' => $type,
            'body' => $body,
            'status' => 'pending',
        ]);

        $result = $this->gateway->send($phone, $body);

        $message->update([
            'status' => $result->success ? ($this->gateway->isLive() ? 'sent' : 'mocked') : 'failed',
            'provider_response' => $result->raw ?: ['id' => $result->providerMessageId],
            'sent_at' => $result->success ? now() : null,
        ]);

        return $message;
    }
}
