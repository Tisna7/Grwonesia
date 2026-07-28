@extends('layouts.government')

@section('title', 'Dashboard Ekonomi Regional')

@section('content')
  <div class="grid gap-6">
    {{-- Metrik agregat --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
      <x-stat-card label="UMKM Terdaftar" :value="number_format($stats['total_businesses'], 0, ',', '.')" icon="store" />
      <x-stat-card label="UMKM Aktif (30 Hari)" :value="number_format($stats['active_businesses'], 0, ',', '.')"
        icon="zap" />
      <x-stat-card label="Omzet Regional 30 Hari" :value="rupiah($stats['omzet_30'])" :delta="$stats['omzet_30_delta']"
        icon="wallet" />
      <x-stat-card label="Volume Transaksi 30 Hari" :value="number_format($stats['transactions_30'], 0, ',', '.')"
        icon="file-text" />
    </div>

    {{-- AI Economic Insight --}}
    @if ($insight)
      <x-ai-insight-banner title="🤖 AI Economic Insight">
        <p class="font-semibold text-white">{{ $insight['headline'] ?? '' }}</p>
        @if (!empty($insight['trends']))
          <ul class="mt-2 space-y-1 text-xs">
            @foreach ($insight['trends'] as $trend)
              <li>📊 {{ $trend }}</li>
            @endforeach
          </ul>
        @endif
        @if (!empty($insight['policy_recommendations']))
          <p class="mt-3 text-xs font-bold text-primary-300">Rekomendasi Kebijakan:</p>
          <ul class="mt-1 space-y-1 text-xs">
            @foreach ($insight['policy_recommendations'] as $rec)
              <li>🎯 {{ $rec }}</li>
            @endforeach
          </ul>
        @endif
      </x-ai-insight-banner>
    @elseif (!$aiConfigured)
      <x-ai-unavailable />
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      {{-- Grafik omzet regional --}}
      <div class="glass-card p-6 lg:col-span-2">
        <h3 class="text-sm font-bold text-white">Omzet Regional 30 Hari Terakhir</h3>
        <div class="mt-4 h-64"><canvas id="regionalChart"></canvas></div>
      </div>

      {{-- Estimasi tenaga kerja --}}
      <div class="glass-card p-6 flex flex-col">
        <h3 class="text-sm font-bold text-white">Estimasi Serapan Tenaga Kerja</h3>
        <div class="flex-1 flex flex-col items-center justify-center">
          <p class="text-4xl font-extrabold ai-gradient-text">{{ number_format($workforce, 0, ',', '.') }}</p>
          <p class="mt-1 text-xs text-slate-400">pekerja lokal terlibat</p>
          <p class="mt-3 text-[11px] text-slate-500 text-center">Proksi: rata-rata 4 pekerja per UMKM terdaftar</p>
        </div>
        <div class="impact-badge w-full justify-center">
          <x-icon name="badge-check" class="w-4 h-4" />
          {{ $stats['verified_businesses'] }} dari {{ $stats['total_businesses'] }} UMKM terverifikasi
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      {{-- Hotspot kota --}}
      <div class="glass-card p-6">
        <h3 class="text-sm font-bold text-white">Hotspot Aktivitas Ekonomi (30 Hari)</h3>
        <div class="mt-4 space-y-3">
          @forelse ($stats['city_hotspots'] as $spot)
            <div>
              <div class="flex justify-between text-xs">
                <span class="text-slate-200 font-semibold">{{ $spot['city'] }}</span>
                <span class="font-mono text-slate-400">{{ rupiah($spot['omzet']) }} · {{ $spot['transactions'] }} trx ·
                  {{ $spot['businesses'] }} UMKM</span>
              </div>
              <div class="mt-1.5 h-2.5 rounded-full bg-primary-950/80">
                <div class="h-2.5 rounded-full bg-gradient-to-r from-primary-300 via-primary-500 to-primary-800"
                  style="width: {{ max(4, $spot['intensity']) }}%"></div>
              </div>
            </div>
          @empty
            <p class="text-xs text-slate-500">Belum ada data transaksi regional.</p>
          @endforelse
        </div>
      </div>

      {{-- Tren kategori --}}
      <div class="glass-card p-6">
        <h3 class="text-sm font-bold text-white">Tren Sektor (30 Hari vs Sebelumnya)</h3>
        <div class="mt-4 space-y-3">
          @forelse ($stats['category_trends'] as $trend)
            <div class="flex items-center justify-between text-sm">
              <span class="text-slate-300">{{ $trend['category'] }}</span>
              <div class="flex items-center gap-3">
                <span class="font-mono text-xs text-slate-400">{{ rupiah($trend['omzet']) }}</span>
                <span
                  class="badge-pill {{ $trend['change'] >= 0 ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/40' : 'bg-rose-500/15 text-rose-400 border-rose-500/40' }}">
                  {{ $trend['change'] >= 0 ? '▲' : '▼' }} {{ abs($trend['change']) }}%
                </span>
              </div>
            </div>
          @empty
            <p class="text-xs text-slate-500">Belum ada data.</p>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Produk terlaris regional --}}
    <div class="glass-card overflow-hidden">
      <div class="px-5 py-4 border-b border-primary-400/10">
        <h3 class="text-sm font-bold text-white">Produk Terlaris Regional (30 Hari)</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left text-xs text-slate-400 border-b border-primary-400/10">
              <th class="px-5 py-3 font-semibold">Produk</th>
              <th class="px-5 py-3 font-semibold">UMKM</th>
              <th class="px-5 py-3 font-semibold">Kota</th>
              <th class="px-5 py-3 font-semibold text-center">Terjual</th>
              <th class="px-5 py-3 font-semibold text-right">Omzet</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary-400/5">
            @foreach ($stats['top_products'] as $product)
              <tr class="hover:bg-primary-600/5">
                <td class="px-5 py-3 text-slate-200 font-medium">{{ $product['product'] }}</td>
                <td class="px-5 py-3 text-xs text-slate-400">{{ $product['business'] }}</td>
                <td class="px-5 py-3 text-xs text-slate-400">{{ $product['city'] }}</td>
                <td class="px-5 py-3 text-center font-mono text-slate-300">{{ $product['qty'] }}</td>
                <td class="px-5 py-3 text-right font-mono text-primary-300">{{ rupiah($product['omzet']) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  @vite(['resources/js/charts.js'])
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const data = @json($chart);

      new Chart(document.getElementById('regionalChart'), {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Omzet Regional',
            data: data.values,
            backgroundColor: 'rgba(139, 92, 246, 0.55)',
            borderColor: '#8B5CF6',
            borderWidth: 1,
            borderRadius: 4,
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
            x: { grid: { display: false }, ticks: { color: '#64748B', maxTicksLimit: 10, font: { size: 10 } } },
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