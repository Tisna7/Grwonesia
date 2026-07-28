<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Grownesia - Masuk & Daftar Akun Ekosistem UMKM Berbasis AI">
  <title>@yield('title', 'Autentikasi - Grownesia Platform')</title>

  <!-- Vite Assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
  class="bg-[#080415] text-slate-100 min-h-screen font-sans flex items-center justify-center p-4 sm:p-6 md:p-10 selection:bg-purple-500 selection:text-white relative overflow-x-hidden">

  <!-- FLOATING BACK BUTTON TO LANDING PAGE -->
  <a href="{{ route('landing') }}"
    class="fixed top-6 left-6 z-50 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-purple-950/80 hover:bg-purple-900 border border-purple-500/40 text-purple-200 hover:text-white text-xs font-semibold backdrop-blur-md transition shadow-xl group">
    <svg class="w-4 h-4 text-purple-400 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor"
      viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <span>Kembali ke Beranda</span>
  </a>

  <!-- Ambient Glowing Orbs Background -->
  <div
    class="fixed top-1/4 left-1/4 w-[500px] h-[500px] bg-purple-600/15 rounded-full blur-[140px] pointer-events-none">
  </div>
  <div
    class="fixed bottom-10 right-10 w-[400px] h-[400px] bg-fuchsia-600/15 rounded-full blur-[120px] pointer-events-none">
  </div>

  <!-- MAIN SPLIT SCREEN CONTAINER -->
  <div
    class="w-full max-w-5xl bg-purple-950/80 backdrop-blur-2xl border border-purple-500/30 rounded-[32px] shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px] relative z-10 my-8">

    <!-- LEFT PANEL: VISUAL SHOWCASE -->
    <div
      class="lg:col-span-5 bg-gradient-to-br from-purple-900/90 via-purple-950 to-indigo-950 p-8 lg:p-10 flex flex-col justify-between relative overflow-hidden border-b lg:border-b-0 lg:border-r border-purple-500/20">
      <!-- Curved background SVG shape -->
      <div
        class="absolute -top-20 -left-20 w-64 h-64 bg-purple-500/20 rounded-full filter blur-3xl pointer-events-none">
      </div>

      <!-- Top Brand -->
      <div class="flex items-center gap-3 relative z-10">
        <a href="{{ route('landing') }}" class="flex items-center gap-2.5 group">
          <div
            class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-700 via-purple-600 to-fuchsia-500 p-0.5 shadow-lg">
            <div class="w-full h-full bg-purple-950 rounded-[10px] flex items-center justify-center">
              <svg class="w-5 h-5 text-purple-400 group-hover:scale-110 transition" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                  d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
          </div>
          <div>
            <span class="text-xl font-extrabold tracking-tight font-heading text-white">Grownesia</span>
            <span class="block text-[10px] text-purple-300/70 font-medium -mt-1">AI & Impact Platform</span>
          </div>
        </a>
      </div>

      <!-- Center Platform Mockup Card -->
      <div class="my-8 relative z-10">
        <div class="p-4 rounded-2xl bg-purple-900/40 border border-purple-400/30 backdrop-blur-md shadow-xl space-y-3">
          <div class="flex items-center justify-between border-b border-purple-500/20 pb-2">
            <div class="flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
              <span class="text-[11px] font-bold text-white">AI Personal Shopper</span>
            </div>
            <span class="text-[9px] px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-semibold">Verified
              Impact</span>
          </div>

          <div class="p-3 rounded-xl bg-purple-950/90 border border-purple-500/30 flex items-center gap-3">
            <img src="/images/products/kopi_gula_aren.webp" loading="lazy"
              class="w-12 h-12 rounded-lg object-cover border border-purple-400/30">
            <div class="min-w-0">
              <h5 class="text-xs font-bold text-white truncate">Kopi Gula Aren Nusantara</h5>
              <p class="text-[10px] text-emerald-400 font-semibold">+2 Petani Terbantu</p>
              <span class="text-xs font-extrabold text-purple-300">Rp 45.000</span>
            </div>
          </div>

          <div
            class="p-2.5 rounded-xl bg-purple-800/30 border border-purple-500/20 text-[10px] text-purple-200/90 flex items-center gap-2">
            <span>💬</span>
            <span class="italic">"AI meracik rekomendasi hampers sesuai anggaran Anda."</span>
          </div>
        </div>
      </div>

      <!-- Bottom Inspirational Text -->
      <div class="space-y-2 relative z-10">
        <h3 class="text-lg font-bold text-white font-heading leading-snug">
          @yield('visual_heading', 'Kelola Dampak & Belanja Produk UMKM dalam Satu Tempat')
        </h3>
        <p class="text-xs text-purple-200/80 leading-relaxed">
          @yield('visual_subtext', 'Bergabunglah bersama ribuan pembeli dan pelaku usaha lokal untuk memajukan ekonomi Indonesia.')
        </p>
        <div class="pt-2 flex items-center gap-3 text-[11px] text-purple-300/70">
          <span>✓ Terhubung ke SQLite Database</span>
          <span>✓ Keamanan Terenkripsi</span>
        </div>
      </div>
    </div>

    <!-- RIGHT PANEL: INTERACTIVE FORM AREA -->
    <div class="lg:col-span-7 p-8 lg:p-12 flex flex-col justify-center bg-purple-card relative">
      @yield('auth_form')
    </div>

  </div>

</body>

</html>