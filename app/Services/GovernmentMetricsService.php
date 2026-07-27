<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Business;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GovernmentMetricsService
{
    /**
     * Agregat ekonomi regional dari seluruh bisnis terdaftar.
     */
    public function regionalStats(): array
    {
        $revenueStatuses = OrderStatus::revenueStatuses();
        $now = Carbon::now();
        $last30Start = $now->copy()->subDays(30);
        $prev30Start = $now->copy()->subDays(60);

        $activeBusinesses = Business::whereHas('orders', fn ($q) => $q->where('ordered_at', '>=', $last30Start))->count();
        $totalBusinesses = Business::count();
        $verifiedBusinesses = Business::where('verification_status', 'verified')->count();

        $omzet = fn (Carbon $from, Carbon $to) => (float) DB::table('orders')
            ->whereIn('status', $revenueStatuses)
            ->whereBetween('ordered_at', [$from, $to])
            ->sum('total');

        $omzetLast30 = $omzet($last30Start, $now);
        $omzetPrev30 = $omzet($prev30Start, $last30Start);

        $transactions30 = DB::table('orders')
            ->whereBetween('ordered_at', [$last30Start, $now])
            ->count();

        return [
            'total_businesses' => $totalBusinesses,
            'active_businesses' => $activeBusinesses,
            'verified_businesses' => $verifiedBusinesses,
            'omzet_30' => $omzetLast30,
            'omzet_30_delta' => $omzetPrev30 > 0 ? round(($omzetLast30 - $omzetPrev30) / $omzetPrev30 * 100, 1) : null,
            'transactions_30' => $transactions30,
            'top_products' => $this->topRegionalProducts(),
            'city_hotspots' => $this->cityHotspots(),
            'category_trends' => $this->categoryTrends(),
        ];
    }

    /**
     * Hotspot aktivitas ekonomi per kota (basis kota bisnis).
     */
    public function cityHotspots(): array
    {
        $revenueStatuses = OrderStatus::revenueStatuses();
        $last30Start = Carbon::now()->subDays(30);

        $rows = DB::table('orders')
            ->join('businesses', 'businesses.id', '=', 'orders.business_id')
            ->whereIn('orders.status', $revenueStatuses)
            ->where('orders.ordered_at', '>=', $last30Start)
            ->groupBy('businesses.city')
            ->select(
                'businesses.city',
                DB::raw('SUM(orders.total) as omzet'),
                DB::raw('COUNT(orders.id) as transactions'),
                DB::raw('COUNT(DISTINCT businesses.id) as businesses'),
            )
            ->orderByDesc('omzet')
            ->get();

        $max = (float) ($rows->first()->omzet ?? 0);

        return $rows->map(fn ($row) => [
            'city' => $row->city ?? 'Tidak diketahui',
            'omzet' => (float) $row->omzet,
            'transactions' => (int) $row->transactions,
            'businesses' => (int) $row->businesses,
            'intensity' => $max > 0 ? round((float) $row->omzet / $max * 100) : 0,
        ])->all();
    }

    /**
     * Tren omzet per kategori bisnis: 30 hari terakhir vs 30 hari sebelumnya.
     */
    public function categoryTrends(): array
    {
        $revenueStatuses = OrderStatus::revenueStatuses();
        $now = Carbon::now();
        $last30Start = $now->copy()->subDays(30);
        $prev30Start = $now->copy()->subDays(60);

        $byCategory = fn (Carbon $from, Carbon $to) => DB::table('orders')
            ->join('businesses', 'businesses.id', '=', 'orders.business_id')
            ->whereIn('orders.status', $revenueStatuses)
            ->whereBetween('orders.ordered_at', [$from, $to])
            ->groupBy('businesses.category')
            ->select('businesses.category', DB::raw('SUM(orders.total) as omzet'))
            ->pluck('omzet', 'category');

        $current = $byCategory($last30Start, $now);
        $previous = $byCategory($prev30Start, $last30Start);

        return collect($current->keys()->merge($previous->keys())->unique())
            ->map(function ($category) use ($current, $previous) {
                $cur = (float) ($current[$category] ?? 0);
                $prev = (float) ($previous[$category] ?? 0);

                return [
                    'category' => $category,
                    'omzet' => $cur,
                    'change' => $prev > 0 ? round(($cur - $prev) / $prev * 100, 1) : ($cur > 0 ? 100.0 : 0.0),
                ];
            })
            ->sortByDesc('omzet')
            ->values()
            ->all();
    }

    /**
     * Produk terlaris regional (30 hari).
     */
    public function topRegionalProducts(int $limit = 8): array
    {
        $revenueStatuses = OrderStatus::revenueStatuses();
        $last30Start = Carbon::now()->subDays(30);

        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('businesses', 'businesses.id', '=', 'orders.business_id')
            ->whereIn('orders.status', $revenueStatuses)
            ->where('orders.ordered_at', '>=', $last30Start)
            ->groupBy('order_items.product_name', 'businesses.name', 'businesses.city')
            ->select(
                'order_items.product_name',
                'businesses.name as business_name',
                'businesses.city',
                DB::raw('SUM(order_items.quantity) as qty'),
                DB::raw('SUM(order_items.subtotal) as omzet'),
            )
            ->orderByDesc('qty')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'product' => $row->product_name,
                'business' => $row->business_name,
                'city' => $row->city,
                'qty' => (int) $row->qty,
                'omzet' => (float) $row->omzet,
            ])
            ->all();
    }

    /**
     * Deret omzet regional harian untuk grafik.
     */
    public function dailyRegionalRevenue(int $days = 30): array
    {
        $revenueStatuses = OrderStatus::revenueStatuses();
        $start = Carbon::today()->subDays($days - 1);

        $rows = DB::table('orders')
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

    /**
     * Estimasi serapan tenaga kerja: proksi sederhana dari jumlah bisnis aktif.
     */
    public function estimatedWorkforce(): int
    {
        // Asumsi rata-rata 4 pekerja per UMKM aktif (proksi — belum ada data pekerja riil)
        return Business::count() * 4;
    }
}
