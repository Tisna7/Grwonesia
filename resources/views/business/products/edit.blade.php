@extends('layouts.business')

@section('title', 'Edit Produk')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6"
     x-data="productAi({{ $product->id }})">

    {{-- Form edit --}}
    <div class="glass-card p-6 xl:col-span-2">
        <div class="flex items-start justify-between mb-4">
            <h3 class="text-sm font-bold text-white">Detail Produk</h3>
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-16 h-16 rounded-xl object-cover border border-primary-400/20">
        </div>
        <form method="POST" action="{{ route('business.products.update', $product) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('business.products._form', ['product' => $product])
            <div class="mt-6 flex gap-3">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('business.products.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </form>
        <form method="POST" action="{{ route('business.products.destroy', $product) }}" class="mt-4"
              onsubmit="return confirm('Hapus produk ini? Tindakan tidak bisa dibatalkan.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs text-rose-400 hover:text-rose-300">Hapus produk</button>
        </form>
    </div>

    {{-- Panel AI Optimizer --}}
    <div class="space-y-4">
        <div class="glass-card p-5" style="border-color: rgba(236, 72, 153, 0.25);">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg ai-gradient flex items-center justify-center text-white text-sm ai-active-pulse">✦</span>
                <h3 class="text-sm font-bold ai-gradient-text">AI Product Optimizer</h3>
            </div>
            <p class="mt-2 text-xs text-slate-400">Analisis foto, saran judul SEO, dan deskripsi otomatis oleh Gemini AI.</p>

            <div class="mt-4 space-y-2">
                <button class="btn-secondary w-full text-xs" @click="run('photo')" :disabled="loading" x-text="loading === 'photo' ? '⏳ Menganalisis foto…' : '🖼️ Analisis Kualitas Foto'"></button>
                <button class="btn-secondary w-full text-xs" @click="run('seo')" :disabled="loading" x-text="loading === 'seo' ? '⏳ Menyusun judul…' : '🔍 Saran Judul SEO'"></button>
                <button class="btn-secondary w-full text-xs" @click="run('description')" :disabled="loading" x-text="loading === 'description' ? '⏳ Menulis deskripsi…' : '📝 Buat Deskripsi Otomatis'"></button>
            </div>

            <template x-if="error">
                <div class="mt-3 rounded-lg border border-amber-500/40 bg-amber-500/10 px-3 py-2 text-xs text-amber-400" x-text="error"></div>
            </template>
        </div>

        {{-- Hasil analisis foto --}}
        <template x-if="photo">
            <div class="glass-card p-5">
                <h4 class="text-xs font-bold text-white">🖼️ Analisis Foto</h4>
                <div class="mt-3 flex items-center gap-4">
                    <div class="text-center">
                        <p class="text-2xl font-extrabold" :class="photo.quality_score >= 70 ? 'text-emerald-400' : (photo.quality_score >= 40 ? 'text-amber-400' : 'text-rose-400')" x-text="photo.quality_score"></p>
                        <p class="text-[10px] text-slate-500">Skor</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-extrabold text-primary-300" x-text="photo.brightness_pct + '%'"></p>
                        <p class="text-[10px] text-slate-500">Kecerahan</p>
                    </div>
                </div>
                <template x-if="photo.issues?.length">
                    <div class="mt-3">
                        <p class="text-[11px] font-bold text-amber-400">⚠️ Masalah:</p>
                        <ul class="mt-1 space-y-1">
                            <template x-for="issue in photo.issues"><li class="text-xs text-slate-300" x-text="'• ' + issue"></li></template>
                        </ul>
                    </div>
                </template>
                <template x-if="photo.suggestions?.length">
                    <div class="mt-3">
                        <p class="text-[11px] font-bold text-primary-300">💡 Saran:</p>
                        <ul class="mt-1 space-y-1">
                            <template x-for="s in photo.suggestions"><li class="text-xs text-slate-300" x-text="'• ' + s"></li></template>
                        </ul>
                    </div>
                </template>
            </div>
        </template>

        {{-- Hasil saran SEO --}}
        <template x-if="seo">
            <div class="glass-card p-5">
                <h4 class="text-xs font-bold text-white">🔍 Saran Judul SEO</h4>
                <ul class="mt-2 space-y-2">
                    <template x-for="title in seo.titles">
                        <li class="text-xs text-slate-300 flex items-start justify-between gap-2">
                            <span x-text="title"></span>
                            <button class="shrink-0 text-primary-300 hover:text-primary-200" @click="applyTitle(title)" title="Pakai judul ini">↩</button>
                        </li>
                    </template>
                </ul>
                <div class="mt-3 flex flex-wrap gap-1">
                    <template x-for="kw in seo.keywords">
                        <span class="badge-pill bg-primary-600/15 text-primary-300 border-primary-500/30" x-text="kw"></span>
                    </template>
                </div>
            </div>
        </template>

        {{-- Hasil deskripsi --}}
        <template x-if="description">
            <div class="glass-card p-5">
                <h4 class="text-xs font-bold text-white">📝 Deskripsi AI</h4>
                <p class="mt-2 text-xs text-slate-300 whitespace-pre-line" x-text="description"></p>
                <button class="btn-primary w-full mt-3 text-xs" @click="applyDescription()">↩ Masukkan ke Form</button>
            </div>
        </template>

        {{-- Analisis tersimpan sebelumnya --}}
        @if ($product->ai_photo_analysis)
            <div class="glass-card p-4" x-show="!photo">
                <p class="text-[11px] text-slate-500">Analisis foto terakhir: skor
                    <span class="font-bold text-primary-300">{{ $product->ai_photo_analysis['quality_score'] ?? '-' }}/100</span>
                    — klik "Analisis Kualitas Foto" untuk memperbarui.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function productAi(productId) {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const routes = {
        photo: `{{ url('business/products') }}/${productId}/ai/photo`,
        seo: `{{ url('business/products') }}/${productId}/ai/seo`,
        description: `{{ url('business/products') }}/${productId}/ai/description`,
    };

    return {
        loading: null,
        error: null,
        photo: null,
        seo: null,
        description: null,

        async run(kind) {
            this.loading = kind;
            this.error = null;
            try {
                const res = await fetch(routes[kind], {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                });
                const json = await res.json();
                if (!res.ok) {
                    this.error = json.error ?? 'Terjadi kesalahan. Coba lagi.';
                    return;
                }
                if (kind === 'description') {
                    this.description = json.data.description;
                } else {
                    this[kind] = json.data;
                }
            } catch (e) {
                this.error = 'Gagal terhubung ke server.';
            } finally {
                this.loading = null;
            }
        },

        applyTitle(title) {
            document.getElementById('name').value = title;
            document.getElementById('name').scrollIntoView({ behavior: 'smooth', block: 'center' });
        },

        applyDescription() {
            document.querySelector('textarea[name="description"]').value = this.description;
        },
    };
}
</script>
@endpush
