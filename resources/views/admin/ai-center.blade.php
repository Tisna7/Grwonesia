@extends('layouts.admin')

@section('title', 'AI Management & Integration Center')

@section('content')
<div class="grid gap-6">
    {{-- Ringkasan AI --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-card p-5">
            <p class="text-xs text-slate-400">Requests (7 Hari)</p>
            <p class="mt-1 text-2xl font-extrabold text-white">{{ number_format($ai['total'], 0, ',', '.') }}</p>
        </div>
        <div class="glass-card p-5">
            <p class="text-xs text-slate-400">Success Rate</p>
            <p class="mt-1 text-2xl font-extrabold {{ ($ai['success_rate'] ?? 100) >= 95 ? 'text-emerald-400' : 'text-amber-400' }}">
                {{ $ai['success_rate'] ?? '—' }}%
            </p>
        </div>
        <div class="glass-card p-5">
            <p class="text-xs text-slate-400">Avg Latency</p>
            <p class="mt-1 text-2xl font-extrabold text-white">{{ $ai['avg_latency_ms'] ?? '—' }}<span class="text-sm text-slate-400"> ms</span></p>
        </div>
        <div class="glass-card p-5">
            <p class="text-xs text-slate-400">Total Tokens (7 Hari)</p>
            <p class="mt-1 text-2xl font-extrabold ai-gradient-text">{{ number_format($ai['total_tokens'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Breakdown per jenis --}}
        <div class="glass-card overflow-hidden">
            <div class="px-5 py-4 border-b border-primary-400/10">
                <h3 class="text-sm font-bold text-white">Penggunaan per Jenis Request</h3>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-slate-400 border-b border-primary-400/10">
                        <th class="px-5 py-3 font-semibold">Jenis</th>
                        <th class="px-5 py-3 font-semibold text-center">Total</th>
                        <th class="px-5 py-3 font-semibold text-center">Avg Latency</th>
                        <th class="px-5 py-3 font-semibold text-right">Tokens</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-400/5">
                    @forelse ($ai['by_kind'] as $row)
                        <tr>
                            <td class="px-5 py-3">
                                <span class="badge-pill bg-primary-600/15 text-primary-300 border-primary-500/30">
                                    {{ ['text' => '📝 Text', 'json' => '🧩 Structured', 'chat' => '💬 Chat', 'vision' => '🖼️ Vision'][$row['kind']] ?? $row['kind'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center font-mono text-slate-300">{{ $row['total'] }}</td>
                            <td class="px-5 py-3 text-center font-mono text-slate-400">{{ $row['avg_ms'] }} ms</td>
                            <td class="px-5 py-3 text-right font-mono text-slate-300">{{ number_format($row['tokens'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-xs text-slate-500">Belum ada request AI tercatat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Error terakhir --}}
        <div class="glass-card p-6">
            <h3 class="text-sm font-bold text-white">Error Terakhir</h3>
            <div class="mt-3 space-y-2">
                @forelse ($ai['recent_errors'] as $err)
                    <div class="glass-card !bg-rose-500/5 border-rose-500/20 px-4 py-2.5 flex items-center justify-between text-xs">
                        <span class="text-rose-400 font-mono">{{ $err->error ?? 'Unknown' }}</span>
                        <span class="text-slate-500">{{ $err->kind }} · {{ $err->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-xs text-emerald-400">✓ Tidak ada error tercatat. AI engine sehat.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Integration Center --}}
    <div class="glass-card overflow-hidden">
        <div class="px-5 py-4 border-b border-primary-400/10">
            <h3 class="text-sm font-bold text-white">🔌 Integration Center</h3>
        </div>
        <div class="divide-y divide-primary-400/5">
            @foreach ($integrations as $integration)
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-white">{{ $integration['name'] }}</p>
                        <p class="text-[11px] text-slate-500 font-mono">{{ $integration['detail'] }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[11px] text-slate-400 hidden sm:block">{{ $integration['note'] }}</span>
                        @if ($integration['connected'])
                            <span class="badge-pill bg-emerald-500/15 text-emerald-400 border-emerald-500/40">● Terhubung</span>
                        @else
                            <span class="badge-pill bg-slate-500/15 text-slate-400 border-slate-500/40">○ Belum Aktif</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
