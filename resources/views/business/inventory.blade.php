@extends('layouts.business')

@section('title', 'Inventori AI')

@section('content')
<div class="grid gap-6">
    @if ($narrative)
        <x-ai-insight-banner title="🤖 AI Inventory Prediction">
            <p>{{ $narrative['summary'] ?? '' }}</p>
            @if (!empty($narrative['urgent_actions']))
                <ul class="mt-2 space-y-1 text-xs">
                    @foreach ($narrative['urgent_actions'] as $action)
                        <li>⚡ {{ $action }}</li>
                    @endforeach
                </ul>
            @endif
        </x-ai-insight-banner>
    @elseif (! $aiConfigured)
        <x-ai-unavailable/>
    @endif

    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-primary-400/10 text-left text-xs text-slate-400">
                        <th class="px-5 py-3 font-semibold">Produk</th>
                        <th class="px-5 py-3 font-semibold text-center">Stok</th>
                        <th class="px-5 py-3 font-semibold text-center">Terjual 30 Hari</th>
                        <th class="px-5 py-3 font-semibold text-center">Kecepatan/Hari</th>
                        <th class="px-5 py-3 font-semibold">Perkiraan Habis</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-400/5">
                    @foreach ($predictions as $row)
                        <tr class="hover:bg-primary-600/5">
                            <td class="px-5 py-3">
                                <a href="{{ route('business.products.edit', $row['product']) }}" class="text-slate-200 hover:text-primary-300 font-medium">
                                    {{ $row['product']->name }}
                                </a>
                            </td>
                            <td class="px-5 py-3 text-center font-mono {{ $row['product']->isLowStock() ? 'text-rose-400 font-bold' : 'text-slate-300' }}">
                                {{ $row['product']->stock }}
                            </td>
                            <td class="px-5 py-3 text-center font-mono text-slate-300">{{ $row['sold_last_30'] }}</td>
                            <td class="px-5 py-3 text-center font-mono text-slate-400">{{ $row['daily_velocity'] }}</td>
                            <td class="px-5 py-3 text-xs text-slate-300">
                                @if ($row['stockout_date'])
                                    {{ $row['stockout_date']->format('d M Y') }}
                                    <span class="text-slate-500">({{ $row['days_left'] }} hari)</span>
                                @else
                                    <span class="text-slate-500">— tidak ada penjualan</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @if ($row['level'] === 'critical')
                                    <span class="badge-pill bg-rose-500/15 text-rose-400 border-rose-500/40">🚨 Restock Segera</span>
                                @elseif ($row['level'] === 'warning')
                                    <span class="badge-pill bg-amber-500/15 text-amber-400 border-amber-500/40">⚠️ Siapkan Restock</span>
                                @else
                                    <span class="badge-pill bg-emerald-500/15 text-emerald-400 border-emerald-500/40">✓ Aman</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
