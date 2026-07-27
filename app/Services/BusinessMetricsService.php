<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Business;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BusinessMetricsService
{
    /**
     * Statistik utama dashboard: omzet, order, pelanggan, produk naik/turun.
     */
    public function dashboardStats(Business $business): array
    {
        $revenueStatuses = OrderStatus::revenueStatuses();
        $now = Carbon::now();

        $baseQuery = fn () => $business->orders()->whereIn('status', $revenueStatuses);

        $omzetToday = (float) $baseQuery()->whereDate('ordered_at', $now->toDateString())->sum('total');
        $omzetMonth = (float) $baseQuery()
            ->whereBetween('ordered_at', [$now->copy()->startOfMonth(), $now])
            ->sum('total');
        $omzetPrevMonth = (float) $baseQuery()
            ->whereBetween('ordered_at', [
                $now->copy()->subMonthNoOverflow()->startOfMonth(),
                $now->copy()->subMonthNoOverflow()->endOfMonth(),
            ])
            ->sum('total');

        $ordersMonth = $business->orders()
            ->whereBetween('ordered_at', [$now->copy()->startOfMonth(), $now])
            ->count();

        $customersMonth = $business->orders()
            ->whereBetween('ordered_at', [$now->copy()->startOfMonth(), $now])
            ->whereNotNull('customer_id')
            ->distinct('customer_id')
            ->count('customer_id');

        $pendingOrders = $business->orders()->where('status', OrderStatus::Pending->value)->count();

        return [
            'omzet_today' => $omzetToday,
            'omzet_month' => $omzetMonth,
            'omzet_month_delta' => $omzetPrevMonth > 0
                ? round(($omzetMonth - $omzetPrevMonth) / $omzetPrevMonth * 100, 1)
                : null,
            'orders_month' => $ordersMonth,
            'customers_month' => $customersMonth,
            'pending_orders' => $pendingOrders,
            'top_products' => $this->productMovers($business, 'top'),
            'declining_products' => $this->productMovers($business, 'declining'),
        ];
    }

    /**
     * Produk terlaris / menurun: bandingkan qty 7 hari terakhir vs 7 hari sebelumnya.
     */
    public function productMovers(Business $business, string $mode = 'top'): array
    {
        $revenueStatuses = OrderStatus::revenueStatuses();
        $now = Carbon::now();

        $sales = fn (Carbon $from, Carbon $to) => DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.business_id', $business->id)
            ->whereIn('orders.status', $revenueStatuses)
            ->whereBetween('orders.ordered_at', [$from, $to])
            ->whereNotNull('order_items.product_id')
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->select('order_items.product_id', 'order_items.product_name', DB::raw('SUM(order_items.quantity) as qty'))
            ->pluck('qty', 'product_id');

        $current = $sales($now->copy()->subDays(7), $now);
        $previous = $sales($now->copy()->subDays(14), $now->copy()->subDays(7));

        $names = DB::table('products')->where('business_id', $business->id)->pluck('name', 'id');

        $movers = collect($current->keys()->merge($previous->keys())->unique())
            ->map(function ($productId) use ($current, $previous, $names) {
                $cur = (int) ($current[$productId] ?? 0);
                $prev = (int) ($previous[$productId] ?? 0);

                return [
                    'product_id' => $productId,
                    'name' => $names[$productId] ?? 'Produk terhapus',
                    'qty_current' => $cur,
                    'qty_previous' => $prev,
                    'change' => $prev > 0 ? round(($cur - $prev) / $prev * 100, 1) : ($cur > 0 ? 100.0 : 0.0),
                ];
            });

        return ($mode === 'top'
            ? $movers->sortByDesc('qty_current')
            : $movers->filter(fn ($m) => $m['change'] < 0)->sortBy('change'))
            ->take(5)
            ->values()
            ->all();
    }

    /**
     * Business Health Score 0-100 dengan rincian komponen.
     */
    public function healthScore(Business $business): array
    {
        $revenueStatuses = OrderStatus::revenueStatuses();
        $now = Carbon::now();
        $last30Start = $now->copy()->subDays(30);
        $prev30Start = $now->copy()->subDays(60);

        $omzetLast30 = (float) $business->orders()->whereIn('status', $revenueStatuses)
            ->whereBetween('ordered_at', [$last30Start, $now])->sum('total');
        $omzetPrev30 = (float) $business->orders()->whereIn('status', $revenueStatuses)
            ->whereBetween('ordered_at', [$prev30Start, $last30Start])->sum('total');

        // 1. Pertumbuhan omzet (30 pt): -50% → 0, 0% → 15, +50% → 30
        $growthRatio = $omzetPrev30 > 0 ? ($omzetLast30 - $omzetPrev30) / $omzetPrev30 : ($omzetLast30 > 0 ? 0.5 : 0);
        $revenueScore = round(max(0, min(30, 15 + $growthRatio * 30)));

        // 2. Konsistensi penjualan (20 pt): hari dengan order / 30
        $activeDays = $business->orders()
            ->whereBetween('ordered_at', [$last30Start, $now])
            ->selectRaw('COUNT(DISTINCT DATE(ordered_at)) as days')
            ->value('days') ?? 0;
        $consistencyScore = round(min(1, $activeDays / 30) * 20);

        // 3. Kesehatan stok (20 pt): % produk aktif dengan stok > min_stock
        $activeProducts = $business->products()->active()->count();
        $healthyStock = $business->products()->active()->whereColumn('stock', '>', 'min_stock')->count();
        $stockScore = $activeProducts > 0 ? round($healthyStock / $activeProducts * 20) : 0;

        // 4. Pertumbuhan pelanggan (15 pt)
        $custLast30 = $business->orders()->whereBetween('ordered_at', [$last30Start, $now])
            ->whereNotNull('customer_id')->distinct('customer_id')->count('customer_id');
        $custPrev30 = $business->orders()->whereBetween('ordered_at', [$prev30Start, $last30Start])
            ->whereNotNull('customer_id')->distinct('customer_id')->count('customer_id');
        $custRatio = $custPrev30 > 0 ? ($custLast30 - $custPrev30) / $custPrev30 : ($custLast30 > 0 ? 0.5 : 0);
        $customerScore = round(max(0, min(15, 7.5 + $custRatio * 15)));

        // 5. Kesehatan margin (15 pt): margin rata-rata tertimbang; >= 40% penuh
        $margins = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.business_id', $business->id)
            ->whereIn('orders.status', $revenueStatuses)
            ->whereBetween('orders.ordered_at', [$last30Start, $now])
            ->selectRaw('SUM(order_items.subtotal) as revenue, SUM(order_items.unit_cost * order_items.quantity) as cost')
            ->first();
        $marginPct = ($margins && (float) $margins->revenue > 0)
            ? ((float) $margins->revenue - (float) $margins->cost) / (float) $margins->revenue
            : 0;
        $marginScore = round(min(1, $marginPct / 0.4) * 15);

        $score = (int) min(100, $revenueScore + $consistencyScore + $stockScore + $customerScore + $marginScore);

        return [
            'score' => $score,
            'label' => match (true) {
                $score >= 85 => 'Sangat Baik',
                $score >= 70 => 'Baik',
                $score >= 40 => 'Cukup Baik',
                default => 'Perlu Perhatian',
            },
            'components' => [
                'Pertumbuhan Omzet' => ['score' => $revenueScore, 'max' => 30],
                'Konsistensi Penjualan' => ['score' => $consistencyScore, 'max' => 20],
                'Kesehatan Stok' => ['score' => $stockScore, 'max' => 20],
                'Pertumbuhan Pelanggan' => ['score' => $customerScore, 'max' => 15],
                'Kesehatan Margin' => ['score' => $marginScore, 'max' => 15],
            ],
            'omzet_last_30' => $omzetLast30,
            'omzet_prev_30' => $omzetPrev30,
            'margin_pct' => round($marginPct * 100, 1),
        ];
    }

    /**
     * Deret omzet harian untuk Chart.js.
     */
    public function dailyRevenueSeries(Business $business, int $days = 30): array
    {
        $revenueStatuses = OrderStatus::revenueStatuses();
        $start = Carbon::today()->subDays($days - 1);

        $rows = $business->orders()
            ->whereIn('status', $revenueStatuses)
            ->where('ordered_at', '>=', $start)
            ->selectRaw('DATE(ordered_at) as date, SUM(total) as revenue')
            ->groupBy('date')
            ->pluck('revenue', 'date');

        $labels = [];
        $values = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $labels[] = $date->format('d M');
            $values[] = (float) ($rows[$date->toDateString()] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
