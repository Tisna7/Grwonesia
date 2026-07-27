@extends('layouts.business')

@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-3xl grid gap-6">
    <div class="glass-card p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="font-mono text-sm text-primary-300">{{ $order->order_number }}</p>
                <p class="mt-1 text-xs text-slate-400">{{ $order->ordered_at->format('d F Y, H:i') }} · {{ $order->channel->label() }}</p>
            </div>
            <span class="badge-pill {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
        </div>

        @if ($order->customer)
            <div class="mt-4 pt-4 border-t border-primary-400/10 text-sm">
                <p class="text-white font-semibold">{{ $order->customer->name }}</p>
                <p class="text-xs text-slate-400">
                    {{ $order->customer->phone ?? 'Tanpa nomor' }}
                    {{ $order->customer->city ? ' · '.$order->customer->city : '' }}
                </p>
            </div>
        @endif

        <div class="mt-4 pt-4 border-t border-primary-400/10">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-slate-400">
                        <th class="pb-2 font-semibold">Produk</th>
                        <th class="pb-2 font-semibold text-center">Qty</th>
                        <th class="pb-2 font-semibold text-right">Harga</th>
                        <th class="pb-2 font-semibold text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-400/5">
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="py-2 text-slate-300">{{ $item->product_name }}</td>
                            <td class="py-2 text-center text-slate-400">{{ $item->quantity }}</td>
                            <td class="py-2 text-right text-slate-400">{{ rupiah($item->unit_price) }}</td>
                            <td class="py-2 text-right font-semibold text-white">{{ rupiah($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-primary-400/15">
                        <td colspan="3" class="pt-3 text-right text-slate-400 text-xs font-bold uppercase">Total</td>
                        <td class="pt-3 text-right text-lg font-extrabold text-primary-300">{{ rupiah($order->total) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if ($order->notes)
            <p class="mt-4 text-xs text-slate-400 italic">📝 {{ $order->notes }}</p>
        @endif
    </div>

    <div class="glass-card p-6">
        <h3 class="text-sm font-bold text-white">Ubah Status</h3>
        <form method="POST" action="{{ route('business.orders.status', $order) }}" class="mt-3 flex flex-wrap gap-3">
            @csrf
            @method('PATCH')
            <select name="status" class="form-input max-w-xs">
                @foreach (\App\Enums\OrderStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected($order->status === $status)>{{ $status->label() }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">Perbarui</button>
        </form>
    </div>

    <a href="{{ route('business.orders.index') }}" class="text-xs text-primary-300 hover:text-primary-200">← Kembali ke daftar pesanan</a>
</div>
@endsection
