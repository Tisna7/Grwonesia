@extends('layouts.business')

@section('title', 'Pesanan')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <div class="flex gap-2 flex-wrap">
        @foreach (['' => 'Semua', 'pending' => 'Menunggu', 'paid' => 'Dibayar', 'shipped' => 'Dikirim', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $label)
            <a href="{{ route('business.orders.index', $val ? ['status' => $val] : []) }}"
               class="badge-pill {{ request('status', '') === $val ? 'bg-primary-600/30 text-white border-primary-400/50' : 'bg-primary-950/40 text-slate-400 border-primary-400/15 hover:text-white' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
    <a href="{{ route('business.orders.create') }}" class="btn-primary shrink-0">+ Pesanan Baru</a>
</div>

@if ($orders->isEmpty())
    <x-empty-state icon="🧾" title="Belum ada pesanan" description="Catat pesanan pertamamu — dari WhatsApp, marketplace, atau penjualan langsung.">
        <a href="{{ route('business.orders.create') }}" class="btn-primary">+ Pesanan Baru</a>
    </x-empty-state>
@else
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-primary-400/10 text-left text-xs text-slate-400">
                        <th class="px-5 py-3 font-semibold">No. Pesanan</th>
                        <th class="px-5 py-3 font-semibold">Pelanggan</th>
                        <th class="px-5 py-3 font-semibold">Channel</th>
                        <th class="px-5 py-3 font-semibold">Total</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-400/5">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-primary-600/5 transition-colors">
                            <td class="px-5 py-3">
                                <a href="{{ route('business.orders.show', $order) }}" class="font-mono text-xs text-primary-300 hover:text-primary-200">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-5 py-3 text-slate-300">{{ $order->customer?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-xs text-slate-400">{{ $order->channel->label() }}</td>
                            <td class="px-5 py-3 font-semibold text-white">{{ rupiah($order->total) }}</td>
                            <td class="px-5 py-3">
                                <span class="badge-pill {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
                            </td>
                            <td class="px-5 py-3 text-xs text-slate-400">{{ $order->ordered_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
@endif
@endsection
