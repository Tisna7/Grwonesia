@extends('layouts.business')

@section('title', 'AI Marketing Center')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6" x-data="marketingStudio()">
    {{-- Generator --}}
    <div class="glass-card p-6">
        <div class="flex items-center gap-3">
            <span class="w-9 h-9 rounded-xl ai-gradient flex items-center justify-center text-white ai-active-pulse">✦</span>
            <div>
                <h3 class="text-sm font-bold ai-gradient-text">Studio Konten AI</h3>
                <p class="text-[11px] text-slate-400">Copywriting otomatis untuk semua channel promosi</p>
            </div>
        </div>

        @unless ($aiConfigured)
            <div class="mt-4"><x-ai-unavailable/></div>
        @endunless

        <div class="mt-5">
            <p class="text-xs font-semibold text-slate-300 mb-2">Jenis Konten</p>
            <div class="flex flex-wrap gap-2">
                @foreach ($types as $type)
                    <button type="button"
                            class="badge-pill transition-all"
                            :class="type === '{{ $type->value }}' ? 'bg-primary-600/40 text-white border-primary-400/60' : 'bg-primary-950/40 text-slate-400 border-primary-400/15 hover:text-white'"
                            @click="type = '{{ $type->value }}'">
                        {{ $type->icon() }} {{ $type->label() }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Produk (opsional)</label>
            <select class="form-input" x-model="productId">
                <option value="">— Umum / semua produk —</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-4">
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Brief / Instruksi</label>
            <textarea x-model="brief" rows="3" class="form-input" placeholder="cth: Promo diskon 15% untuk pembelian kedua, target ibu-ibu muda, tone santai…"></textarea>
        </div>

        <template x-if="error">
            <div class="mt-3 rounded-lg border border-amber-500/40 bg-amber-500/10 px-3 py-2 text-xs text-amber-400" x-text="error"></div>
        </template>

        <button class="btn-primary w-full mt-4" :class="loading && 'btn-shimmer'" @click="generate()" :disabled="loading || !brief.trim()">
            <span x-text="loading ? '⏳ AI sedang membuat konten…' : '✨ Generate Konten'"></span>
        </button>

        {{-- Hasil terbaru --}}
        <template x-if="result">
            <div class="mt-5 glass-card !bg-primary-950/40 p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold text-primary-300" x-text="result.type_label"></p>
                    <button class="text-xs text-primary-300 hover:text-primary-200" @click="copy(result.content)" x-text="copied ? '✓ Tersalin' : '📋 Salin'"></button>
                </div>
                <p class="mt-2 text-sm text-slate-200 whitespace-pre-line" x-text="result.content"></p>
            </div>
        </template>
    </div>

    {{-- Riwayat --}}
    <div class="glass-card p-6 max-h-[80vh] overflow-y-auto">
        <h3 class="text-sm font-bold text-white">Riwayat Konten</h3>
        <div class="mt-4 space-y-3">
            @forelse ($history as $item)
                <div class="glass-card !bg-primary-950/30 p-4" x-data="{ open: false, copiedItem: false }">
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-primary-300">{{ $item->type->icon() }} {{ $item->type->label() }}
                                @if ($item->product)
                                    <span class="text-slate-500 font-normal">· {{ $item->product->name }}</span>
                                @endif
                            </p>
                            <p class="text-[11px] text-slate-500 truncate">{{ $item->brief }} — {{ $item->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <button class="text-xs text-slate-400 hover:text-white" @click="open = !open" x-text="open ? '▲' : '▼'"></button>
                            <button class="text-xs text-primary-300 hover:text-primary-200"
                                    @click="navigator.clipboard.writeText($el.closest('[x-data]').querySelector('[data-content]').textContent.trim()); copiedItem = true; setTimeout(() => copiedItem = false, 1500)"
                                    x-text="copiedItem ? '✓' : '📋'"></button>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-slate-300 whitespace-pre-line" data-content x-show="open" x-cloak>{{ $item->content }}</p>
                </div>
            @empty
                <p class="text-xs text-slate-500">Belum ada konten yang dibuat. Mulai dari studio di samping!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function marketingStudio() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    return {
        type: 'promo_copy',
        productId: '',
        brief: '',
        loading: false,
        error: null,
        result: null,
        copied: false,

        async generate() {
            this.loading = true;
            this.error = null;
            try {
                const res = await fetch(`{{ route('business.marketing.generate') }}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ type: this.type, brief: this.brief, product_id: this.productId || null }),
                });
                const json = await res.json();
                if (!res.ok) {
                    this.error = json.error ?? 'Terjadi kesalahan. Coba lagi.';
                    return;
                }
                this.result = json.data;
            } catch (e) {
                this.error = 'Gagal terhubung ke server.';
            } finally {
                this.loading = false;
            }
        },

        copy(text) {
            navigator.clipboard.writeText(text);
            this.copied = true;
            setTimeout(() => this.copied = false, 1500);
        },
    };
}
</script>
@endpush
