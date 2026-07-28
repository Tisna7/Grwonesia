@extends('layouts.user')

@section('content')

  <div class="space-y-8">

    <!-- TOP STATS BANNER (SHOWN ON HOME/KATALOG TAB) (DESAIN.MD SPEC 1.1 & 6.1) -->
    <div x-show="activeTab === 'katalog'"
      class="p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl glow-purple relative overflow-hidden">
      <div class="absolute -top-24 -right-24 w-72 h-72 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center relative z-10">
        <div class="lg:col-span-8 space-y-3">
          <div
            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/20 border border-purple-400/30 text-purple-300 text-xs font-semibold">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span>AI Shopping Assistant Active</span>
          </div>

          <h1 class="text-2xl sm:text-4xl font-extrabold text-white font-heading tracking-tight">
            Belanja Produk UMKM <span class="text-gradient-purple">Terbaik AI</span>
          </h1>

          <p class="text-xs sm:text-sm text-purple-200/80 leading-relaxed max-w-2xl">
            Jelajahi produk UMKM Indonesia pilihan berbasis AI, dapatkan rekomendasi hampers personal, baca ulasan pembeli
            terverifikasi, dan nikmati belanja yang aman & nyaman.
          </p>

          <div class="flex flex-wrap items-center gap-3 pt-2">
            <button @click="showFloatingAiWidget = true" class="btn-primary-purple text-xs shadow-lg">
              <svg class="w-4 h-4 text-amber-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
              <span>Buka AI Shopping Assistant</span>
            </button>
            <button @click="activeTab = 'orders'"
              class="px-4 py-2.5 rounded-xl bg-purple-950/80 hover:bg-purple-900 border border-purple-500/30 text-purple-200 font-bold text-xs transition flex items-center gap-2">
              <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <span>Beri Ulasan Pesanan</span>
            </button>
          </div>
        </div>

        <!-- Quick Shopping Summary Widget -->
        <div
          class="lg:col-span-4 p-5 rounded-2xl bg-gradient-to-br from-purple-950/90 via-purple-900/60 to-indigo-950/90 border border-purple-500/30 space-y-3 text-center shadow-xl">
          <div
            class="inline-flex items-center gap-1.5 text-[11px] font-bold text-purple-300 px-3 py-1 rounded-full bg-purple-900/60 border border-purple-500/30">
            <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span>Status Transaksi Anda</span>
          </div>
          <div class="flex items-center justify-around py-1">
            <div>
              <div class="text-2xl font-extrabold text-white font-heading" x-text="ordersHistory.length"></div>
              <div class="text-[10px] text-purple-300 font-semibold">Total Pesanan</div>
            </div>
            <div class="w-px h-8 bg-purple-500/30"></div>
            <div>
              <div class="text-2xl font-extrabold text-white font-heading" x-text="favorites.length"></div>
              <div class="text-[10px] text-purple-300 font-semibold">Produk Favorit</div>
            </div>
          </div>
          <button @click="activeTab = 'tracking'"
            class="w-full py-2 rounded-xl bg-purple-900/80 hover:bg-purple-800 border border-purple-400/30 text-purple-200 text-xs font-bold flex items-center justify-center gap-1.5 transition">
            <span>Lacak Pengiriman Aktif</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    @include('user.catalog.katalog')
    @include('user.catalog.detail')
    @include('user.cart.cart')
    @include('user.cart.success-impact')
    @include('user.ai.ai-assistant')
    @include('user.catalog.favorites')
    @include('user.orders.orders')
    @include('user.orders.write-review')
    @include('user.profile')
    @include('user.ai.ai-gift')
    @include('user.ai.ai-compare')
    @include('user.orders.tracking')

    <!-- COPY TOAST NOTIFICATION -->
    <div x-show="copyToast" x-cloak
      class="fixed bottom-6 right-6 z-50 px-4 py-3 rounded-2xl bg-purple-900 border border-purple-400 text-white text-xs font-bold shadow-2xl flex items-center gap-2 animate-bounce">
      <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
      </svg>
      <span>Nomor Resi Berhasil Disalin ke Clipboard!</span>
    </div>

  </div>

@endsection