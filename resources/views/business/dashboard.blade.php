@extends('layouts.business')

@section('title', 'Dashboard')

@section('content')
<div class="grid gap-6">
    {{-- Baris stat --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <x-stat-card label="Omzet Hari Ini" :value="rupiah($stats['omzet_today'])" icon="wallet"/>
        <x-stat-card label="Omzet Bulan Ini" :value="rupiah($stats['omzet_month'])" :delta="$stats['omzet_month_delta']" icon="trending-up"/>
        <x-stat-card label="Pesanan Bulan Ini" :value="number_format($stats['orders_month'], 0, ',', '.')" icon="file-text"/>
        <x-stat-card label="Pelanggan Unik" :value="number_format($stats['customers_month'], 0, ',', '.')" icon="users"/>
    </div>

    {{-- AI Insight --}}
    @if ($insight)
        <x-ai-insight-banner title="🤖 AI Insight Hari Ini">
            <p class="font-semibold text-white">{{ $insight['headline'] ?? '' }}</p>
            <ul class="mt-2 space-y-1 text-xs">
                @foreach ($insight['insights'] ?? [] as $item)
                    <li>• {{ $item }}</li>
                @endforeach
            </ul>
            @if (!empty($insight['recommendations']))
                <p class="mt-3 text-xs font-bold text-primary-300">Rekomendasi:</p>
                <ul class="mt-1 space-y-1 text-xs">
                    @foreach ($insight['recommendations'] as $rec)
                        <li>💡 {{ $rec }}</li>
                    @endforeach
                </ul>
            @endif
        </x-ai-insight-banner>
    @elseif (! $aiConfigured)
        <x-ai-unavailable/>
    @endif

    {{-- Program pemerintah yang relevan (feedback loop Gov → Business) --}}
    @if ($govPrograms->isNotEmpty())
        <div class="glass-card p-6 border-indigo-500/25">
            <div class="flex items-center gap-2">
                <div class="icon-tile !w-9 !h-9" style="background: linear-gradient(145deg, rgba(79,70,229,0.28), rgba(79,70,229,0.06)); border-color: rgba(129,140,248,0.3); color: #A5B4FC;">
                    <x-icon name="clipboard-list" class="!w-4 !h-4"/>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">Program Pemerintah untuk Usahamu</h3>
                    <p class="text-[11px] text-slate-500">Direkomendasikan berdasarkan kota & sektor bisnismu</p>
                </div>
            </div>
            <div class="mt-4 grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($govPrograms as $program)
                    <div class="glass-card !bg-primary-950/30 p-4 flex flex-col">
                        <p class="text-xs font-bold text-white">{{ $program->title }}</p>
                        <p class="mt-1 text-[11px] text-slate-400">
                            {{ $program->typeLabel() }}
                            {{ $program->city ? ' · '.$program->city : '' }}
                            @if ($program->starts_at)
                                <span class="block mt-0.5">{{ $program->starts_at->format('d M Y') }}{{ $program->ends_at ? ' – '.$program->ends_at->format('d M Y') : '' }}</span>
                            @endif
                        </p>
                        <div class="mt-3 pt-2 flex-1 flex items-end">
                            @if ($registeredProgramIds->contains($program->id))
                                <span class="badge-pill bg-emerald-500/15 text-emerald-400 border-emerald-500/40 w-full justify-center">
                                    <x-icon name="badge-check" class="w-3.5 h-3.5"/> Terdaftar
                                </span>
                            @else
                                <form method="POST" action="{{ route('business.programs.register', $program) }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="btn-secondary w-full !py-1.5 text-xs">Daftar Program</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Grafik omzet --}}
        <div class="glass-card p-6 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white">Omzet 30 Hari Terakhir</h3>
                @if ($stats['pending_orders'] > 0)
                    <a href="{{ route('business.orders.index') }}" class="badge-pill bg-amber-500/15 text-amber-400 border-amber-500/40">
                        {{ $stats['pending_orders'] }} pesanan menunggu
                    </a>
                @endif
            </div>
            <div class="mt-4 h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Health Score --}}
        <div class="glass-card p-6 flex flex-col items-center">
            <h3 class="text-sm font-bold text-white self-start">Business Health Score</h3>
            <div class="mt-4">
                <x-health-gauge :score="$health['score']" :label="$health['label']"/>
            </div>
            <div class="mt-5 w-full space-y-2">
                @foreach ($health['components'] as $name => $comp)
                    <div>
                        <div class="flex justify-between text-[11px] text-slate-400">
                            <span>{{ $name }}</span>
                            <span class="font-mono">{{ $comp['score'] }}/{{ $comp['max'] }}</span>
                        </div>
                        <div class="mt-0.5 h-1.5 rounded-full bg-primary-950">
                            <div class="h-1.5 rounded-full bg-gradient-to-r from-primary-600 to-primary-400"
                                 style="width: {{ $comp['max'] > 0 ? round($comp['score'] / $comp['max'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Produk terlaris --}}
        <div class="glass-card p-6">
            <h3 class="text-sm font-bold text-white">Produk Terlaris (7 Hari)</h3>
            <div class="mt-4 space-y-3">
                @forelse ($stats['top_products'] as $p)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-300 truncate">{{ $p['name'] }}</span>
                        <span class="shrink-0 ml-3 font-mono text-xs text-emerald-400">{{ $p['qty_current'] }} terjual</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500">Belum ada penjualan minggu ini.</p>
                @endforelse
            </div>
        </div>

        {{-- Produk menurun --}}
        <div class="glass-card p-6">
            <h3 class="text-sm font-bold text-white">Produk Tren Menurun</h3>
            <div class="mt-4 space-y-3">
                @forelse ($stats['declining_products'] as $p)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-300 truncate">{{ $p['name'] }}</span>
                        <span class="shrink-0 ml-3 font-mono text-xs text-rose-400">▼ {{ abs($p['change']) }}%</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500">Tidak ada produk dengan tren menurun. Mantap! 🎉</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('revenueChart');
    const data = @json($chart);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Omzet',
                data: data.values,
                borderColor: '#8B5CF6',
                backgroundColor: (context) => {
                    const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 260);
                    gradient.addColorStop(0, 'rgba(124, 58, 237, 0.35)');
                    gradient.addColorStop(1, 'rgba(124, 58, 237, 0)');
                    return gradient;
                },
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 5,
                borderWidth: 2,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (item) => 'Rp' + Number(item.raw).toLocaleString('id-ID'),
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748B', maxTicksLimit: 8, font: { size: 10 } },
                },
                y: {
                    grid: { color: 'rgba(124, 58, 237, 0.08)' },
                    ticks: {
                        color: '#64748B',
                        font: { size: 10 },
                        callback: (v) => 'Rp' + (v >= 1000000 ? (v / 1000000) + 'jt' : (v / 1000) + 'rb'),
                    },
                },
            },
        },
    });
});
</script>
@endpush
