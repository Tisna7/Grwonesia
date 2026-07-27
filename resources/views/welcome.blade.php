<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grownesia — Ekosistem Ekonomi UMKM Berbasis AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased">
    <div class="ambient-glow"></div>

    <nav class="max-w-6xl mx-auto px-6 py-6 flex items-center justify-between">
        <span class="flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-lg ai-gradient flex items-center justify-center text-white font-bold shadow-lg shadow-primary-600/40" style="font-family: 'Space Grotesk'">G</span>
            <span class="text-lg font-bold tracking-tight text-white" style="font-family: 'Space Grotesk'">Grownesia</span>
        </span>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ auth()->user()->isBusiness() ? route('business.dashboard') : route('home') }}" class="btn-primary text-xs">Buka Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-secondary text-xs">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary text-xs">Daftar Bisnis</a>
            @endauth
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6">
        {{-- Hero --}}
        <section class="pt-16 pb-10 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="badge-pill bg-primary-600/12 text-primary-300 border-primary-500/30">
                    <x-icon name="sparkles" class="w-3.5 h-3.5"/> Didukung Gemini AI
                </span>
                <h1 class="mt-6 text-4xl sm:text-5xl font-bold tracking-tight text-white leading-[1.1]">
                    Belanja Lokal Berdampak,<br>
                    <span class="ai-gradient-text">Bisnis Tumbuh Bersama AI</span>
                </h1>
                <p class="mt-5 text-slate-400 leading-relaxed max-w-lg">
                    Grownesia menghubungkan UMKM, konsumen, dan pemerintah daerah dalam satu ekosistem ekonomi digital —
                    dengan AI yang menganalisis, memprediksi, dan membuat konten untukmu.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('register') }}" class="btn-primary">Mulai Gratis untuk UMKM</a>
                    <a href="{{ route('login') }}" class="btn-secondary">Lihat Demo</a>
                </div>
                <div class="mt-10 flex items-center gap-8">
                    <div>
                        <p class="text-2xl font-bold text-white" style="font-family: 'Space Grotesk'">12+</p>
                        <p class="text-[11px] text-slate-500">Fitur AI Terintegrasi</p>
                    </div>
                    <div class="w-px h-8 bg-primary-400/15"></div>
                    <div>
                        <p class="text-2xl font-bold text-white" style="font-family: 'Space Grotesk'">3</p>
                        <p class="text-[11px] text-slate-500">Dashboard Multi-Peran</p>
                    </div>
                    <div class="w-px h-8 bg-primary-400/15"></div>
                    <div>
                        <p class="text-2xl font-bold text-white" style="font-family: 'Space Grotesk'">100%</p>
                        <p class="text-[11px] text-slate-500">Produk Lokal Indonesia</p>
                    </div>
                </div>
            </div>

            {{-- Mock product shot dari komponen asli --}}
            <div class="relative hidden lg:block" aria-hidden="true">
                <div class="absolute -inset-8 bg-primary-600/10 blur-3xl rounded-full"></div>
                <div class="relative space-y-4">
                    <div class="glass-card p-5 glass-card-hover ml-10">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Omzet Bulan Ini</p>
                                <p class="mt-2 text-2xl font-extrabold text-white" style="font-family: 'Space Grotesk'">Rp24.005.000</p>
                                <p class="mt-1.5 inline-flex items-center gap-1 text-xs font-semibold text-emerald-400">
                                    <x-icon name="trending-up" class="w-3.5 h-3.5"/> 12,7% vs bulan lalu
                                </p>
                            </div>
                            <div class="icon-tile"><x-icon name="wallet"/></div>
                        </div>
                    </div>
                    <div class="glass-card p-5" style="border-color: rgba(236, 72, 153, 0.25);">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 shrink-0 rounded-xl ai-gradient flex items-center justify-center text-white ai-active-pulse">
                                <x-icon name="sparkles" class="w-4 h-4"/>
                            </div>
                            <div>
                                <p class="text-sm font-bold ai-gradient-text">AI Insight Hari Ini</p>
                                <p class="mt-1 text-xs text-slate-300 leading-relaxed">
                                    "Penjualan Kopi Klepon naik pesat di Bandung. Disarankan menambah stok 50 unit sebelum akhir pekan."
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="glass-card p-4 mr-10 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="icon-tile !w-9 !h-9"><x-icon name="message-circle" class="!w-4 !h-4"/></div>
                            <div>
                                <p class="text-xs font-bold text-white">Broadcast WhatsApp</p>
                                <p class="text-[10px] text-slate-500">40 pelanggan terkirim otomatis</p>
                            </div>
                        </div>
                        <span class="badge-pill bg-emerald-500/15 text-emerald-400 border-emerald-500/40">Terkirim</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Fitur --}}
        <section class="py-16">
            <p class="text-center text-[11px] font-bold uppercase tracking-[0.2em] text-primary-300/70">Satu platform, tiga peran</p>
            <h2 class="mt-2 text-center text-2xl sm:text-3xl font-bold text-white">Semua yang UMKM butuhkan untuk tumbuh</h2>
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ([
                    ['dashboard', 'AI Business Dashboard', 'Omzet real-time, health score 0–100, dan insight harian yang tahu kondisi bisnismu.'],
                    ['bot', 'AI Business Coach', 'Konsultan 24/7 untuk strategi harga, pemasaran, dan operasional — paham datamu.'],
                    ['trending-up', 'Prediksi Inventori', 'Tahu tanggal stok habis sebelum kejadian, lengkap dengan prioritas restock.'],
                    ['sparkles', 'Marketing Center', 'Caption IG/TikTok, broadcast WA, dan copy promo jadi dalam hitungan detik.'],
                ] as [$icon, $title, $desc])
                    <div class="glass-card glass-card-hover p-6">
                        <div class="icon-tile"><x-icon :name="$icon"/></div>
                        <h3 class="mt-4 text-sm font-bold text-white">{{ $title }}</h3>
                        <p class="mt-1.5 text-xs text-slate-400 leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- CTA --}}
        <section class="pb-20">
            <div class="glass-card p-10 text-center relative overflow-hidden">
                <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-96 h-40 bg-primary-600/20 blur-3xl rounded-full"></div>
                <h2 class="relative text-2xl font-bold text-white">Siap mengembangkan usahamu?</h2>
                <p class="relative mt-2 text-sm text-slate-400">Daftar gratis — AI langsung bekerja sejak produk pertamamu.</p>
                <a href="{{ route('register') }}" class="relative btn-primary mt-6">Buat Akun Bisnis</a>
            </div>
        </section>
    </main>

    <footer class="border-t border-primary-400/10 py-8 text-center text-xs text-slate-600">
        © {{ date('Y') }} Grownesia — Ekosistem Ekonomi & E-Commerce UMKM Berbasis AI
    </footer>
</body>
</html>
