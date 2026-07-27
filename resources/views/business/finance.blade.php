@extends('layouts.business')

@section('title', 'Keuangan AI')

@section('content')
<div class="grid gap-6">
    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <x-stat-card label="Omzet 30 Hari" :value="rupiah($analysis['revenue_30'])" icon="wallet"/>
        <x-stat-card label="Laba Bersih 30 Hari (est.)" :value="rupiah($analysis['net_profit_30'])" icon="activity"/>
        <x-stat-card label="Margin Kontribusi" :value="$analysis['contribution_margin_pct'].'%'" icon="percent"/>
        <x-stat-card label="BEP Omzet Bulanan" :value="$analysis['bep_monthly'] !== null ? rupiah($analysis['bep_monthly']) : '—'" icon="target"/>
    </div>

    @if ($narrative)
        <x-ai-insight-banner title="🤖 AI Financial Assistant">
            <p>{{ $narrative['summary'] ?? '' }}</p>
            <div class="mt-3 grid sm:grid-cols-3 gap-3 text-xs">
                @if (!empty($narrative['strengths']))
                    <div>
                        <p class="font-bold text-emerald-400">💪 Kekuatan</p>
                        <ul class="mt-1 space-y-1">
                            @foreach ($narrative['strengths'] as $s)<li>• {{ $s }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                @if (!empty($narrative['risks']))
                    <div>
                        <p class="font-bold text-amber-400">⚠️ Risiko</p>
                        <ul class="mt-1 space-y-1">
                            @foreach ($narrative['risks'] as $r)<li>• {{ $r }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                @if (!empty($narrative['recommendations']))
                    <div>
                        <p class="font-bold text-primary-300">💡 Rekomendasi</p>
                        <ul class="mt-1 space-y-1">
                            @foreach ($narrative['recommendations'] as $r)<li>• {{ $r }}</li>@endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </x-ai-insight-banner>
    @elseif (! $aiConfigured)
        <x-ai-unavailable/>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Proyeksi cashflow --}}
        <div class="glass-card p-6 lg:col-span-2">
            <h3 class="text-sm font-bold text-white">Proyeksi Cashflow Kumulatif 30 Hari</h3>
            <p class="text-[11px] text-slate-500">Berdasarkan rata-rata omzet & biaya 30 hari terakhir, dikurangi biaya tetap harian.</p>
            <div class="mt-4 h-64"><canvas id="cashflowChart"></canvas></div>
        </div>

        {{-- Biaya tetap --}}
        <div class="glass-card p-6">
            <h3 class="text-sm font-bold text-white">Biaya Tetap Bulanan</h3>
            <p class="mt-1 text-[11px] text-slate-500">Sewa, gaji, listrik, dll. Dipakai untuk hitung BEP & proyeksi laba.</p>
            <form method="POST" action="{{ route('business.finance.fixed-cost') }}" class="mt-4 space-y-3">
                @csrf
                <input type="number" name="monthly_fixed_cost" value="{{ (int) $business->monthly_fixed_cost }}" min="0" step="50000" class="form-input">
                <button type="submit" class="btn-primary w-full text-xs">Simpan</button>
            </form>
            <div class="mt-5 pt-4 border-t border-primary-400/10 space-y-2 text-xs text-slate-400">
                <div class="flex justify-between"><span>Rata-rata omzet/hari</span><span class="font-mono text-slate-200">{{ rupiah($analysis['avg_daily_revenue']) }}</span></div>
                <div class="flex justify-between"><span>Rata-rata HPP/hari</span><span class="font-mono text-slate-200">{{ rupiah($analysis['avg_daily_cost']) }}</span></div>
                <div class="flex justify-between"><span>Biaya tetap/hari</span><span class="font-mono text-slate-200">{{ rupiah($analysis['monthly_fixed_cost'] / 30) }}</span></div>
            </div>
        </div>
    </div>

    {{-- Margin per produk --}}
    <div class="glass-card overflow-hidden">
        <div class="px-5 py-4 border-b border-primary-400/10">
            <h3 class="text-sm font-bold text-white">Profit Margin per Produk (30 Hari)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-slate-400 border-b border-primary-400/10">
                        <th class="px-5 py-3 font-semibold">Produk</th>
                        <th class="px-5 py-3 font-semibold text-center">Terjual</th>
                        <th class="px-5 py-3 font-semibold text-right">Omzet</th>
                        <th class="px-5 py-3 font-semibold text-right">Laba Kotor</th>
                        <th class="px-5 py-3 font-semibold text-right">Margin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-400/5">
                    @forelse ($analysis['product_margins'] as $row)
                        <tr class="hover:bg-primary-600/5">
                            <td class="px-5 py-3 text-slate-200">{{ $row['name'] }}</td>
                            <td class="px-5 py-3 text-center font-mono text-slate-400">{{ $row['qty'] }}</td>
                            <td class="px-5 py-3 text-right font-mono text-slate-300">{{ rupiah($row['revenue']) }}</td>
                            <td class="px-5 py-3 text-right font-mono text-emerald-400">{{ rupiah($row['profit']) }}</td>
                            <td class="px-5 py-3 text-right">
                                <span class="badge-pill {{ $row['margin_pct'] >= 40 ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/40' : ($row['margin_pct'] >= 25 ? 'bg-amber-500/15 text-amber-400 border-amber-500/40' : 'bg-rose-500/15 text-rose-400 border-rose-500/40') }}">
                                    {{ $row['margin_pct'] }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-xs text-slate-500">Belum ada penjualan dalam 30 hari terakhir.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const data = @json($analysis['projection']);

    new Chart(document.getElementById('cashflowChart'), {
        type: 'line',
        data: {
            labels: data.map(d => d.label),
            datasets: [{
                label: 'Cashflow Kumulatif',
                data: data.map(d => d.value),
                borderColor: '#EC4899',
                backgroundColor: (context) => {
                    const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 260);
                    gradient.addColorStop(0, 'rgba(236, 72, 153, 0.25)');
                    gradient.addColorStop(1, 'rgba(236, 72, 153, 0)');
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
                tooltip: { callbacks: { label: (item) => 'Rp' + Number(item.raw).toLocaleString('id-ID') } },
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#64748B', maxTicksLimit: 8, font: { size: 10 } } },
                y: {
                    grid: { color: 'rgba(124, 58, 237, 0.08)' },
                    ticks: {
                        color: '#64748B',
                        font: { size: 10 },
                        callback: (v) => 'Rp' + (Math.abs(v) >= 1000000 ? (v / 1000000) + 'jt' : (v / 1000) + 'rb'),
                    },
                },
            },
        },
    });
});
</script>
@endpush
