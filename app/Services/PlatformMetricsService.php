<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\AiRequest;
use App\Models\Business;
use App\Models\GovProgram;
use App\Models\Order;
use App\Models\User;
use App\Models\WaMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PlatformMetricsService
{
    /**
     * Metrik master platform (F-ADM-01).
     */
    public function platformStats(): array
    {
        $now = Carbon::now();
        $last30Start = $now->copy()->subDays(30);

        $usersByRole = User::selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        return [
            'total_users' => User::count(),
            'users_by_role' => $usersByRole,
            'total_businesses' => Business::count(),
            'pending_verifications' => Business::where('verification_status', 'pending')->count(),
            'gov_programs' => GovProgram::count(),
            'orders_30' => Order::where('ordered_at', '>=', $last30Start)->count(),
            'gmv_30' => (float) Order::whereIn('status', OrderStatus::revenueStatuses())
                ->where('ordered_at', '>=', $last30Start)
                ->sum('total'),
            'wa_messages_30' => WaMessage::where('created_at', '>=', $last30Start)->count(),
            'ai_requests_24h' => AiRequest::where('created_at', '>=', $now->copy()->subDay())->count(),
        ];
    }

    /**
     * Statistik penggunaan AI (F-ADM-03).
     */
    public function aiStats(int $days = 7): array
    {
        $since = Carbon::now()->subDays($days);
        $requests = AiRequest::where('created_at', '>=', $since);

        $total = (clone $requests)->count();
        $success = (clone $requests)->where('success', true)->count();

        $byKind = AiRequest::where('created_at', '>=', $since)
            ->selectRaw('kind, COUNT(*) as total, AVG(duration_ms) as avg_ms, SUM(COALESCE(prompt_tokens,0) + COALESCE(output_tokens,0)) as tokens')
            ->groupBy('kind')
            ->get()
            ->map(fn ($row) => [
                'kind' => $row->kind,
                'total' => (int) $row->total,
                'avg_ms' => (int) round((float) $row->avg_ms),
                'tokens' => (int) $row->tokens,
            ])
            ->all();

        $daily = AiRequest::where('created_at', '>=', $since)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $values = [];
        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::today()->subDays($days - 1 - $i);
            $labels[] = $date->format('d M');
            $values[] = (int) ($daily[$date->toDateString()] ?? 0);
        }

        return [
            'total' => $total,
            'success_rate' => $total > 0 ? round($success / $total * 100, 1) : null,
            'avg_latency_ms' => $total > 0 ? (int) round((float) AiRequest::where('created_at', '>=', $since)->avg('duration_ms')) : null,
            'total_tokens' => (int) AiRequest::where('created_at', '>=', $since)
                ->selectRaw('SUM(COALESCE(prompt_tokens,0) + COALESCE(output_tokens,0)) as t')
                ->value('t'),
            'by_kind' => $byKind,
            'daily' => ['labels' => $labels, 'values' => $values],
            'recent_errors' => AiRequest::where('success', false)
                ->latest()
                ->take(5)
                ->get(['kind', 'error', 'created_at']),
        ];
    }

    /**
     * Daftar bisnis + indikator risiko sederhana untuk verifikasi (F-ADM-02).
     */
    public function businessesWithRisk()
    {
        return Business::with('user')
            ->withCount('products')
            ->withCount('orders')
            ->orderByRaw("CASE verification_status WHEN 'pending' THEN 0 WHEN 'rejected' THEN 1 ELSE 2 END")
            ->orderByDesc('created_at')
            ->paginate(12)
            ->through(function (Business $business) {
                $totalOrders = $business->orders_count;
                $cancelled = $business->orders()->where('status', 'cancelled')->count();
                $cancelRate = $totalOrders > 0 ? $cancelled / $totalOrders * 100 : 0;

                $business->risk_level = match (true) {
                    $totalOrders === 0 && $business->products_count === 0 => 'tinggi',
                    $cancelRate > 25 => 'tinggi',
                    $cancelRate > 12 => 'sedang',
                    default => 'rendah',
                };
                $business->cancel_rate = round($cancelRate, 1);

                return $business;
            });
    }

    /**
     * Status integrasi pihak ketiga (F-ADM-03).
     */
    public function integrations(): array
    {
        return [
            [
                'name' => 'Gemini AI',
                'detail' => config('services.gemini.model'),
                'connected' => filled(config('services.gemini.key')),
                'note' => filled(config('services.gemini.key')) ? 'API key terpasang' : 'GEMINI_API_KEY belum diisi',
            ],
            [
                'name' => 'WhatsApp Gateway',
                'detail' => 'driver: '.config('services.whatsapp.driver'),
                'connected' => config('services.whatsapp.driver') === 'baileys' && filled(config('services.whatsapp.url')),
                'note' => config('services.whatsapp.driver') === 'baileys' ? 'Terhubung ke gateway Baileys' : 'Mode simulasi (log)',
            ],
            [
                'name' => 'Instagram Graph API',
                'detail' => filled(config('services.instagram.business_id')) ? 'IG Business #'.config('services.instagram.business_id') : 'Content Publishing',
                'connected' => filled(config('services.instagram.business_id')) && filled(config('services.instagram.token')),
                'note' => filled(config('services.instagram.token')) ? 'Token terpasang' : 'IG_BUSINESS_ID / IG_ACCESS_TOKEN belum diisi',
            ],
            [
                'name' => 'Payment Gateway',
                'detail' => 'QRIS / E-Wallet',
                'connected' => false,
                'note' => 'Belum diintegrasikan (roadmap modul Consumer)',
            ],
            [
                'name' => 'Courier API',
                'detail' => 'Multi-kurir',
                'connected' => false,
                'note' => 'Belum diintegrasikan (roadmap modul Consumer)',
            ],
            [
                'name' => 'Database MySQL',
                'detail' => config('database.connections.mysql.database'),
                'connected' => $this->dbHealthy(),
                'note' => $this->dbHealthy() ? 'Koneksi normal' : 'Koneksi bermasalah',
            ],
        ];
    }

    private function dbHealthy(): bool
    {
        try {
            DB::select('SELECT 1');

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
