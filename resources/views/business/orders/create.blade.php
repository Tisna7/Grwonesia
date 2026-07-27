@extends('layouts.business')

@section('title', 'Pesanan Baru')

@section('content')
<div class="max-w-3xl" x-data="orderForm()">
    <div class="glass-card p-6">
        <form method="POST" action="{{ route('business.orders.store') }}">
            @csrf

            <p class="text-xs font-bold uppercase tracking-wider text-primary-300">Pelanggan</p>
            <div class="mt-3 grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Pelanggan Lama</label>
                    <select name="customer_id" class="form-input" x-model="customerId">
                        <option value="">— Pelanggan baru —</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->phone ? '('.$customer->phone.')' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <template x-if="!customerId">
                    <div class="grid gap-4">
                        <x-input label="Nama Pelanggan Baru" name="customer_name" placeholder="Nama pelanggan"/>
                        <x-input label="No. WhatsApp" name="customer_phone" placeholder="628xxxxxxxxxx"/>
                    </div>
                </template>
            </div>
            @error('items')
                <p class="mt-3 text-xs text-rose-400">{{ $message }}</p>
            @enderror

            <p class="mt-6 text-xs font-bold uppercase tracking-wider text-primary-300">Item Pesanan</p>
            <div class="mt-3 space-y-3">
                <template x-for="(item, index) in items" :key="index">
                    <div class="flex gap-3 items-end">
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5" x-show="index === 0">Produk</label>
                            <select :name="`items[${index}][product_id]`" class="form-input" x-model="item.productId" required>
                                <option value="">Pilih produk…</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                                        {{ $product->name }} — {{ rupiah($product->price) }} (stok {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-24">
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5" x-show="index === 0">Qty</label>
                            <input type="number" :name="`items[${index}][quantity]`" x-model.number="item.quantity" min="1" class="form-input" required>
                        </div>
                        <button type="button" class="btn-secondary !px-3 text-rose-400" @click="items.splice(index, 1)" x-show="items.length > 1">✕</button>
                    </div>
                </template>
            </div>
            <button type="button" class="btn-secondary mt-3 text-xs" @click="items.push({ productId: '', quantity: 1 })">+ Tambah Item</button>

            <div class="mt-4 glass-card !bg-primary-950/40 px-4 py-3 flex justify-between text-sm">
                <span class="text-slate-400">Perkiraan total</span>
                <span class="font-extrabold text-white" x-text="'Rp' + estimatedTotal().toLocaleString('id-ID')"></span>
            </div>

            <div class="mt-6 grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Status</label>
                    <select name="status" class="form-input">
                        <option value="paid">Dibayar</option>
                        <option value="pending">Menunggu Pembayaran</option>
                        <option value="shipped">Dikirim</option>
                        <option value="completed">Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Channel</label>
                    <select name="channel" class="form-input">
                        <option value="manual">Manual / Langsung</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="marketplace">Marketplace</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Catatan</label>
                <textarea name="notes" rows="2" class="form-input" placeholder="Catatan opsional…">{{ old('notes') }}</textarea>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="btn-primary">Simpan Pesanan</button>
                <a href="{{ route('business.orders.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function orderForm() {
    const prices = Object.fromEntries([
        @foreach ($products as $product)
            [{{ $product->id }}, {{ $product->price }}],
        @endforeach
    ]);

    return {
        customerId: '',
        items: [{ productId: '', quantity: 1 }],
        estimatedTotal() {
            return this.items.reduce((sum, item) =>
                sum + (prices[item.productId] ?? 0) * (item.quantity || 0), 0);
        },
    };
}
</script>
@endpush
