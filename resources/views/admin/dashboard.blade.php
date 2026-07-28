@extends('layouts.admin')

@section('title', 'Master Platform Dashboard')

@section('content')
  <div class="grid gap-6">
    {{-- Metrik utama --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
      <x-stat-card label="Total Pengguna" :value="number_format($stats['total_users'], 0, ',', '.')" icon="users" />
      <x-stat-card label="Akun Bisnis Aktif" :value="number_format($stats['total_businesses'], 0, ',', '.')"
        icon="store" />
      <x-stat-card label="GMV 30 Hari" :value="rupiah($stats['gmv_30'])" icon="wallet" />
      <x-stat-card label="AI Requests (24 Jam)" :value="number_format($stats['ai_requests_24h'], 0, ',', '.')"
        icon="cpu" />
    </div>

    {{-- Platform AI Insight --}}
    @if ($insight)
      <x-ai-insight-banner title="🤖 Platform AI Insight">
        <p class="font-semibold text-white">{{ $insight['headline'] ?? '' }}</p>
        @if (!empty($insight['observations']))
          <ul class="mt-2 space-y-1 text-xs">
            @foreach ($insight['observations'] as $obs)
              <li>👁️ {{ $obs }}</li>
            @endforeach
          </ul>
        @endif
        @if (!empty($insight['action_items']))
          <p class="mt-3 text-xs font-bold text-primary-300">Action Items:</p>
          <ul class="mt-1 space-y-1 text-xs">
            @foreach ($insight['action_items'] as $item)
              <li>⚡ {{ $item }}</li>
            @endforeach
          </ul>
        @endif
      </x-ai-insight-banner>
    @elseif (!$aiConfigured)
      <x-ai-unavailable />
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      {{-- Komposisi pengguna --}}
      <div class="glass-card p-6">
        <h3 class="text-sm font-bold text-white">Komposisi Pengguna</h3>
        <div class="mt-4 space-y-3">
          @foreach (['consumer' => ['Pembeli', '🛒'], 'business' => ['Bisnis', '🏪'], 'government' => ['Pemerintah', '🏛️'], 'admin' => ['Admin', '⚙️']] as $role => [$label, $icon])
            @php $count = $stats['users_by_role'][$role] ?? 0; @endphp
            <div class="flex items-center justify-between text-sm">
              <span class="text-slate-300">{{ $icon }} {{ $label }}</span>
              <span class="font-mono font-bold text-white">{{ $count }}</span>
            </div>
            <div class="h-1.5 rounded-full bg-primary-950">
              <div class="h-1.5 rounded-full bg-gradient-to-r from-primary-600 to-magenta"
                style="width: {{ $stats['total_users'] > 0 ? round($count / $stats['total_users'] * 100) : 0 }}%; background: linear-gradient(to right, #7C3AED, #EC4899);">
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Aktivitas platform --}}
      <div class="glass-card p-6">
        <h3 class="text-sm font-bold text-white">Aktivitas 30 Hari</h3>
        <div class="mt-4 space-y-4 text-sm">
          <div class="flex justify-between"><span class="text-slate-400">🧾 Order</span><span
              class="font-mono font-bold text-white">{{ number_format($stats['orders_30'], 0, ',', '.') }}</span></div>
          <div class="flex justify-between"><span class="text-slate-400">💬 Pesan WA</span><span
              class="font-mono font-bold text-white">{{ number_format($stats['wa_messages_30'], 0, ',', '.') }}</span>
          </div>
          <div class="flex justify-between"><span class="text-slate-400">📋 Program Pemerintah</span><span
              class="font-mono font-bold text-white">{{ $stats['gov_programs'] }}</span></div>
          <div class="flex justify-between items-center">
            <span class="text-slate-400">✅ Verifikasi Pending</span>
            <a href="{{ route('admin.businesses') }}"
              class="badge-pill {{ $stats['pending_verifications'] > 0 ? 'bg-amber-500/15 text-amber-400 border-amber-500/40' : 'bg-emerald-500/15 text-emerald-400 border-emerald-500/40' }}">
              {{ $stats['pending_verifications'] }} menunggu
            </a>
          </div>
        </div>
      </div>

      {{-- Kesehatan AI --}}
      <div class="glass-card p-6">
        <div class="flex items-center justify-between">
          <h3 class="text-sm font-bold text-white">Kesehatan AI Engine</h3>
          <a href="{{ route('admin.ai-center') }}" class="text-xs text-primary-300 hover:text-primary-200">Detail →</a>
        </div>
        <div class="mt-4 space-y-4 text-sm">
          <div class="flex justify-between"><span class="text-slate-400">Requests (7 hari)</span><span
              class="font-mono font-bold text-white">{{ number_format($ai['total'], 0, ',', '.') }}</span></div>
          <div class="flex justify-between">
            <span class="text-slate-400">Success Rate</span>
            <span
              class="font-mono font-bold {{ ($ai['success_rate'] ?? 100) >= 95 ? 'text-emerald-400' : 'text-amber-400' }}">{{ $ai['success_rate'] ?? '—' }}%</span>
          </div>
          <div class="flex justify-between"><span class="text-slate-400">Avg Latency</span><span
              class="font-mono font-bold text-white">{{ $ai['avg_latency_ms'] ?? '—' }} ms</span></div>
          <div class="flex justify-between"><span class="text-slate-400">Total Tokens</span><span
              class="font-mono font-bold text-white">{{ number_format($ai['total_tokens'], 0, ',', '.') }}</span></div>
        </div>
      </div>
    </div>

    {{-- Grafik AI requests --}}
    <div class="glass-card p-6">
      <h3 class="text-sm font-bold text-white">Volume AI Requests (7 Hari)</h3>
      <div class="mt-4 h-52"><canvas id="aiChart"></canvas></div>
    </div>
  </div>
@endsection

@push('scripts')
  @vite(['resources/js/charts.js'])
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const data = @json($ai['daily']);

      new Chart(document.getElementById('aiChart'), {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'AI Requests',
            data: data.values,
            backgroundColor: 'rgba(236, 72, 153, 0.5)',
            borderColor: '#EC4899',
            borderWidth: 1,
            borderRadius: 4,
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: { grid: { display: false }, ticks: { color: '#64748B', font: { size: 10 } } },
            y: { grid: { color: 'rgba(124, 58, 237, 0.08)' }, ticks: { color: '#64748B', font: { size: 10 }, precision: 0 } },
          },
        },
      });
    });
  </script>
@endpush