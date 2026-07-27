<?php

namespace App\Services\Ai;

use App\Enums\OrderStatus;
use App\Models\AiInsight;
use App\Models\Business;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryPredictionService
{
    public function __construct(private readonly GeminiClient $gemini) {}

    /**
     * Prediksi stok deterministik: kecepatan jual 30 hari → perkiraan tanggal habis.
     */
    public function predictions(Business $business): array
    {
        $now = Carbon::now();
        $velocities = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.business_id', $business->id)
            ->whereIn('orders.status', OrderStatus::revenueStatuses())
            ->where('orders.ordered_at', '>=', $now->copy()->subDays(30))
            ->whereNotNull('order_items.product_id')
            ->groupBy('order_items.product_id')
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as qty'))
            ->pluck('qty', 'product_id');

        return $business->products()->active()->orderBy('name')->get()
            ->map(function ($product) use ($velocities) {
                $soldLast30 = (int) ($velocities[$product->id] ?? 0);
                $dailyVelocity = $soldLast30 / 30;
                $daysLeft = $dailyVelocity > 0 ? (int) floor($product->stock / $dailyVelocity) : null;

                return [
                    'product' => $product,
                    'sold_last_30' => $soldLast30,
                    'daily_velocity' => round($dailyVelocity, 2),
                    'days_left' => $daysLeft,
                    'stockout_date' => $daysLeft !== null ? Carbon::today()->addDays($daysLeft) : null,
                    'level' => match (true) {
                        $daysLeft !== null && $daysLeft < 7 => 'critical',
                        $daysLeft !== null && $daysLeft < 14 => 'warning',
                        default => 'ok',
                    },
                ];
            })
            ->sortBy(fn ($row) => $row['days_left'] ?? PHP_INT_MAX)
            ->values()
            ->all();
    }

    /**
     * Narasi AI atas data prediksi — cache harian di ai_insights.
     */
    public function narrative(Business $business, array $predictions): ?array
    {
        $cached = AiInsight::where('business_id', $business->id)
            ->where('type', 'inventory')
            ->whereDate('date', Carbon::today())
            ->first();

        if ($cached) {
            return $cached->payload;
        }

        if (! $this->gemini->isConfigured()) {
            return null;
        }

        $context = collect($predictions)->map(fn ($row) => [
            'produk' => $row['product']->name,
            'stok' => $row['product']->stock,
            'terjual_30_hari' => $row['sold_last_30'],
            'perkiraan_hari_tersisa' => $row['days_left'],
            'level' => $row['level'],
        ])->all();

        $payload = $this->gemini->generateJson(
            "Data prediksi inventori UMKM \"{$business->name}\":\n".
            json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).
            "\n\nBuat ringkasan prioritas restock untuk pemilik bisnis.",
            [
                'type' => 'object',
                'properties' => [
                    'summary' => ['type' => 'string', 'description' => 'Ringkasan kondisi inventori 1-2 kalimat'],
                    'urgent_actions' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                        'description' => 'Aksi restock paling mendesak dengan estimasi jumlah unit',
                    ],
                ],
                'required' => ['summary', 'urgent_actions'],
            ],
            'Kamu adalah asisten manajemen inventori untuk UMKM Indonesia. Jawab ringkas dan konkret dalam Bahasa Indonesia.',
        );

        if ($payload === null) {
            return null;
        }

        AiInsight::create([
            'business_id' => $business->id,
            'type' => 'inventory',
            'date' => Carbon::today(),
            'payload' => $payload,
        ]);

        return $payload;
    }
}
