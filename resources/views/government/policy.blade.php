@extends('layouts.government')

@section('title', 'AI Policy Simulator')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6" x-data="policySimulator()" x-init="simulate()">
    {{-- Panel parameter --}}
    <div class="glass-card p-6">
        <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg ai-gradient flex items-center justify-center text-white text-sm ai-active-pulse">🎛️</span>
            <h3 class="text-sm font-bold ai-gradient-text">Parameter Kebijakan</h3>
        </div>
        <p class="mt-2 text-xs text-slate-400">Geser parameter — proyeksi dihitung real-time dari data omzet riil platform.</p>

        <div class="mt-5 space-y-5">
            <div>
                <div class="flex justify-between text-xs">
                    <label class="font-semibold text-slate-300">Subsidi Ongkir</label>
                    <span class="font-mono text-primary-300" x-text="params.subsidi_ongkir + '%'"></span>
                </div>
                <input type="range" min="0" max="50" step="5" x-model.number="params.subsidi_ongkir" @change="simulate()"
                       class="w-full mt-2 accent-primary-500">
            </div>

            <div>
                <div class="flex justify-between text-xs">
                    <label class="font-semibold text-slate-300">Bantuan Alat Produksi</label>
                    <span class="font-mono text-primary-300" x-text="'Rp' + params.bantuan_alat + 'jt'"></span>
                </div>
                <input type="range" min="0" max="500" step="10" x-model.number="params.bantuan_alat" @change="simulate()"
                       class="w-full mt-2 accent-primary-500">
            </div>

            <div>
                <div class="flex justify-between text-xs">
                    <label class="font-semibold text-slate-300">Pelatihan Digital</label>
                    <span class="font-mono text-primary-300" x-text="params.pelatihan_batch + ' batch (' + (params.pelatihan_batch * 25) + ' UMKM)'"></span>
                </div>
                <input type="range" min="0" max="10" step="1" x-model.number="params.pelatihan_batch" @change="simulate()"
                       class="w-full mt-2 accent-primary-500">
            </div>

            <div>
                <div class="flex justify-between text-xs">
                    <label class="font-semibold text-slate-300">Durasi Program</label>
                    <span class="font-mono text-primary-300" x-text="params.durasi_bulan + ' bulan'"></span>
                </div>
                <input type="range" min="1" max="12" step="1" x-model.number="params.durasi_bulan" @change="simulate()"
                       class="w-full mt-2 accent-primary-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Target Sektor</label>
                <select class="form-input" x-model="params.sector" @change="simulate()">
                    <option value="">Semua sektor</option>
                    @foreach ($sectors as $sector)
                        <option value="{{ $sector }}">{{ $sector }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button class="btn-primary w-full mt-6 text-xs" :class="loadingNarrative && 'btn-shimmer'"
                @click="getNarrative()" :disabled="loadingNarrative || !{{ $aiConfigured ? 'true' : 'false' }}">
            <span x-text="loadingNarrative ? '⏳ AI menganalisis…' : '✨ Analisis AI atas Hasil'"></span>
        </button>
        @unless ($aiConfigured)
            <p class="mt-2 text-[11px] text-amber-400">⚠️ Narasi AI butuh GEMINI_API_KEY di .env</p>
        @endunless
    </div>

    {{-- Hasil --}}
    <div class="xl:col-span-2 grid gap-4 content-start">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="glass-card p-4">
                <p class="text-[11px] text-slate-400">Kenaikan Omzet</p>
                <p class="mt-1 text-xl font-extrabold text-emerald-400" x-text="'+' + (result?.uplift_pct ?? 0) + '%'"></p>
            </div>
            <div class="glass-card p-4">
                <p class="text-[11px] text-slate-400">Omzet Tambahan</p>
                <p class="mt-1 text-lg font-extrabold text-white" x-text="fmt(result?.cumulative_extra)"></p>
            </div>
            <div class="glass-card p-4">
                <p class="text-[11px] text-slate-400">Estimasi Biaya</p>
                <p class="mt-1 text-lg font-extrabold text-amber-400" x-text="fmt(result?.estimated_cost)"></p>
            </div>
            <div class="glass-card p-4">
                <p class="text-[11px] text-slate-400">Serapan Kerja Baru</p>
                <p class="mt-1 text-xl font-extrabold ai-gradient-text" x-text="(result?.new_jobs ?? 0) + ' orang'"></p>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white">Proyeksi Omzet: Baseline vs Dengan Kebijakan</h3>
                <span class="badge-pill bg-primary-600/15 text-primary-300 border-primary-500/30"
                      x-text="'ROI: ' + (result?.roi ?? '—') + 'x'"></span>
            </div>
            <div class="mt-4 h-72"><canvas id="policyChart"></canvas></div>
        </div>

        <div class="glass-card p-5">
            <h3 class="text-xs font-bold text-white">Kontribusi per Instrumen Kebijakan</h3>
            <div class="mt-3 grid sm:grid-cols-3 gap-3">
                <template x-for="[name, pct] in Object.entries(result?.components ?? {})" :key="name">
                    <div class="glass-card !bg-primary-950/40 p-3 text-center">
                        <p class="text-[11px] text-slate-400" x-text="name"></p>
                        <p class="mt-1 text-lg font-extrabold text-primary-300" x-text="'+' + pct + '%'"></p>
                    </div>
                </template>
            </div>
        </div>

        <template x-if="narrative">
            <div class="glass-card p-5" style="border-color: rgba(236, 72, 153, 0.25);">
                <p class="text-sm font-bold ai-gradient-text">🤖 Ringkasan Eksekutif AI</p>
                <p class="mt-2 text-sm text-slate-200 leading-relaxed whitespace-pre-line" x-text="narrative"></p>
            </div>
        </template>
        <template x-if="error">
            <div class="glass-card p-4 border-amber-500/40 text-xs text-amber-400" x-text="error"></div>
        </template>
    </div>
</div>
@endsection

@push('scripts')
<script>
function policySimulator() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    let chart = null;

    return {
        params: { subsidi_ongkir: 20, bantuan_alat: 50, pelatihan_batch: 2, durasi_bulan: 3, sector: '' },
        result: null,
        narrative: null,
        error: null,
        loadingNarrative: false,

        fmt(v) {
            if (v == null) return '—';
            return 'Rp' + (v >= 1000000000 ? (v / 1000000000).toFixed(1) + 'M'
                : v >= 1000000 ? (v / 1000000).toFixed(1) + 'jt'
                : Number(v).toLocaleString('id-ID'));
        },

        async simulate() {
            this.error = null;
            this.narrative = null;
            try {
                const res = await fetch(`{{ route('government.policy.simulate') }}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.params),
                });
                const json = await res.json();
                if (!res.ok) {
                    this.error = json.error ?? 'Simulasi gagal.';
                    return;
                }
                this.result = json.data;
                this.renderChart();
            } catch (e) {
                this.error = 'Gagal terhubung ke server.';
            }
        },

        async getNarrative() {
            this.loadingNarrative = true;
            try {
                const res = await fetch(`{{ route('government.policy.simulate') }}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ...this.params, with_narrative: true }),
                });
                const json = await res.json();
                if (res.ok && json.narrative) {
                    this.narrative = json.narrative;
                } else {
                    this.error = json.error ?? 'AI belum tersedia. Periksa GEMINI_API_KEY.';
                }
            } catch (e) {
                this.error = 'Gagal terhubung ke server.';
            } finally {
                this.loadingNarrative = false;
            }
        },

        renderChart() {
            const monthly = this.result.monthly;
            const config = {
                type: 'line',
                data: {
                    labels: monthly.map(m => m.month),
                    datasets: [
                        {
                            label: 'Baseline',
                            data: monthly.map(m => m.baseline),
                            borderColor: '#64748B',
                            borderDash: [6, 4],
                            pointRadius: 3,
                            tension: 0.3,
                        },
                        {
                            label: 'Dengan Kebijakan',
                            data: monthly.map(m => m.projected),
                            borderColor: '#EC4899',
                            backgroundColor: 'rgba(236, 72, 153, 0.12)',
                            fill: true,
                            pointRadius: 4,
                            tension: 0.3,
                            borderWidth: 2.5,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#94A3B8', font: { size: 11 } } },
                        tooltip: { callbacks: { label: (item) => item.dataset.label + ': Rp' + Number(item.raw).toLocaleString('id-ID') } },
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#64748B', font: { size: 10 } } },
                        y: {
                            grid: { color: 'rgba(124, 58, 237, 0.08)' },
                            ticks: {
                                color: '#64748B',
                                font: { size: 10 },
                                callback: (v) => 'Rp' + (v >= 1000000 ? (v / 1000000).toFixed(0) + 'jt' : (v / 1000) + 'rb'),
                            },
                        },
                    },
                },
            };

            if (chart) {
                chart.data = config.data;
                chart.update();
            } else {
                chart = new Chart(document.getElementById('policyChart'), config);
            }
        },
    };
}
</script>
@endpush
