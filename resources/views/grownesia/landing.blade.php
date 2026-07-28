@extends('layouts.landing')

@section('content')

  <!-- LANDING PAGE HERO SECTION -->
  <section id="beranda"
    class="relative overflow-hidden pt-16 pb-24 border-b border-purple-500/20 bg-gradient-to-b from-purple-950/60 via-purple-dark to-purple-dark">
    <!-- Glowing backdrop -->
    <div
      class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[400px] bg-purple-600/20 rounded-full blur-[140px] pointer-events-none">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

        <!-- Left Hero Content -->
        <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
          <!-- Badge -->
          <div
            class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-purple-500/10 border border-purple-400/30 text-purple-300 text-xs font-semibold">
            <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
            Platform Ekosistem UMKM Indonesia Berbasis AI
          </div>

          <h1
            class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight font-heading text-white leading-tight">
            Pemberdayaan Ekonomi <br>
            <span class="text-gradient-purple">UMKM Indonesia</span> Berbasis AI
          </h1>

          <p class="text-base sm:text-lg text-purple-200/80 max-w-2xl leading-relaxed">
            Solusi digital terintegrasi yang menghubungkan <strong>Pembeli</strong>, <strong>Pelaku Usaha UMKM</strong>,
            dan <strong>Pemerintah Daerah</strong> melalui fitur cerdas kecerdasan buatan & transparansi <em>Impact
              Score</em>.
          </p>

          <!-- Hero Actions -->
          <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
            <a href="{{ route('register') }}"
              class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-sm transition shadow-lg shadow-purple-900/50 flex items-center gap-2">
              <span>Mulai Sekarang — Gratis</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>
            <a href="{{ route('login') }}"
              class="px-8 py-3.5 rounded-xl bg-purple-950/80 hover:bg-purple-900 text-purple-200 font-bold text-sm border border-purple-500/30 transition flex items-center gap-2">
              <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              <span>Masuk ke User Portal</span>
            </a>
          </div>

          <!-- Stats Ticker -->
          <div class="grid grid-cols-3 gap-4 pt-8 border-t border-purple-500/20">
            <div>
              <div class="text-3xl font-extrabold text-white font-heading">1,420+</div>
              <div class="text-xs text-purple-300/70">UMKM Terverifikasi</div>
            </div>
            <div>
              <div class="text-3xl font-extrabold text-emerald-400 font-heading">18.5k+</div>
              <div class="text-xs text-purple-300/70">Pekerja Terbantu</div>
            </div>
            <div>
              <div class="text-3xl font-extrabold text-purple-300 font-heading">120+</div>
              <div class="text-xs text-purple-300/70">Desa Berkembang</div>
            </div>
          </div>
        </div>

        <!-- Right Hero Card Visual -->
        <div class="lg:col-span-5">
          <div
            class="relative rounded-3xl p-6 bg-purple-card border border-purple-500/30 shadow-2xl glow-purple space-y-5">
            <div class="flex items-center justify-between border-b border-purple-500/20 pb-3">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                <span class="text-xs font-bold text-white">Live AI Personal Shopper</span>
              </div>
              <span
                class="text-[10px] px-2 py-0.5 rounded-full bg-purple-800/40 text-purple-300 border border-purple-500/30">User
                Role Preview</span>
            </div>

            <div class="p-4 rounded-2xl bg-purple-900/50 border border-purple-500/30 space-y-2">
              <div class="text-xs font-bold text-purple-300 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Kueri AI Pengguna:
              </div>
              <p class="text-xs text-purple-200/90 italic">"Saya ingin mencari kado batik & hampers kopi untuk ibu umur 50
                tahun budget Rp 300rb."</p>
            </div>

            <div class="p-3.5 rounded-2xl bg-purple-950/90 border border-purple-500/30 space-y-2">
              <div class="text-[11px] font-semibold text-emerald-400">✓ AI Rekomendasi Terhitung:</div>
              <div class="flex items-center gap-3">
                <img src="/images/products/batik_solo.webp" loading="lazy"
                  class="w-14 h-14 rounded-xl object-cover border border-purple-400/30">
                <div class="flex-1 min-w-0">
                  <h4 class="text-xs font-bold text-white truncate">Batik Tulis Mega Mendung Signature</h4>
                  <p class="text-[10px] text-purple-300/70">Batik Sekar Arum • Solo</p>
                  <span class="text-xs font-extrabold text-purple-300">Rp 185.000</span>
                </div>
              </div>
            </div>

            <a href="{{ route('login') }}"
              class="w-full py-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition shadow-lg block text-center">
              Coba Fitur Lengkap di Dashboard →
            </a>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- FITUR UTAMA ROLE USER -->
  <section id="fitur" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
    <div class="text-center max-w-3xl mx-auto space-y-3">
      <span class="text-xs font-extrabold uppercase tracking-widest text-fuchsia-400">Fitur Cerdas Unggulan</span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-heading">Solusi AI Khusus Pengguna (User Role)</h2>
      <p class="text-xs sm:text-sm text-purple-300/70">Dibuat khusus untuk memberikan pengalaman belanja personal yang
        cerdas dan berdampak positif bagi UMKM.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <!-- Card 1: AI Shopping Assistant -->
      <div
        class="p-6 rounded-3xl bg-purple-card border border-purple-500/20 bg-purple-card-hover transition duration-300 space-y-4">
        <div
          class="w-12 h-12 rounded-2xl bg-purple-600/30 border border-purple-400/30 flex items-center justify-center text-purple-300 shadow-md">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
          </svg>
        </div>
        <h3 class="text-lg font-bold text-white font-heading">AI Shopping Assistant</h3>
        <p class="text-xs text-purple-300/80 leading-relaxed">
          Interaksi percakapan langsung dengan AI. Mengerti kebutuhan pengguna seperti pencarian kado usia tertentu atau
          selera produk tertentu.
        </p>
      </div>

      <!-- Card 2: AI Gift Recommendation -->
      <div
        class="p-6 rounded-3xl bg-purple-card border border-purple-500/20 bg-purple-card-hover transition duration-300 space-y-4">
        <div
          class="w-12 h-12 rounded-2xl bg-fuchsia-600/30 border border-fuchsia-400/30 flex items-center justify-center text-fuchsia-300 shadow-md">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2z" />
          </svg>
        </div>
        <h3 class="text-lg font-bold text-white font-heading">AI Gift Recommendation</h3>
        <p class="text-xs text-purple-300/80 leading-relaxed">
          Meracik kombinasi paket produk (*bundling*) secara otomatis yang menyesuaikan anggaran belanja (misal: Rp
          300.000) dengan 1-klik checkout.
        </p>
      </div>

      <!-- Card 3: AI Product Comparison -->
      <div
        class="p-6 rounded-3xl bg-purple-card border border-purple-500/20 bg-purple-card-hover transition duration-300 space-y-4">
        <div
          class="w-12 h-12 rounded-2xl bg-indigo-600/30 border border-indigo-400/30 flex items-center justify-center text-indigo-300 shadow-md">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
        <h3 class="text-lg font-bold text-white font-heading">AI Product Comparison</h3>
        <p class="text-xs text-purple-300/80 leading-relaxed">
          Membandingkan dua buah produk yang sejenis secara transparan dari aspek harga, rating, rasa, dan skor dampak
          sosialnya.
        </p>
      </div>

    </div>
  </section>

  <!-- EKOSISTEM ROLE OVERVIEW -->
  <section id="ekosistem" class="py-20 border-t border-purple-500/20 bg-purple-950/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

      <div class="text-center max-w-3xl mx-auto space-y-3">
        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-400">Ekosistem Terintegrasi</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-heading">4 Role Utama Dalam Platform</h2>
        <p class="text-xs sm:text-sm text-purple-300/70">Semua aktivitas ekonomi terhubung secara mulus dalam satu
          ekosistem berbasis AI.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- User Role -->
        <div class="p-5 rounded-2xl bg-purple-950/80 border border-purple-500/40 space-y-3 relative overflow-hidden">
          <span
            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/20 text-purple-300 border border-purple-400/30">FITUR
            UTAMA AKTIF</span>
          <h4 class="text-base font-bold text-white font-heading">1. User (Pembeli Impact)</h4>
          <p class="text-xs text-purple-300/70">Katalog produk, AI Shopping Assistant, Gift Recommendation, Comparison, &
            Impact Score.</p>
        </div>

        <!-- Business Account -->
        <div class="p-5 rounded-2xl bg-purple-950/40 border border-purple-500/20 space-y-3">
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-800/30 text-purple-400">EKOSISTEM
            UMKM</span>
          <h4 class="text-base font-bold text-white font-heading">2. Business Account</h4>
          <p class="text-xs text-purple-300/70">Dashboard omzet, AI Product Optimizer, AI Marketing Center, Omnichannel, &
            Cashflow AI.</p>
        </div>

        <!-- Government -->
        <div class="p-5 rounded-2xl bg-purple-950/40 border border-purple-500/20 space-y-3">
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-800/30 text-purple-400">PEMDA &
            DINAS</span>
          <h4 class="text-base font-bold text-white font-heading">3. Government Dashboard</h4>
          <p class="text-xs text-purple-300/70">Economic Dashboard daerah, AI Economic Insight, simulasi kebijakan, &
            event management.</p>
        </div>

        <!-- Super Admin -->
        <div class="p-5 rounded-2xl bg-purple-950/40 border border-purple-500/20 space-y-3">
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-800/30 text-purple-400">KONTROL
            UTAMA</span>
          <h4 class="text-base font-bold text-white font-heading">4. Super Admin</h4>
          <p class="text-xs text-purple-300/70">Platform Dashboard, verifikasi UMKM, AI Engine management, & status
            server.</p>
        </div>

      </div>

      <!-- CTA Box -->
      <div
        class="p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-purple-900 via-indigo-950 to-purple-950 border border-purple-400/30 text-center space-y-5 shadow-2xl">
        <h3 class="text-2xl sm:text-3xl font-extrabold text-white font-heading">Siap Mencoba Pengalaman Belanja Berdampak?
        </h3>
        <p class="text-xs sm:text-sm text-purple-200/80 max-w-xl mx-auto">Masuk sekarang untuk mengakses Dashboard User
          dengan tampilan <strong>Sidebar Layout</strong> interaktif.</p>
        <div class="flex flex-wrap items-center justify-center gap-3">
          <a href="{{ route('login') }}"
            class="px-8 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-xs transition shadow-lg">
            Masuk ke User Portal →
          </a>
        </div>
      </div>

    </div>
  </section>

@endsection