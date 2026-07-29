<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Confirm payment for an order and log history.
     */
    public function confirmPayment(Order $order, string $paymentMethod = 'QRIS', ?string $reference = null): Payment
    {
        return DB::transaction(function () use ($order, $paymentMethod, $reference) {
            $order->update([
                'status' => 'paid',
                'order_status' => 'Processing',
                'payment_status' => 'Paid',
                'payment_method' => $paymentMethod,
            ]);

            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payment_method' => $paymentMethod,
                    'payment_reference' => $reference ?: 'PAY-' . strtoupper(substr(uniqid(), -8)),
                    'payment_status' => 'Paid',
                    'paid_at' => now(),
                ]
            );

            OrderHistory::create([
                'order_id' => $order->id,
                'status' => 'Paid',
                'description' => "Pembayaran sebesar Rp " . number_format($order->grand_total ?: $order->total, 0, ',', '.') . " berhasil diverifikasi.",
                'created_by' => 'System Payment',
            ]);

            return $payment;
        });
    }
}
