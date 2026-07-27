@extends('layouts.business')

@section('title', 'Posting ke Instagram')

@section('content')
<div class="grid gap-6">
    {{-- Status koneksi --}}
    @if (! $configured)
        <div class="glass-card p-5 border-amber-500/30">
            <div class="flex items-start gap-3">
                <div class="icon-tile shrink-0 !border-amber-500/30" style="background: linear-gradient(145deg, rgba(245,158,11,0.22), rgba(245,158,11,0.05)); color: #FBBF24;">
                    <x-icon name="instagram"/>
                </div>
                <div>
                    <p class="text-sm font-bold text-amber-400">Instagram belum terhubung</p>
                    <p class="mt-1 text-xs text-slate-400 leading-relaxed">
                        Isi <code class="font-mono text-primary-300">IG_BUSINESS_ID</code> dan
                        <code class="font-mono text-primary-300">IG_ACCESS_TOKEN</code> di file .env.
                        Panduan lengkap langkah demi langkah ada di
                        <code class="font-mono text-primary-300">docs/instagram-setup.md</code>.
                    </p>
                </div>
            </div>
        </div>
    @elseif ($account)
        <div class="glass-card p-5 border-emerald-500/25">
            <div class="flex items-center gap-4">
                @if (!empty($account['profile_picture_url']))
                    <img src="{{ $account['profile_picture_url'] }}" alt="" class="w-12 h-12 rounded-full border border-primary-400/30">
                @else
                    <div class="icon-tile"><x-icon name="instagram"/></div>
                @endif
                <div class="flex-1">
                    <p class="text-sm font-bold text-white">{{ '@'.($account['username'] ?? '?') }}</p>
                    <p class="text-xs text-slate-400">
                        {{ number_format($account['followers_count'] ?? 0, 0, ',', '.') }} followers ·
                        {{ number_format($account['media_count'] ?? 0, 0, ',', '.') }} post
                    </p>
                </div>
                <span class="badge-pill bg-emerald-500/15 text-emerald-400 border-emerald-500/40">● Terhubung</span>
            </div>
        </div>
    @else
        <div class="glass-card p-5 border-rose-500/30">
            <p class="text-sm font-bold text-rose-400">Token tidak valid</p>
            <p class="mt-1 text-xs text-slate-400">
                Kredensial terisi tapi Meta menolaknya — token mungkin kedaluwarsa (token long-lived berlaku ±60 hari).
                Perbarui <code class="font-mono text-primary-300">IG_ACCESS_TOKEN</code> di .env.
            </p>
        </div>
    @endif

    @if ($appUrlIsLocal && $configured)
        <div class="glass-card p-4 border-amber-500/30 text-xs text-amber-400">
            ⚠ APP_URL kamu masih <code class="font-mono">{{ config('app.url') }}</code> — server Meta tidak bisa mengunduh
            foto dari localhost. Jalankan lewat tunnel (ngrok / cloudflared) atau server publik, lalu sesuaikan APP_URL.
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        {{-- Composer --}}
        <div class="glass-card p-6" x-data="igComposer()">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg ai-gradient flex items-center justify-center text-white"><x-icon name="instagram" class="w-4 h-4"/></span>
                <h3 class="text-sm font-bold text-white">Buat Postingan</h3>
            </div>

            <form method="POST" action="{{ route('business.instagram.publish') }}" class="mt-5 space-y-4"
                  onsubmit="return confirm('Posting foto ini ke Instagram sekarang?')">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Produk (fotonya yang akan diposting) <span class="text-magenta">*</span></label>
                    <select name="product_id" required class="form-input" x-model="productId">
                        <option value="">Pilih produk…</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-photo="{{ asset('storage/'.$product->photo_path) }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    @if ($products->isEmpty())
                        <p class="mt-1 text-xs text-amber-400">Belum ada produk aktif yang punya foto — unggah foto dulu di halaman Produk.</p>
                    @endif
                </div>

                <template x-if="photoUrl()">
                    <img :src="photoUrl()" alt="Preview" class="w-40 h-40 object-cover rounded-2xl border border-primary-400/20">
                </template>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-semibold text-slate-300">Caption <span class="text-magenta">*</span></label>
                        <span class="text-[10px] text-slate-500" x-text="caption.length + '/2200'"></span>
                    </div>
                    <textarea name="caption" rows="6" maxlength="2200" required class="form-input" x-model="caption"
                              placeholder="Tulis caption… atau pakai hasil dari Marketing Center di bawah."></textarea>
                </div>

                @if ($captions->isNotEmpty())
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 mb-2">Pakai caption dari Marketing Center:</p>
                        <div class="space-y-1.5 max-h-36 overflow-y-auto pr-1">
                            @foreach ($captions as $item)
                                <button type="button"
                                        class="w-full text-left glass-card !bg-primary-950/30 px-3 py-2 text-[11px] text-slate-400 hover:text-white hover:border-primary-400/40 transition-colors truncate"
                                        @click="caption = {{ Js::from($item->content) }}">
                                    {{ Str::limit($item->brief, 60) }} — {{ $item->created_at->diffForHumans() }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <button type="submit" class="btn-primary w-full" @disabled(! $configured || $products->isEmpty())>
                    Posting ke Instagram
                </button>
            </form>
        </div>

        {{-- Riwayat --}}
        <div class="glass-card overflow-hidden">
            <div class="px-5 py-4 border-b border-primary-400/10">
                <h3 class="text-sm font-bold text-white">Riwayat Posting</h3>
            </div>
            <div class="divide-y divide-primary-400/5 max-h-[32rem] overflow-y-auto">
                @forelse ($posts as $post)
                    <div class="px-5 py-4 flex items-start gap-3">
                        <img src="{{ $post->image_url }}" alt="" class="w-12 h-12 rounded-xl object-cover border border-primary-400/15"
                             onerror="this.style.display='none'">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-slate-300 line-clamp-2">{{ $post->caption }}</p>
                            <p class="mt-1 text-[10px] text-slate-500">
                                {{ $post->product?->name }} · {{ $post->created_at->format('d M Y H:i') }}
                                @if ($post->error)
                                    <span class="text-rose-400 block mt-0.5">{{ Str::limit($post->error, 120) }}</span>
                                @endif
                            </p>
                        </div>
                        @if ($post->status === 'published')
                            <span class="badge-pill bg-emerald-500/15 text-emerald-400 border-emerald-500/40 shrink-0">Terposting</span>
                        @else
                            <span class="badge-pill bg-rose-500/15 text-rose-400 border-rose-500/40 shrink-0">Gagal</span>
                        @endif
                    </div>
                @empty
                    <p class="px-5 py-10 text-center text-xs text-slate-500">Belum ada riwayat posting.</p>
                @endforelse
            </div>
            <div class="px-5 py-3">{{ $posts->links() }}</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function igComposer() {
    return {
        productId: '',
        caption: '',
        photoUrl() {
            const opt = document.querySelector(`select[name=product_id] option[value="${this.productId}"]`);
            return opt?.dataset.photo ?? null;
        },
    };
}
</script>
@endpush
