@extends('layouts.business')

@section('title', 'Produk')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <form method="GET" class="flex-1 max-w-sm">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari produk…" class="form-input">
    </form>
    <a href="{{ route('business.products.create') }}" class="btn-primary">+ Tambah Produk</a>
</div>

@if ($products->isEmpty())
    <x-empty-state icon="📦" title="Belum ada produk" description="Tambahkan produk pertamamu dan biarkan AI membantu menyempurnakan listing-nya.">
        <a href="{{ route('business.products.create') }}" class="btn-primary">+ Tambah Produk</a>
    </x-empty-state>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach ($products as $product)
            <div class="glass-card glass-card-hover p-5 flex gap-4">
                <div class="w-20 h-20 shrink-0 rounded-xl bg-primary-950/60 border border-primary-400/15 flex items-center justify-center overflow-hidden">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <a href="{{ route('business.products.edit', $product) }}" class="text-sm font-bold text-white hover:text-primary-300 truncate">
                            {{ $product->name }}
                        </a>
                        @if ($product->status === 'inactive')
                            <span class="badge-pill bg-slate-500/15 text-slate-400 border-slate-500/40 shrink-0">Nonaktif</span>
                        @endif
                    </div>
                    <p class="mt-0.5 text-xs text-slate-400">{{ $product->category }} · Margin {{ $product->marginPercent() }}%</p>
                    <p class="mt-1 text-sm font-extrabold text-primary-300">{{ rupiah($product->price) }}</p>
                    <p class="mt-1 text-xs {{ $product->isLowStock() ? 'text-rose-400 font-semibold' : 'text-slate-400' }}">
                        Stok: {{ $product->stock }} {{ $product->isLowStock() ? '⚠️ menipis' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
@endif
@endsection
