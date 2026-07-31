<!DOCTYPE html>
<html lang="id" class="dark">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Grownesia User Dashboard - Ekosistem Pemberdayaan UMKM Indonesia Berbasis AI">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title x-text="getPageTitle() + ' — Grownesia User Portal'">Katalog Produk — Grownesia AI</title>

  <!-- Google Fonts Harmonization with Business, Admin & Government Layouts -->
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
    class="fixed inset-y-0 left-0 z-40 w-64 glass-card !rounded-none border-r border-primary-400/15 flex flex-col justify-between transition-transform duration-300 lg:translate-x-0"
    :class="sidebarMobileOpen ? 'translate-x-0' : '-translate-x-full'">

    <!-- Sidebar Brand Logo Header -->
    <div class="px-5 py-6 border-b border-primary-400/10">
      <a href="{{ route('landing') }}" class="flex items-center gap-3">
        <span
          class="w-9 h-9 rounded-xl ai-gradient flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-primary-600/40"
          style="font-family: 'Space Grotesk'">G</span>
        <span>
          <span class="block text-lg font-bold tracking-tight text-white leading-none" style="font-family: 'Space Grotesk'">Grownesia</span>
          <span class="block mt-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-primary-300/80">User Portal</span>
        </span>
      </a>
    </div>

    <!-- Sidebar Menu Links -->
    <nav class="flex-1 px-3 py-3 overflow-y-auto space-y-1">
      <p class="section-label">Navigasi Utama</p>
      <div class="space-y-1">
        <a href="/produk" @click.prevent="activeTab = 'katalog'; sidebarMobileOpen = false"
          class="sidebar-link" :class="(activeTab === 'katalog' || activeTab === 'detail') ? 'active' : ''">
          <x-icon name="shopping-bag" class="w-4 h-4"/>
          <span>Katalog Produk UMKM</span>
        </a>

        <a href="/ai-assistant" @click.prevent="activeTab = 'ai-assistant'; sidebarMobileOpen = false"
          class="sidebar-link flex items-center justify-between" :class="activeTab === 'ai-assistant' ? 'active' : ''">
          <div class="flex items-center gap-2.5">
            <x-icon name="sparkles" class="w-4 h-4 text-amber-300 animate-pulse"/>
            <span>AI Personal Shopper</span>
          </div>
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
        </a>

        <a href="/ai-gift" @click.prevent="activeTab = 'ai-gift'; sidebarMobileOpen = false"
          class="sidebar-link" :class="activeTab === 'ai-gift' ? 'active' : ''">
          <x-icon name="bot" class="w-4 h-4"/>
          <span>Rekomendasi Kado AI</span>
        </a>

        <a href="/ai-compare" @click.prevent="activeTab = 'ai-compare'; sidebarMobileOpen = false"
          class="sidebar-link" :class="activeTab === 'ai-compare' ? 'active' : ''">
          <x-icon name="sliders" class="w-4 h-4"/>
          <span>Komparasi Produk AI</span>
        </a>
      </div>

      <p class="section-label mt-4">Belanja & Transaksi</p>
      <div class="space-y-1">
        <a href="/cart" @click.prevent="activeTab = 'cart'; sidebarMobileOpen = false"
          class="sidebar-link flex items-center justify-between" :class="activeTab === 'cart' ? 'active' : ''">
          <div class="flex items-center gap-2.5">
            <x-icon name="package" class="w-4 h-4"/>
            <span>Keranjang Belanja</span>
          </div>
          <span x-show="cartTotalCount > 0" x-text="cartTotalCount"
            class="bg-fuchsia-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow"></span>
        </a>

        <a href="/favorites" @click.prevent="activeTab = 'favorites'; sidebarMobileOpen = false"
          class="sidebar-link flex items-center justify-between" :class="activeTab === 'favorites' ? 'active' : ''">
          <div class="flex items-center gap-2.5">
            <x-icon name="globe" class="w-4 h-4 text-rose-400"/>
            <span>Produk Favorit</span>
          </div>
          <span x-show="favorites.length > 0" x-text="favorites.length"
            class="bg-red-500/20 text-red-300 text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-red-500/30"></span>
        </a>

        <a href="/orders" @click.prevent="activeTab = 'orders'; sidebarMobileOpen = false"
          class="sidebar-link flex items-center justify-between" :class="(activeTab === 'orders' || activeTab === 'write-review') ? 'active' : ''">
          <div class="flex items-center gap-2.5">
            <x-icon name="file-text" class="w-4 h-4 text-emerald-400"/>
            <span>Riwayat & Ulasan</span>
          </div>
          <span x-text="ordersHistory.length"
            class="bg-emerald-500/20 text-emerald-300 text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-emerald-500/30"></span>
        </a>

        <a href="/tracking" @click.prevent="activeTab = 'tracking'; sidebarMobileOpen = false"
          class="sidebar-link flex items-center justify-between" :class="activeTab === 'tracking' ? 'active' : ''">
          <div class="flex items-center gap-2.5">
            <x-icon name="truck" class="w-4 h-4 text-blue-400"/>
            <span>Lacak Pengiriman</span>
          </div>
          <span class="bg-blue-500/20 text-blue-300 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-500/30">Live</span>
        </a>
      </div>

      <p class="section-label mt-4">Pengaturan Akun</p>
      <div class="space-y-1">
        <a href="/profile" @click.prevent="activeTab = 'profile'; sidebarMobileOpen = false"
          class="sidebar-link" :class="activeTab === 'profile' ? 'active' : ''">
          <x-icon name="dashboard" class="w-4 h-4"/>
          <span>Profil & Pengaturan</span>
        </a>
      </div>
    </nav>

    <!-- Sidebar User Profile Footer (Consistent with Business, Admin & Government) -->
    <div class="px-4 py-4 border-t border-primary-400/10">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 shrink-0 rounded-full ai-gradient flex items-center justify-center text-white font-bold text-sm shadow">
          {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </div>
        <div class="min-w-0 flex-1">
          <p class="text-xs font-bold text-white truncate" x-text="userProfile.name || '{{ Auth::user()->name ?? 'Pembeli' }}'"></p>
          <p class="text-[10px] text-slate-500 truncate" x-text="userProfile.email || '{{ Auth::user()->email ?? 'user@grownesia.id' }}'"></p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" title="Keluar dari Akun" class="p-2 rounded-lg text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-colors">
            <x-icon name="log-out" class="w-4 h-4"/>
          </button>
        </form>
      </div>
    </div>
  </aside>

  <!-- Mobile sidebar overlay -->
  <div x-show="sidebarMobileOpen" x-cloak @click="sidebarMobileOpen = false"
    class="fixed inset-0 z-30 bg-black/60 lg:hidden"></div>

  <!-- RIGHT MAIN WORKSPACE AREA -->
  <div class="lg:pl-64 flex-1 min-w-0 flex flex-col min-h-screen">

    <!-- TOP HEADER FOR DASHBOARD WORKSPACE (Consistent with Business & Government Layouts) -->
    <header
      class="sticky top-0 z-20 glass-card !rounded-none border-b border-primary-400/10 px-6 py-4 flex items-center justify-between backdrop-blur-xl">
      <div class="flex items-center gap-3">
        <button class="lg:hidden text-slate-300 p-1" @click="sidebarMobileOpen = true">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <div>
          <h1 class="text-lg font-bold text-white leading-tight font-heading" x-text="getPageTitle()">Katalog Produk</h1>
          <p class="text-[11px] text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
      </div>

      <!-- Header Tools & Role Switcher -->
      <div class="flex items-center gap-3 relative">

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
              <button @click="markAllNotifRead()" class="text-[10px] text-purple-300/80 hover:text-white">Tandai Dibaca</button>
            </div>
            <div class="space-y-2 max-h-60 overflow-y-auto text-xs">
              <template x-for="n in notifications" :key="'notif-'+n.id">
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

        <!-- Quick Cart Shortcut Button -->
        <button @click="activeTab = 'cart'"
          :class="activeTab === 'cart' ? 'border-purple-400 bg-purple-900' : 'bg-purple-950/70 border-purple-500/30 hover:border-purple-400'"
          class="relative p-2.5 rounded-xl border text-purple-200 transition" title="Keranjang Belanja">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          <span x-show="cartTotalCount > 0" x-text="cartTotalCount"
            class="absolute -top-1 -right-1 bg-fuchsia-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center"></span>
        </button>

        <!-- Verified Buyer Badge -->
        <span class="hidden sm:inline-flex badge-pill bg-emerald-500/12 text-emerald-400 border-emerald-500/35">
          <x-icon name="badge-check" class="w-3.5 h-3.5"/> Pembeli Terverifikasi
        </span>

        <!-- Role Switcher Component for Unified Ecosystem Switching -->
        <x-role-switcher />
      </div>
    </header>

    <!-- DASHBOARD CONTENT AREA -->
    <main class="flex-1 px-6 py-6">
      @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mb-4 glass-card border-emerald-500/40 px-4 py-3 text-sm text-emerald-400 flex items-center justify-between">
          <span>{{ session('success') }}</span>
          <button @click="show = false" class="text-slate-500 hover:text-white">✕</button>
        </div>
      @endif
      @if (session('error'))
        <div x-data="{ show: true }" x-show="show"
             class="mb-4 glass-card border-rose-500/40 px-4 py-3 text-sm text-rose-400 flex items-center justify-between">
          <span>{{ session('error') }}</span>
          <button @click="show = false" class="text-slate-500 hover:text-white">✕</button>
        </div>
      @endif

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
      userImpact: @json($userImpact ?? ['totalJobs' => 8, 'villagesHelped' => 3, 'craftswomenHelped' => 5]),
      favorites: @json($userFavorites ?? [])
    };
  </script>
  <script src="{{ asset('js/user-dashboard.js') }}"></script>

  <!-- FLOATING AI ASSISTANT OVERLAY WIDGET -->
  <div class="fixed bottom-6 right-6 z-50">
    <button @click="showFloatingAiWidget = !showFloatingAiWidget"
      class="relative px-4 py-3 rounded-full bg-gradient-to-r from-purple-600 via-fuchsia-600 to-pink-600 text-white font-bold text-xs shadow-2xl ai-active-pulse hover:scale-105 transition flex items-center gap-2 border border-purple-300/40">
      <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
      <svg class="w-5 h-5 text-amber-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg>
      <span class="font-bold tracking-tight">Grownesia AI Assistant</span>
    </button>

    <div x-show="showFloatingAiWidget" x-cloak @click.away="showFloatingAiWidget = false"
      class="absolute bottom-16 right-0 w-80 sm:w-96 rounded-3xl bg-slate-950/95 border border-purple-400/40 shadow-2xl backdrop-blur-2xl overflow-hidden flex flex-col justify-between"
      style="box-shadow: 0 20px 50px rgba(124, 58, 237, 0.5);">

      <div class="p-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white flex items-center justify-between shadow-md">
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