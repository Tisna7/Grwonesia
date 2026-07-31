<!DOCTYPE html>
<html lang="id" class="dark">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Grownesia User Dashboard - Ekosistem Pemberdayaan UMKM Indonesia Berbasis AI">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title x-text="getPageTitle() + ' — Grownesia AI'">Katalog Produk — Grownesia AI</title>

  <!-- Google Fonts Harmonization with Business Layout -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;600&display=swap"
    rel="stylesheet">

  <!-- Vite Assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen antialiased text-slate-100 flex overflow-x-hidden" x-data="grownesiaUserDashboard()"
  x-init="initDashboard()">
  <div class="ambient-glow"></div>

  <!-- LEFT SIDEBAR NAVIGATION (LOGGED-IN USER LAYOUT) -->
  <aside
    class="fixed inset-y-0 left-0 z-40 w-64 glass-card !rounded-none border-r border-primary-400/15 h-screen flex flex-col justify-between shrink-0 transition-transform duration-300 md:translate-x-0"
    :class="sidebarMobileOpen ? 'translate-x-0' : '-translate-x-full'">

    <!-- Sidebar Brand & User Profile Card -->
    <div class="p-5 border-b border-primary-400/10 space-y-4">
      <div class="flex items-center justify-between">
        <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
          <span
            class="w-9 h-9 rounded-xl ai-gradient flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-purple-600/40"
            style="font-family: 'Space Grotesk'">G</span>
          <div>
            <span class="block text-lg font-bold tracking-tight text-white leading-none font-heading">Grownesia</span>
            <span class="block mt-1 text-[10px] font-bold uppercase tracking-[0.16em] text-purple-300/80">Portal
              Pembeli</span>
          </div>
        </a>
      </div>

      <!-- Profile Info Widget Card -->
      <button @click="activeTab = 'profile'; sidebarMobileOpen = false"
        :class="activeTab === 'profile' ? 'bg-purple-800/60 border-purple-400/50 shadow-md' : 'bg-purple-950/60 border-purple-500/20 hover:bg-purple-900/40'"
        class="w-full p-3 rounded-2xl border text-xs flex items-center justify-between transition text-left group">
        <div class="flex items-center gap-2.5 min-w-0">
          <div
            class="w-7 h-7 rounded-xl bg-gradient-to-tr from-purple-600 to-pink-600 flex items-center justify-center text-white font-extrabold text-xs shrink-0 shadow">
            {{ strtoupper(substr(Auth::user()->name ?? 'B', 0, 1)) }}
          </div>
          <div class="min-w-0">
            <span class="block text-white font-bold truncate text-[11px]" x-text="userProfile.name"></span>
            <span class="block text-[9px] text-purple-300 font-semibold truncate">Akun Terverifikasi</span>
          </div>
        </div>
        <div class="p-1 rounded-lg bg-purple-900/60 text-purple-300 group-hover:text-white transition shrink-0">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </div>
      </button>
    </div>

    <!-- Sidebar Menu Links -->
    <div class="flex-1 py-5 px-3.5 space-y-1 overflow-y-auto text-xs font-semibold">
      <!-- CATEGORY 1: NAVIGASI UTAMA -->
      <div class="text-[10px] uppercase font-extrabold tracking-wider text-purple-400/70 px-3 pt-1 pb-2">Navigasi Utama
      </div>

      <a href="/produk" @click.prevent="activeTab = 'katalog'; sidebarMobileOpen = false"
        :class="activeTab === 'katalog' || activeTab === 'detail' ? 'bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg shadow-purple-900/40 border border-purple-400/30' : 'text-slate-300 hover:bg-purple-900/40 hover:text-white'"
        class="w-full px-3.5 py-2.5 rounded-xl transition flex items-center gap-3">
        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <span>Katalog Produk UMKM</span>
      </a>

      <a href="/ai-assistant" @click.prevent="activeTab = 'ai-assistant'; sidebarMobileOpen = false"
        :class="activeTab === 'ai-assistant' ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg shadow-purple-900/40 border border-purple-400/30' : 'text-slate-300 hover:bg-purple-900/40 hover:text-white'"
        class="w-full px-3.5 py-2.5 rounded-xl transition flex items-center justify-between">
        <div class="flex items-center gap-3">
          <svg class="w-4 h-4 text-amber-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
          <span>AI Personal Shopper</span>
        </div>
        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
      </a>

      <a href="/ai-gift" @click.prevent="activeTab = 'ai-gift'; sidebarMobileOpen = false"
        :class="activeTab === 'ai-gift' ? 'bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg shadow-purple-900/40 border border-purple-400/30' : 'text-slate-300 hover:bg-purple-900/40 hover:text-white'"
        class="w-full px-3.5 py-2.5 rounded-xl transition flex items-center gap-3">
        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2z" />
        </svg>
        <span>Rekomendasi Kado AI</span>
      </a>

      <a href="/ai-compare" @click.prevent="activeTab = 'ai-compare'; sidebarMobileOpen = false"
        :class="activeTab === 'ai-compare' ? 'bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg shadow-purple-900/40 border border-purple-400/30' : 'text-slate-300 hover:bg-purple-900/40 hover:text-white'"
        class="w-full px-3.5 py-2.5 rounded-xl transition flex items-center gap-3">
        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        <span>Komparasi Produk AI</span>
      </a>

      <!-- CATEGORY 2: BELANJA & TRANSAKSI -->
      <div class="text-[10px] uppercase font-extrabold tracking-wider text-purple-400/70 px-3 pt-4 pb-2">Belanja &
        Transaksi</div>

      <a href="/cart" @click.prevent="activeTab = 'cart'; sidebarMobileOpen = false"
        :class="activeTab === 'cart' ? 'bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg shadow-purple-900/40 border border-purple-400/30' : 'text-slate-300 hover:bg-purple-900/40 hover:text-white'"
        class="w-full px-3.5 py-2.5 rounded-xl transition flex items-center justify-between">
        <div class="flex items-center gap-3">
          <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z" />
          </svg>
          <span>Keranjang Belanja</span>
        </div>
        <span x-show="cartTotalCount > 0" x-text="cartTotalCount"
          class="bg-fuchsia-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow"></span>
      </a>

      <a href="/favorites" @click.prevent="activeTab = 'favorites'; sidebarMobileOpen = false"
        :class="activeTab === 'favorites' ? 'bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg shadow-purple-900/40 border border-purple-400/30' : 'text-slate-300 hover:bg-purple-900/40 hover:text-white'"
        class="w-full px-3.5 py-2.5 rounded-xl transition flex items-center justify-between">
        <div class="flex items-center gap-3">
          <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
          <span>Produk Favorit</span>
        </div>
        <span x-show="favorites.length > 0" x-text="favorites.length"
          class="bg-red-500/20 text-red-300 text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-red-500/30"></span>
      </a>

      <a href="/orders" @click.prevent="activeTab = 'orders'; sidebarMobileOpen = false"
        :class="activeTab === 'orders' || activeTab === 'write-review' ? 'bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg shadow-purple-900/40 border border-purple-400/30' : 'text-slate-300 hover:bg-purple-900/40 hover:text-white'"
        class="w-full px-3.5 py-2.5 rounded-xl transition flex items-center justify-between">
        <div class="flex items-center gap-3">
          <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <span>Riwayat & Ulasan</span>
        </div>
        <span x-text="ordersHistory.length"
          class="bg-emerald-500/20 text-emerald-300 text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-emerald-500/30"></span>
      </a>

      <a href="/tracking" @click.prevent="activeTab = 'tracking'; sidebarMobileOpen = false"
        :class="activeTab === 'tracking' ? 'bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg shadow-purple-900/40 border border-purple-400/30' : 'text-slate-300 hover:bg-purple-900/40 hover:text-white'"
        class="w-full px-3.5 py-2.5 rounded-xl transition flex items-center justify-between">
        <div class="flex items-center gap-3">
          <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Lacak Pengiriman</span>
        </div>
        <span
          class="bg-blue-500/20 text-blue-300 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-500/30">Live</span>
      </a>

      <!-- CATEGORY 3: PENGATURAN AKUN -->
      <div class="text-[10px] uppercase font-extrabold tracking-wider text-purple-400/70 px-3 pt-4 pb-2">Pengaturan Akun
      </div>

      <a href="/profile" @click.prevent="activeTab = 'profile'; sidebarMobileOpen = false"
        :class="activeTab === 'profile' ? 'bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg shadow-purple-900/40 border border-purple-400/30' : 'text-slate-300 hover:bg-purple-900/40 hover:text-white'"
        class="w-full px-3.5 py-2.5 rounded-xl transition flex items-center gap-3">
        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span>Profil & Pengaturan</span>
      </a>
    </div>

    <!-- Sidebar Footer & Logout Button -->
    <div class="p-4 border-t border-purple-500/15 bg-purple-950/80">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
          class="w-full py-2.5 px-3 rounded-xl bg-purple-900/40 hover:bg-red-950/70 border border-purple-500/20 hover:border-red-500/40 text-purple-300 hover:text-red-200 text-xs font-bold transition flex items-center justify-center gap-2 group">
          <svg class="w-4 h-4 text-purple-400 group-hover:text-red-300 transition" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span>Keluar dari Akun</span>
        </button>
      </form>
    </div>
  </aside>

  <!-- Mobile sidebar overlay -->
  <div x-show="sidebarMobileOpen" x-cloak @click="sidebarMobileOpen = false"
    class="fixed inset-0 z-30 bg-black/60 md:hidden"></div>

  <!-- RIGHT MAIN WORKSPACE AREA -->
  <div class="md:pl-64 flex-1 min-w-0 flex flex-col min-h-screen">

    <!-- TOP HEADER FOR DASHBOARD WORKSPACE -->
    <header
      class="sticky top-0 z-30 glass-card !rounded-none border-b border-primary-400/10 px-6 py-4 flex items-center justify-between backdrop-blur-xl">
      <div class="flex items-center gap-4">
        <button class="md:hidden text-slate-300 p-1" @click="sidebarMobileOpen = true">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <h2 class="text-sm sm:text-lg font-bold text-white font-heading truncate" x-text="getPageTitle()">
          Katalog Produk
        </h2>
        <span
          class="hidden lg:inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-500/20 text-purple-300 border border-purple-400/30">
          Pembeli Terverifikasi
        </span>
      </div>

      <!-- Header Quick Tools & Notifications & Profile -->
      <div class="flex items-center gap-3 relative">

        <!-- Role Switcher Component for Unified Ecosystem Switching -->
        <x-role-switcher />

        <!-- Business Studio Role Switcher Button -->
        <a href="{{ route('business.dashboard') }}"
          class="px-3 py-2 rounded-xl bg-purple-900/60 hover:bg-purple-800 border border-primary-400/30 text-purple-200 text-xs font-semibold flex items-center gap-1.5 transition shadow-md">
          <svg class="w-4 h-4 text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          <span class="hidden sm:inline">Business Studio</span>
        </a>

        <!-- NOTIFICATION BELL WITH DROPDOWN -->
        <div class="relative" x-data="{ openNotif: false }">
          <button @click="openNotif = !openNotif"
            class="relative p-2.5 rounded-xl bg-purple-950/70 border border-purple-500/30 hover:border-purple-400 text-purple-200 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span x-show="unreadNotifCount > 0" x-text="unreadNotifCount"
              class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center animate-bounce"></span>
          </button>

          <div x-show="openNotif" @click.away="openNotif = false"
            class="absolute right-0 mt-2 w-80 bg-purple-card border border-purple-500/30 rounded-2xl shadow-2xl p-4 z-50 space-y-3"
            style="display: none;">
            <div class="flex items-center justify-between border-b border-purple-500/20 pb-2">
              <h4 class="text-xs font-bold text-white">Notifikasi (Pusat Informasi)</h4>
              <button @click="markAllNotifRead()" class="text-[10px] text-purple-300/80 hover:text-white">Tandai
                Dibaca</button>
            </div>
            <div class="space-y-2 max-h-60 overflow-y-auto text-xs">
              <template x-for="n in notifications" :key="n.id">
                <div class="p-2.5 rounded-xl border border-purple-500/20 bg-purple-950/60 space-y-1">
                  <div class="flex items-center justify-between">
                    <span class="font-bold text-purple-200" x-text="n.title"></span>
                    <span class="text-[9px] text-purple-400/60" x-text="n.time"></span>
                  </div>
                  <p class="text-[11px] text-purple-300/70" x-text="n.desc"></p>
                </div>
              </template>
            </div>
          </div>
        </div>

        <button @click="activeTab = 'ai-assistant'"
          :class="activeTab === 'ai-assistant' ? 'bg-purple-500 text-white' : 'bg-purple-600 hover:bg-purple-500 text-white'"
          class="px-3.5 py-2 rounded-xl text-xs font-semibold shadow-md flex items-center gap-1.5 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
          <span>AI Assistant</span>
        </button>

        <button @click="activeTab = 'cart'"
          :class="activeTab === 'cart' ? 'border-purple-400 bg-purple-900' : 'bg-purple-950/70 border-purple-500/30 hover:border-purple-400'"
          class="relative p-2.5 rounded-xl border text-purple-200 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          <span x-show="cartTotalCount > 0" x-text="cartTotalCount"
            class="absolute -top-1 -right-1 bg-fuchsia-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center"></span>
        </button>

        <button @click="activeTab = 'profile'"
          class="w-9 h-9 rounded-xl bg-purple-800 border border-purple-400/40 flex items-center justify-center text-xs font-bold text-white hover:bg-purple-700 transition">
          {{ strtoupper(substr(Auth::user()->name ?? 'Budi', 0, 1)) }}
        </button>
      </div>
    </header>

    <!-- DASHBOARD CONTENT AREA -->
    <main class="flex-1 p-6 lg:p-8">
      @yield('content')
    </main>
  </div>

  <script>
    window.dashboardInitialData = {
      products: @json($products ?? []),
      activeTab: @json($activeTab ?? 'katalog'),
      initialProductId: @json($initialProductId ?? null),
      userProfile: @json($userProfile ?? []),
      reviews: @json($reviews ?? []),
      ordersHistory: @json($ordersHistory ?? []),
      notifications: @json($notifications ?? []),
      unreadNotifCount: @json($unreadNotifCount ?? 0),
      userImpact: @json($userImpact ?? ['totalJobs' => 8, 'villagesHelped' => 3, 'craftswomenHelped' => 5])
    };
  </script>
  <script src="{{ asset('js/user-dashboard.js') }}"></script>
  <!-- FLOATING AI ASSISTANT OVERLAY WIDGET (DESAIN.MD SPEC 4.1) -->
  <div class="fixed bottom-6 right-6 z-50">
    <!-- Floating Trigger Button with AI Pulsing Glow -->
    <button @click="showFloatingAiWidget = !showFloatingAiWidget"
      class="relative px-4 py-3 rounded-full bg-gradient-to-r from-purple-600 via-fuchsia-600 to-pink-600 text-white font-bold text-xs shadow-2xl ai-active-pulse hover:scale-105 transition flex items-center gap-2 border border-purple-300/40">
      <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
      <svg class="w-5 h-5 text-amber-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg>
      <span class="font-bold tracking-tight">Grownesia AI Assistant</span>
    </button>

    <!-- Floating Drawer Overlay Container -->
    <div x-show="showFloatingAiWidget" x-cloak @click.away="showFloatingAiWidget = false"
      class="absolute bottom-16 right-0 w-80 sm:w-96 rounded-3xl bg-slate-950/95 border border-purple-400/40 shadow-2xl backdrop-blur-2xl overflow-hidden flex flex-col justify-between"
      style="box-shadow: 0 20px 50px rgba(124, 58, 237, 0.5);">

      <!-- Header Widget (linear-gradient(135deg, #7C3AED 0%, #EC4899 100%)) -->
      <div
        class="p-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white flex items-center justify-between shadow-md">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
            <svg class="w-4 h-4 text-amber-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <div>
            <h4 class="text-xs font-extrabold tracking-tight font-heading">Grownesia AI Shopping Assistant</h4>
            <span class="text-[10px] text-purple-100/90 font-medium">Empowered Local Economy with AI</span>
          </div>
        </div>
        <button @click="showFloatingAiWidget = false" class="text-white/80 hover:text-white p-1 font-bold">✕</button>
      </div>

      <!-- Quick Prompt Chips (Desain.md Section 4.1) -->
      <div class="p-3 bg-purple-950/60 border-b border-purple-500/20 space-y-1.5">
        <span class="text-[10px] text-purple-300/80 font-bold uppercase tracking-wider block">Quick Prompts:</span>
        <div class="flex flex-wrap gap-1.5">
          <button @click="sendAiQuery('Saya ingin hadiah untuk ibu umur 50 tahun.')"
            class="px-2.5 py-1 rounded-full bg-purple-900/80 hover:bg-purple-800 border border-purple-400/30 text-purple-200 text-[11px] font-semibold transition">
            🎁 Hadiah Ibu 50th
          </button>
          <button @click="sendAiQuery('Saya punya budget Rp300.000 untuk hampers.')"
            class="px-2.5 py-1 rounded-full bg-purple-900/80 hover:bg-purple-800 border border-purple-400/30 text-purple-200 text-[11px] font-semibold transition">
            💰 Budget Rp300.000
          </button>
          <button @click="sendAiQuery('Kopi gula aren atau kopi klepon mana yang lebih disukai?')"
            class="px-2.5 py-1 rounded-full bg-purple-900/80 hover:bg-purple-800 border border-purple-400/30 text-purple-200 text-[11px] font-semibold transition">
            ⚖️ Komparasi Kopi
          </button>
        </div>
      </div>

      <!-- Bubble Chat Content Area -->
      <div class="p-4 space-y-3 max-h-72 overflow-y-auto text-xs">
        <template x-for="(msg, idx) in chatMessages" :key="'fmsg-'+idx">
          <div :class="msg.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
            <div
              :class="msg.sender === 'user' ? 'bg-[#5B21B6] text-white rounded-2xl rounded-tr-none p-3 max-w-[85%]' : 'bg-purple-950/80 border border-purple-400/30 text-purple-100 rounded-2xl rounded-tl-none p-3 max-w-[90%] space-y-2'"
              style="box-shadow: inset 0 1px 0 rgba(255,255,255,0.05)">
              <div x-html="formatAiText(msg.text)" class="leading-relaxed"></div>
              <template x-if="msg.products && msg.products.length">
                <div class="pt-2 space-y-2 border-t border-purple-500/20">
                  <template x-for="p in msg.products" :key="'fp-'+p.id">
                    <div
                      class="p-2 rounded-xl bg-purple-900/50 border border-purple-500/30 flex items-center justify-between gap-2">
                      <div class="flex items-center gap-2 min-w-0">
                        <img :src="p.image" loading="lazy"
                          class="w-9 h-9 rounded-lg object-cover border border-purple-400/30">
                        <div class="min-w-0">
                          <p class="font-bold text-white truncate text-[11px]" x-text="p.name"></p>
                          <p class="text-purple-300 text-[10px]" x-text="formatRupiah(p.price)"></p>
                        </div>
                      </div>
                      <button @click="addToCart(p)"
                        class="px-2.5 py-1 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-bold text-[10px] shrink-0">
                        + Beli
                      </button>
                    </div>
                  </template>
                  <button @click="addBundleToCart()"
                    class="w-full py-1.5 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold text-[11px] shadow-md hover:opacity-90 transition">
                    Tambahkan Semua Paket ke Keranjang
                  </button>
                </div>
              </template>
            </div>
          </div>
        </template>

        <!-- Animated Typing Indicator when AI is processing -->
        <template x-if="aiIsTyping">
          <div class="flex justify-start">
            <div
              class="bg-purple-950/80 border border-purple-400/30 text-purple-200 rounded-2xl rounded-tl-none p-3 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-purple-400 animate-bounce"></span>
              <span class="w-2 h-2 rounded-full bg-fuchsia-400 animate-bounce" style="animation-delay: 0.15s"></span>
              <span class="w-2 h-2 rounded-full bg-pink-400 animate-bounce" style="animation-delay: 0.3s"></span>
              <span class="text-[11px] text-purple-300 font-semibold ml-1.5">Grownesia AI sedang berpikir...</span>
            </div>
          </div>
        </template>
      </div>

      <!-- Input Form -->
      <form @submit.prevent="sendAiQuery(customChatInput)"
        class="p-3 bg-purple-950/90 border-t border-purple-500/20 flex gap-2">
        <input type="text" x-model="customChatInput" placeholder="Tanyakan rekomendasi AI..."
          class="flex-1 px-3 py-2 rounded-xl bg-slate-900 border border-purple-400/30 text-xs text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">
        <button type="submit"
          class="px-3.5 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-md">Kirim</button>
      </form>
    </div>
  </div>
</body>

</html>