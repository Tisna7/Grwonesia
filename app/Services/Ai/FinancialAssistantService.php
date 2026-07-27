<?php

namespace App\Services\Ai;

use App\Enums\OrderStatus;
use App\Models\AiInsight;
use App\Models\Business;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialAssistantService
{
    public function __construct(private readonly GeminiClient $gemini) {}

    /**
     * Analisis keuangan deterministik: margin per produk, proyeksi cashflow 30 hari, BEP.
     */
    public function analysis(Business $business): array
    {
        $now = Carbon::now();
        $last30Start = $now->copy()->subDays(30);
        $revenueStatuses = OrderStatus::revenueStatuses();

        // Margin per produk (30 hari)
        $productMargins = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.business_id', $business->id)
            ->whereIn('orders.status', $revenueStatuses)
            ->where('orders.ordered_at', '>=', $last30Start)
            ->whereNotNull('order_items.product_id')
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.subtotal) as revenue'),
                DB::raw('SUM(order_items.unit_cost * order_items.quantity) as cost'),
                DB::raw('SUM(order_items.quantity) as qty'),
            )
            ->orderByDesc('revenue')
            ->get()
            ->map(function ($row) {
                $revenue = (float) $row->revenue;
                $cost = (float) $row->cost;
                $profit = $revenue - $cost;

                return [
                    'name' => $row->product_name,
                    'qty' => (int) $row->qty,
                    'revenue' => $revenue,
                    'profit' => $profit,
                    'margin_pct' => $revenue > 0 ? round($profit / $revenue * 100, 1) : 0,
                ];
            })
            ->all();

        // Rata-rata harian 30 hari terakhir
        $daily = DB::table('orders')
            ->where('business_id', $business->id)
            ->whereIn('status', $revenueStatuses)
            ->where('ordered_at', '>=', $last30Start)
            ->selectRaw('SUM(total) as revenue, SUM(total_cost) as cost')
            ->first();

        $avgDailyRevenue = ((float) ($daily->revenue ?? 0)) / 30;
        $avgDailyCost = ((float) ($daily->cost ?? 0)) / 30;
        $fixedDaily = (float) $business->monthly_fixed_cost / 30;

        // Proyeksi cashflow kumulatif 30 hari ke depan
        $projection = [];
        $cumulative = 0.0;
        for ($i = 1; $i <= 30; $i++) {
            $cumulative += $avgDailyRevenue - $avgDailyCost - $fixedDaily;
            $projection[] = [
                'label' => Carbon::today()->addDays($i)->format('d M'),
                'value' => round($cumulative),
            ];
        }

        // BEP: biaya tetap bulanan / rasio margin kontribusi
        $totalRevenue30 = (float) ($daily->revenue ?? 0);
        $contributionMarginRatio = $totalRevenue30 > 0
            ? ($totalRevenue30 - (float) ($daily->cost ?? 0)) / $totalRevenue30
            : 0;
        $bepMonthly = $contributionMarginRatio > 0
            ? (float) $business->monthly_fixed_cost / $contributionMarginRatio
            : null;

        return [
            'product_margins' => $productMargins,
            'projection' => $projection,
            'avg_daily_revenue' => $avgDailyRevenue,
            'avg_daily_cost' => $avgDailyCost,
            'monthly_fixed_cost' => (float) $business->monthly_fixed_cost,
            'net_profit_30' => $totalRevenue30 - (float) ($daily->cost ?? 0) - (float) $business->monthly_fixed_cost,
            'contribution_margin_pct' => round($contributionMarginRatio * 100, 1),
            'bep_monthly' => $bepMonthly,
            'revenue_30' => $totalRevenue30,
        ];
    }

    /**
     * Narasi AI atas analisis keuangan — cache harian.
     */
    public function narrative(Business $business, array $analysis): ?array
    {
        $cached = AiInsight::where('business_id', $business->id)
            ->where('type', 'financial')
            ->whereDate('date', Carbon::today())
            ->first();

        if ($cached) {
            return $cached->payload;
        }

        if (! $this->gemini->isConfigured()) {
            return null;
        }

        $context = [
            'omzet_30_hari' => $analysis['revenue_30'],
            'laba_bersih_30_hari' => $analysis['net_profit_30'],
            'margin_kontribusi_pct' => $analysis['contribution_margin_pct'],
            'biaya_tetap_bulanan' => $analysis['monthly_fixed_cost'],
            'bep_omzet_bulanan' => $analysis['bep_monthly'],
            'margin_per_produk' => array_slice($analysis['product_margins'], 0, 8),
        ];

        $payload = $this->gemini->generateJson(
            "Data keuangan UMKM \"{$business->name}\":\n".
            json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).
            "\n\nBerikan analisis keuangan sederhana untuk pemilik yang tidak paham akuntansi.",
            [
                'type' => 'object',
                'properties' => [
                    'summary' => ['type' => 'string', 'description' => 'Kondisi keuangan dalam bahasa awam, 2-3 kalimat'],
                    'strengths' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'risks' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'recommendations' => ['type' => 'array', 'items' => ['type' => 'string']],
                ],
                'required' => ['summary', 'strengths', 'risks', 'recommendations'],
            ],
            'Kamu adalah AI Financial Assistant untuk UMKM Indonesia. Jelaskan keuangan dengan bahasa sederhana tanpa jargon akuntansi, dalam Bahasa Indonesia.',
        );

        if ($payload === null) {
            return null;
        }

        AiInsight::create([
            'business_id' => $business->id,
            'type' => 'financial',
            'date' => Carbon::today(),
            'payload' => $payload,
        ]);

        return $payload;
    }
}
