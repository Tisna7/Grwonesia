@extends('layouts.government')

@section('title', 'Program & Event')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6" x-data="programPage()">
    {{-- Daftar program --}}
    <div class="xl:col-span-2 grid gap-4">
        @forelse ($programs as $program)
            <div class="glass-card glass-card-hover p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-sm font-bold text-white">{{ $program->title }}</h3>
                            @if ($program->ai_recommended)
                                <span class="badge-pill bg-magenta/15 text-pink-400 border-pink-500/40">✦ AI</span>
                            @endif
                        </div>
                        <p class="mt-1 text-xs text-slate-400">
                            {{ $program->typeLabel() }}
                            {{ $program->sector ? ' · '.$program->sector : '' }}
                            {{ $program->city ? ' · 📍 '.$program->city : '' }}
                            @if ($program->starts_at)
                                · {{ $program->starts_at->format('d M Y') }}{{ $program->ends_at ? ' – '.$program->ends_at->format('d M Y') : '' }}
                            @endif
                        </p>
                        @if ($program->description)
                            <p class="mt-2 text-xs text-slate-300 leading-relaxed">{{ $program->description }}</p>
                        @endif
                    </div>
                    <div class="flex flex-col items-end gap-1.5 shrink-0">
                        <span class="badge-pill {{ $program->statusBadgeClass() }}">{{ ucfirst($program->status) }}</span>
                        <span class="badge-pill bg-indigo-500/12 text-indigo-300 border-indigo-500/30">
                            <x-icon name="users" class="w-3 h-3"/> {{ $program->registrations_count }} UMKM terdaftar
                        </span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-primary-400/10 flex items-center gap-3">
                    <form method="POST" action="{{ route('government.programs.update', $program) }}" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-input !py-1.5 !text-xs !w-auto">
                            @foreach (['draft' => 'Draft', 'aktif' => 'Aktif', 'selesai' => 'Selesai'] as $val => $label)
                                <option value="{{ $val }}" @selected($program->status === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-secondary !py-1.5 !px-3 text-xs">Ubah</button>
                    </form>
                    <form method="POST" action="{{ route('government.programs.destroy', $program) }}"
                          onsubmit="return confirm('Hapus program ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-rose-400 hover:text-rose-300">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <x-empty-state icon="📋" title="Belum ada program" description="Buat program pertama atau minta rekomendasi AI dari data ekonomi riil."/>
        @endforelse

        <div>{{ $programs->links() }}</div>
    </div>

    {{-- Panel kanan --}}
    <div class="space-y-4">
        {{-- Rekomendasi AI --}}
        <div class="glass-card p-5" style="border-color: rgba(236, 72, 153, 0.25);">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg ai-gradient flex items-center justify-center text-white text-sm ai-active-pulse">✦</span>
                <h3 class="text-sm font-bold ai-gradient-text">Rekomendasi Program AI</h3>
            </div>
            <p class="mt-2 text-xs text-slate-400">AI menganalisis hotspot, tren sektor, dan produk unggulan untuk mengusulkan program yang paling dibutuhkan.</p>
            <button class="btn-primary w-full mt-3 text-xs" :class="loading && 'btn-shimmer'" @click="recommend()" :disabled="loading">
                <span x-text="loading ? '⏳ Menganalisis data…' : '✨ Minta Rekomendasi'"></span>
            </button>
            <template x-if="error">
                <div class="mt-3 rounded-lg border border-amber-500/40 bg-amber-500/10 px-3 py-2 text-xs text-amber-400" x-text="error"></div>
            </template>
            <template x-for="(rec, i) in recommendations" :key="i">
                <div class="mt-3 glass-card !bg-primary-950/40 p-3">
                    <p class="text-xs font-bold text-white" x-text="rec.title"></p>
                    <p class="mt-0.5 text-[11px] text-primary-300" x-text="rec.type + ' · ' + rec.sector + ' · 📍 ' + rec.city"></p>
                    <p class="mt-1 text-[11px] text-slate-400" x-text="rec.reason"></p>
                    <button class="btn-secondary w-full mt-2 !py-1.5 text-[11px]" @click="useRecommendation(rec)">↩ Pakai sebagai draf</button>
                </div>
            </template>
        </div>

        {{-- Form program baru --}}
        <div class="glass-card p-5">
            <h3 class="text-sm font-bold text-white">+ Program Baru</h3>
            <form method="POST" action="{{ route('government.programs.store') }}" class="mt-3 space-y-3">
                @csrf
                <input type="hidden" name="ai_recommended" :value="fromAi ? 1 : 0">
                <x-input label="Judul Program" name="title" required x-model="form.title"/>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Jenis</label>
                        <select name="type" class="form-input" x-model="form.type">
                            <option value="pelatihan">Pelatihan</option>
                            <option value="bantuan">Bantuan</option>
                            <option value="event">Event</option>
                            <option value="pameran">Pameran</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Status</label>
                        <select name="status" class="form-input">
                            <option value="draft">Draft</option>
                            <option value="aktif">Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <x-input label="Sektor" name="sector" placeholder="cth: Kuliner" x-model="form.sector"/>
                    <x-input label="Kota" name="city" placeholder="cth: Bandung" x-model="form.city"/>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <x-input label="Mulai" name="starts_at" type="date"/>
                    <x-input label="Selesai" name="ends_at" type="date"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-input" x-model="form.description"></textarea>
                </div>
                <button type="submit" class="btn-primary w-full text-xs">Simpan Program</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function programPage() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    return {
        loading: false,
        error: null,
        recommendations: [],
        fromAi: false,
        form: { title: '', type: 'pelatihan', sector: '', city: '', description: '' },

        async recommend() {
            this.loading = true;
            this.error = null;
            try {
                const res = await fetch(`{{ route('government.programs.recommend') }}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                });
                const json = await res.json();
                if (!res.ok) {
                    this.error = json.error ?? 'Terjadi kesalahan.';
                    return;
                }
                this.recommendations = json.data.programs ?? [];
            } catch (e) {
                this.error = 'Gagal terhubung ke server.';
            } finally {
                this.loading = false;
            }
        },

        useRecommendation(rec) {
            this.form = {
                title: rec.title,
                type: rec.type,
                sector: rec.sector,
                city: rec.city,
                description: rec.reason,
            };
            this.fromAi = true;
        },
    };
}
</script>
@endpush
