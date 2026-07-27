@csrf
<div class="grid sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <x-input label="Nama Produk" name="name" required :value="$product->name ?? null" placeholder="cth: Kopi Gula Aren 250g"/>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kategori <span class="text-magenta">*</span></label>
        <select name="category" required class="form-input">
            <option value="">Pilih…</option>
            @foreach (['Makanan', 'Minuman', 'Fashion', 'Kerajinan', 'Bahan', 'Lainnya'] as $cat)
                <option value="{{ $cat }}" @selected(old('category', $product->category ?? '') === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
        @error('category')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
    </div>
    <x-input label="SKU" name="sku" :value="$product->sku ?? null" placeholder="Opsional"/>
    <x-input label="Harga Jual (Rp)" name="price" type="number" required :value="$product->price ?? null" min="0" step="100"/>
    <x-input label="Harga Modal / HPP (Rp)" name="cost_price" type="number" :value="$product->cost_price ?? null" min="0" step="100"/>
    <x-input label="Stok" name="stock" type="number" required :value="$product->stock ?? 0" min="0"/>
    <x-input label="Stok Minimum (peringatan)" name="min_stock" type="number" required :value="$product->min_stock ?? 5" min="0"/>
    <div>
        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Status</label>
        <select name="status" class="form-input">
            <option value="active" @selected(old('status', $product->status ?? 'active') === 'active')>Aktif</option>
            <option value="inactive" @selected(old('status', $product->status ?? '') === 'inactive')>Nonaktif</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Foto Produk</label>
        <input type="file" name="photo" accept="image/*" class="form-input !py-2 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-600 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-white">
        @error('photo')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Deskripsi</label>
        <textarea name="description" rows="5" class="form-input" placeholder="Ceritakan keunggulan produkmu…">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
    </div>
</div>
