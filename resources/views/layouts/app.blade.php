<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Grownesia - Platform Ekosistem UMKM Indonesia Berbasis AI & Impact-Driven Shopping">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Grownesia - Pemberdayaan UMKM Indonesia Berbasis AI</title>

  <!-- Vite Assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-purple-dark text-slate-100 min-h-screen font-sans selection:bg-purple-500 selection:text-white"
  x-data="grownesiaApp()" x-init="initApp()">

  <!-- Role Switcher Banner (Top Notification Bar) -->
  <div
    class="bg-gradient-to-r from-purple-900 via-indigo-900 to-purple-900 border-b border-purple-500/20 py-2.5 px-4 text-xs font-medium">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
      <div class="flex items-center gap-2">
        <span
          class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-purple-500/20 text-purple-300 border border-purple-400/30">
          <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-ping mr-1"></span>
          ROLE USER (AKTIF)
        </span>
        <span class="text-purple-200/80">Selamat datang di Ekosistem Digital <strong>Grownesia</strong></span>
      </div>

      <!-- Quick Role Switcher Navigation Buttons -->
      <div class="flex items-center gap-2 text-xs">
        <span class="text-purple-300/60 hidden md:inline">Pindah Role Mode:</span>
        <a href="{{ route('switch-role', 'business') }}"
          class="px-2.5 py-1 rounded-md bg-purple-800/40 hover:bg-purple-700/60 text-purple-200 border border-purple-500/30 transition flex items-center gap-1">
          <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          Business Account
        </a>
        <a href="{{ route('switch-role', 'government') }}"
          class="px-2.5 py-1 rounded-md bg-purple-800/40 hover:bg-purple-700/60 text-purple-200 border border-purple-500/30 transition flex items-center gap-1">
          <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
          </svg>
          Government
        </a>
        <a href="{{ route('switch-role', 'admin') }}"
          class="px-2.5 py-1 rounded-md bg-purple-800/40 hover:bg-purple-700/60 text-purple-200 border border-purple-500/30 transition flex items-center gap-1">
          <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
          Super Admin
        </a>
      </div>
    </div>
  </div>

  <!-- Main Navigation Header -->
  <header class="sticky top-0 z-40 bg-purple-dark/85 backdrop-blur-xl border-b border-purple-500/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <!-- Logo & Brand -->
        <div class="flex items-center gap-3">
          <div
            class="w-11 h-11 rounded-xl bg-gradient-to-tr from-purple-700 via-purple-600 to-fuchsia-500 p-0.5 shadow-lg shadow-purple-500/30">
            <div class="w-full h-full bg-purple-950 rounded-[10px] flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                  d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
          </div>
          <div>
            <a href="/"
              class="text-2xl font-extrabold tracking-tight font-heading text-white flex items-center gap-1.5">
              Grownesia
              <span
                class="text-xs px-2 py-0.5 rounded-full bg-purple-500/20 text-purple-300 border border-purple-400/30 font-sans font-normal">AI
                Powered</span>
            </a>
            <p class="text-[11px] text-purple-300/70 -mt-1 font-medium">Pemberdayaan UMKM Indonesia</p>
          </div>
        </div>

        <!-- Global Search Bar -->
        <div class="hidden md:flex flex-1 max-w-md mx-8 relative">
          <input type="text" x-model="searchQuery" placeholder="Cari produk UMKM, batik, kopi, kerajinan..."
            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-purple-950/60 border border-purple-500/30 text-sm text-purple-100 placeholder-purple-400/60 focus:outline-none focus:border-purple-400 focus:ring-2 focus:ring-purple-500/20 transition">
          <svg class="w-4 h-4 text-purple-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <button x-show="searchQuery" @click="searchQuery = ''"
            class="absolute right-3 top-3 text-purple-400 hover:text-white text-xs">
            ✕
          </button>
        </div>

        <!-- Action Buttons & Badges -->
        <div class="flex items-center gap-3">
          <!-- AI Assistant Trigger Button -->
          <button @click="showAiDrawer = true"
            class="relative group px-3.5 py-2 rounded-xl bg-gradient-to-r from-purple-800/80 to-purple-600/80 hover:from-purple-700 hover:to-purple-500 text-white text-sm font-semibold border border-purple-400/30 transition shadow-md shadow-purple-900/40 flex items-center gap-2">
            <span class="relative flex h-2.5 w-2.5">
              <span
                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-fuchsia-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-fuchsia-500"></span>
            </span>
            <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <span class="hidden lg:inline">AI Shopping Assistant</span>
          </button>

          <!-- Cart Drawer Trigger Button -->
          <button @click="showCartDrawer = true"
            class="relative p-2.5 rounded-xl bg-purple-950/70 border border-purple-500/30 hover:border-purple-400 text-purple-200 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span x-show="cartTotalCount > 0" x-text="cartTotalCount"
              class="absolute -top-1.5 -right-1.5 bg-gradient-to-r from-fuchsia-500 to-purple-600 text-white text-[11px] font-bold w-5 h-5 rounded-full flex items-center justify-center shadow-lg border border-purple-300">
            </span>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content Slot -->
  <main>
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="mt-24 border-t border-purple-500/20 bg-purple-950/40 text-purple-300/70 py-12 text-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
      <div class="space-y-3 md:col-span-2">
        <div class="flex items-center gap-2">
          <div
            class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-white font-bold text-lg font-heading">
            G</div>
          <span class="text-xl font-extrabold text-white font-heading">Grownesia</span>
        </div>
        <p class="text-xs text-purple-300/80 max-w-md leading-relaxed">
          Platform ekosistem pemberdayaan UMKM Indonesia berbasis AI. Menghubungkan pembeli, pelaku bisnis UMKM,
          pemerintah daerah, dan pengelola platform dalam satu jaringan ekonomi digital berdampak tinggi.
        </p>
        <p class="text-[11px] text-purple-400/60 pt-2">© 2026 Grownesia Platform. All Rights Reserved.</p>
      </div>

      <div>
        <h4 class="text-white font-semibold mb-3 text-xs uppercase tracking-wider">Fitur Pembeli (User Role)</h4>
        <ul class="space-y-2 text-xs">
          <li><a href="#" @click.prevent="scrollToSection('katalog')" class="hover:text-purple-300">Cari Produk &
              Katalog UMKM</a></li>
          <li><a href="#" @click.prevent="showAiDrawer = true" class="hover:text-purple-300">AI Shopping Assistant</a>
          </li>
          <li><a href="#" @click.prevent="scrollToSection('ai-gift')" class="hover:text-purple-300">AI Gift
              Recommendation</a></li>
          <li><a href="#" @click.prevent="scrollToSection('ai-compare')" class="hover:text-purple-300">AI Product
              Comparison</a></li>
          <li><a href="#" @click.prevent="showImpactModal = true" class="hover:text-purple-300">Impact Score Tracker</a>
          </li>
        </ul>
      </div>

      <div>
        <h4 class="text-white font-semibold mb-3 text-xs uppercase tracking-wider">Modul Ekosistem</h4>
        <ul class="space-y-2 text-xs">
          <li><a href="#" @click.prevent="openRolePreview('business')" class="hover:text-purple-300">Business Account
              (UMKM & Supplier)</a></li>
          <li><a href="#" @click.prevent="openRolePreview('government')" class="hover:text-purple-300">Government
              Dashboard (Dinas & Pemda)</a></li>
          <li><a href="#" @click.prevent="openRolePreview('superadmin')" class="hover:text-purple-300">Super Admin
              Center</a></li>
        </ul>
      </div>
    </div>
  </footer>

  <!-- AI SHOPPING ASSISTANT FLOATING DRAWER -->
  <div x-show="showAiDrawer" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-hidden bg-purple-950/60 backdrop-blur-md flex justify-end"
    style="display: none;">

    <div @click.away="showAiDrawer = false"
      class="w-full max-w-md bg-purple-card border-l border-purple-500/30 h-full flex flex-col shadow-2xl">
      <!-- Drawer Header -->
      <div class="p-5 border-b border-purple-500/20 bg-purple-950/80 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div
            class="w-10 h-10 rounded-xl bg-purple-600/30 border border-purple-400/40 flex items-center justify-center text-purple-300 shadow-md">
            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <div>
            <h3 class="text-base font-bold text-white font-heading">AI Shopping Assistant</h3>
            <p class="text-xs text-purple-300/70">Personal Shopper & Gift Finder</p>
          </div>
        </div>
        <button @click="showAiDrawer = false"
          class="p-1.5 rounded-lg text-purple-400 hover:text-white hover:bg-purple-800/50">✕</button>
      </div>

      <!-- Chat Messages Body -->
      <div class="flex-1 overflow-y-auto p-4 space-y-4 text-xs" id="chatContainer">
        <div class="p-3.5 rounded-2xl bg-purple-900/50 border border-purple-500/30 text-purple-200 space-y-2">
          <div class="flex items-center gap-2 font-semibold text-purple-300">
            <span class="w-2 h-2 rounded-full bg-purple-400"></span> Grownesia AI Bot
          </div>
          <p>Halo Budi! Saya asisten belanja berbasis AI Anda. Ketik keinginan Anda, atau gunakan contoh di bawah:</p>
          <div class="pt-1 flex flex-col gap-1.5">
            <button @click="sendAiQuery('Saya ingin hadiah untuk ibu umur 50 tahun.')"
              class="text-left p-2 rounded-xl bg-purple-950/60 hover:bg-purple-800/60 border border-purple-400/30 text-purple-200 text-xs transition flex items-center justify-between">
              <span>💡 "Saya ingin hadiah untuk ibu umur 50 tahun."</span>
              <span class="text-purple-400">→</span>
            </button>
            <button @click="sendAiQuery('Saya mau kopi nusantara dengan impak sosial tertinggi.')"
              class="text-left p-2 rounded-xl bg-purple-950/60 hover:bg-purple-800/60 border border-purple-400/30 text-purple-200 text-xs transition flex items-center justify-between">
              <span>☕ "Kopi nusantara impak tinggi."</span>
              <span class="text-purple-400">→</span>
            </button>
          </div>
        </div>

        <template x-for="(msg, index) in chatMessages" :key="index">
          <div :class="msg.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
            <div
              :class="msg.sender === 'user' ? 'bg-purple-600 text-white rounded-2xl rounded-tr-none p-3.5 max-w-[85%]' : 'bg-purple-950/80 border border-purple-500/30 text-purple-100 rounded-2xl rounded-tl-none p-3.5 max-w-[90%] space-y-2'">
              <p x-text="msg.text"></p>

              <!-- Recommended Product Cards if attached to response -->
              <template x-if="msg.products && msg.products.length">
                <div class="mt-2 space-y-2 pt-2 border-t border-purple-500/20">
                  <template x-for="prod in msg.products" :key="prod.id">
                    <div class="p-2.5 rounded-xl bg-purple-900/40 border border-purple-500/20 flex items-center gap-3">
                      <img :src="prod.image" loading="lazy"
                        class="w-12 h-12 rounded-lg object-cover border border-purple-400/30">
                      <div class="flex-1 min-w-0">
                        <h5 class="text-xs font-bold text-white truncate" x-text="prod.name"></h5>
                        <p class="text-[10px] text-emerald-400 font-medium" x-text="prod.impact_text"></p>
                        <p class="text-xs font-semibold text-purple-300" x-text="formatRupiah(prod.price)"></p>
                      </div>
                      <button @click="addToCart(prod)"
                        class="px-2.5 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white text-[11px] font-semibold shrink-0">
                        + Beli
                      </button>
                    </div>
                  </template>
                </div>
              </template>
            </div>
          </div>
        </template>

        <div x-show="aiIsTyping" class="flex items-center gap-2 text-purple-400 text-xs italic">
          <span class="w-2 h-2 rounded-full bg-purple-400 animate-ping"></span> AI mencari produk yang cocok...
        </div>
      </div>

      <!-- Input Form -->
      <div class="p-4 border-t border-purple-500/20 bg-purple-950/80">
        <form @submit.prevent="sendAiQuery(customChatInput)" class="flex gap-2">
          <input type="text" x-model="customChatInput" placeholder="Tanyakan saran produk atau hadiah..."
            class="flex-1 px-3.5 py-2.5 rounded-xl bg-purple-900/60 border border-purple-500/30 text-xs text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">
          <button type="submit"
            class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-semibold text-xs">Kirim</button>
        </form>
      </div>
    </div>
  </div>

  <!-- SHOPPING CART SLIDE-OVER DRAWER -->
  <div x-show="showCartDrawer" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-hidden bg-purple-950/60 backdrop-blur-md flex justify-end"
    style="display: none;">

    <div @click.away="showCartDrawer = false"
      class="w-full max-w-md bg-purple-card border-l border-purple-500/30 h-full flex flex-col shadow-2xl">
      <!-- Cart Header -->
      <div class="p-5 border-b border-purple-500/20 bg-purple-950/80 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          <h3 class="text-base font-bold text-white font-heading">Keranjang Belanja</h3>
        </div>
        <button @click="showCartDrawer = false" class="p-1.5 rounded-lg text-purple-400 hover:text-white">✕</button>
      </div>

      <!-- Cart Items -->
      <div class="flex-1 overflow-y-auto p-5 space-y-4">
        <template x-if="cart.length === 0">
          <div class="text-center py-16 text-purple-300/60 space-y-3">
            <svg class="w-16 h-16 mx-auto text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <p>Keranjang Anda masih kosong.</p>
            <button @click="showCartDrawer = false"
              class="px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-semibold">Mulai Belanja</button>
          </div>
        </template>

        <template x-for="(item, idx) in cart" :key="item.product.id">
          <div class="p-3.5 rounded-xl bg-purple-900/40 border border-purple-500/20 flex gap-3 items-center">
            <img :src="item.product.image" loading="lazy"
              class="w-14 h-14 rounded-lg object-cover border border-purple-400/30">
            <div class="flex-1 min-w-0">
              <h4 class="text-xs font-bold text-white truncate" x-text="item.product.name"></h4>
              <p class="text-[11px] text-purple-300/70" x-text="item.product.umkm"></p>
              <p class="text-xs font-bold text-purple-300 mt-1" x-text="formatRupiah(item.product.price)"></p>
            </div>
            <div class="flex items-center gap-1.5 bg-purple-950/70 rounded-lg p-1 border border-purple-500/30">
              <button @click="updateQty(idx, -1)"
                class="w-5 h-5 rounded text-purple-300 hover:bg-purple-800 text-xs flex items-center justify-center">-</button>
              <span class="text-xs font-bold px-1 text-white" x-text="item.qty"></span>
              <button @click="updateQty(idx, 1)"
                class="w-5 h-5 rounded text-purple-300 hover:bg-purple-800 text-xs flex items-center justify-center">+</button>
            </div>
          </div>
        </template>
      </div>

      <!-- Cart Footer & Impact Summary -->
      <div x-show="cart.length > 0" class="p-5 border-t border-purple-500/20 bg-purple-950/80 space-y-4">
        <!-- Impact Accumulator Banner -->
        <div
          class="p-3 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-xs flex items-center gap-2">
          <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
          <span>Dampak Pembelian Ini: <strong class="text-white" x-text="calculateCartImpactText()"></strong></span>
        </div>

        <div class="flex items-center justify-between text-sm">
          <span class="text-purple-300/80">Total Pembayaran:</span>
          <span class="text-lg font-extrabold text-white" x-text="formatRupiah(cartTotalPrice)"></span>
        </div>

        <button @click="openCheckoutModal()"
          class="w-full py-3 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-sm transition shadow-lg shadow-purple-900/50">
          Lanjut ke Checkout & Bayar →
        </button>
      </div>
    </div>
  </div>

  <!-- CHECKOUT & PAYMENT MODAL -->
  <div x-show="showCheckoutModal"
    class="fixed inset-0 z-50 overflow-y-auto bg-purple-950/80 backdrop-blur-md flex items-center justify-center p-4"
    style="display: none;">
    <div @click.away="showCheckoutModal = false"
      class="w-full max-w-xl bg-purple-card border border-purple-500/30 rounded-2xl overflow-hidden shadow-2xl space-y-5 p-6">
      <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
        <h3 class="text-lg font-bold text-white font-heading">Checkout & Pembayaran</h3>
        <button @click="showCheckoutModal = false" class="text-purple-400 hover:text-white">✕</button>
      </div>

      <!-- Form Alamat & Metod Pembayaran -->
      <div class="space-y-4 text-xs">
        <div>
          <label class="block font-semibold text-purple-200 mb-1">Alamat Pengiriman</label>
          <textarea class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-purple-100"
            rows="2">Jl. Merdeka No. 45, Kebayoran Baru, Jakarta Selatan, 12110</textarea>
        </div>

        <div>
          <label class="block font-semibold text-purple-200 mb-1">Metode Pembayaran</label>
          <div class="grid grid-cols-3 gap-2">
            <label
              class="p-2.5 rounded-xl border border-purple-500/40 bg-purple-900/40 cursor-pointer flex flex-col items-center justify-center gap-1 hover:border-purple-400">
              <input type="radio" name="payment" value="qris" checked class="text-purple-500">
              <span class="font-bold text-white">QRIS Instant</span>
            </label>
            <label
              class="p-2.5 rounded-xl border border-purple-500/40 bg-purple-900/40 cursor-pointer flex flex-col items-center justify-center gap-1 hover:border-purple-400">
              <input type="radio" name="payment" value="gopay" class="text-purple-500">
              <span class="font-bold text-white">GoPay / OVO</span>
            </label>
            <label
              class="p-2.5 rounded-xl border border-purple-500/40 bg-purple-900/40 cursor-pointer flex flex-col items-center justify-center gap-1 hover:border-purple-400">
              <input type="radio" name="payment" value="bank" class="text-purple-500">
              <span class="font-bold text-white">Transfer Bank</span>
            </label>
          </div>
        </div>

        <!-- Ringkasan Transaksi -->
        <div class="p-3.5 rounded-xl bg-purple-950/90 border border-purple-500/30 space-y-2">
          <div class="flex justify-between">
            <span class="text-purple-300/70">Total Produk (<span x-text="cartTotalCount"></span>)</span>
            <span class="text-white font-semibold" x-text="formatRupiah(cartTotalPrice)"></span>
          </div>
          <div class="flex justify-between">
            <span class="text-purple-300/70">Ongkos Kirim (Subsidi UMKM)</span>
            <span class="text-emerald-400 font-semibold">GRATIS</span>
          </div>
          <div class="pt-2 border-t border-purple-500/20 flex justify-between text-sm font-extrabold">
            <span class="text-purple-200">Total Akhir:</span>
            <span class="text-purple-300" x-text="formatRupiah(cartTotalPrice)"></span>
          </div>
        </div>
      </div>

      <button @click="processPaymentSuccess()"
        class="w-full py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm transition shadow-lg flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        Bayar Sekarang & Bantu UMKM
      </button>
    </div>
  </div>

  <!-- ORDER SUCCESS & IMPACT CERTIFICATE MODAL -->
  <div x-show="showSuccessModal"
    class="fixed inset-0 z-50 overflow-y-auto bg-purple-950/90 backdrop-blur-lg flex items-center justify-center p-4"
    style="display: none;">
    <div
      class="w-full max-w-lg bg-purple-card border border-purple-400/40 rounded-3xl p-6 text-center space-y-4 shadow-2xl relative overflow-hidden">
      <!-- Confetti Glow Background -->
      <div class="absolute -top-20 -left-20 w-48 h-48 bg-purple-500/30 rounded-full filter blur-3xl"></div>

      <div
        class="w-16 h-16 rounded-full bg-emerald-500/20 border-2 border-emerald-400 text-emerald-400 flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
        </svg>
      </div>

      <h3 class="text-2xl font-extrabold text-white font-heading">Pembayaran Berhasil! 🎉</h3>
      <p class="text-xs text-purple-200/90">Terima kasih atas pesanan Anda. Transaksi Anda membantu menggerakkan roda
        ekonomi UMKM lokal Indonesia!</p>

      <!-- Social Impact Certificate Card -->
      <div
        class="p-4 rounded-2xl bg-gradient-to-br from-purple-900/80 via-purple-950 to-indigo-950 border border-purple-400/40 text-left space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-[11px] font-bold text-purple-400 uppercase tracking-widest">Sertifikat Dampak Sosial</span>
          <span class="text-xs text-emerald-400 font-semibold"> Verified Impact</span>
        </div>
        <div class="text-sm font-bold text-white" x-text="lastOrderImpactSummary"></div>
        <p class="text-[11px] text-purple-300/70">Pendapatan langsung diteruskan ke UMKM mitra Grownesia di Palembang,
          Solo, dan Kebumen.</p>
      </div>

      <button @click="showSuccessModal = false"
        class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition">
        Kembali ke Beranda
      </button>
    </div>
  </div>

  <!-- ROLE PREVIEW MODAL (BUSINESS / GOV / SUPER ADMIN PREVIEW) -->
  <div x-show="showRolePreviewModal"
    class="fixed inset-0 z-50 overflow-y-auto bg-purple-950/85 backdrop-blur-md flex items-center justify-center p-4"
    style="display: none;">
    <div @click.away="showRolePreviewModal = false"
      class="w-full max-w-2xl bg-purple-card border border-purple-400/40 rounded-3xl p-6 space-y-5 shadow-2xl">
      <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
        <div class="flex items-center gap-3">
          <span
            class="px-3 py-1 rounded-full text-xs font-bold bg-purple-500/30 text-purple-300 border border-purple-400/40 uppercase"
            x-text="previewRoleTitle"></span>
          <span class="text-xs text-purple-300/70">Preview Arsitektur Dashboard</span>
        </div>
        <button @click="showRolePreviewModal = false" class="text-purple-400 hover:text-white">✕</button>
      </div>

      <!-- Role Description & Feature Highlights -->
      <div class="space-y-3">
        <p class="text-xs text-purple-200/90 leading-relaxed" x-text="previewRoleDescription"></p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
          <template x-for="feat in previewRoleFeatures" :key="feat.title">
            <div class="p-3 rounded-xl bg-purple-950/80 border border-purple-500/30 space-y-1">
              <h5 class="text-xs font-bold text-purple-300 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>
                <span x-text="feat.title"></span>
              </h5>
              <p class="text-[11px] text-purple-300/70" x-text="feat.desc"></p>
            </div>
          </template>
        </div>
      </div>

      <div
        class="p-4 rounded-xl bg-purple-900/30 border border-purple-500/20 text-xs text-purple-300 flex items-center justify-between">
        <span>⚡ <strong>Catatan:</strong> Pengembang sedang memfokuskan implementasi <strong>Role User (Fitur
            Utama)</strong> saat ini.</span>
        <button @click="showRolePreviewModal = false"
          class="px-4 py-2 rounded-xl bg-purple-600 text-white font-bold">Mengerti</button>
      </div>
    </div>
  </div>

  <!-- Alpine App Logic Script -->
  <script>
    function grownesiaApp() {
      return {
        // Catalog & State
        products: @json($products ?? []),
        searchQuery: '',
        activeCategory: 'all',
        selectedImpactFilter: 'all',

        // User Impact Tracker State
        userImpact: {
          totalJobs: 8,
          villagesHelped: 3,
          craftswomenHelped: 5
        },

        cart: [],
        showCartDrawer: false,
        showCheckoutModal: false,
        showSuccessModal: false,
        lastOrderImpactSummary: '',

        // AI Assistant State
        showAiDrawer: false,
        customChatInput: '',
        aiIsTyping: false,
        chatMessages: [
          {
            sender: 'ai',
            text: 'Halo Budi! Saya AI Assistant Grownesia. Ada yang bisa saya bantu rekomendasikan?',
          }
        ],

        // AI Gift Recommendation Budget Tool
        giftBudget: 300000,
        giftRecipient: 'ibu',
        generatedBundle: null,

        // AI Product Comparison Tool
        compareProduct1: null,
        compareProduct2: null,
        compareProduct1Id: null,
        compareProduct2Id: null,
        aiCompareAnalysis: null,

        // Modals
        showImpactModal: false,
        showRolePreviewModal: false,
        previewRoleTitle: '',
        previewRoleDescription: '',
        previewRoleFeatures: [],

        async saveCart() {
          const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
          try {
            await fetch('/cart/sync', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token || ''
              },
              body: JSON.stringify({ cart: this.cart })
            });
          } catch (e) {
            console.error('Failed to sync cart to database:', e);
          }
        },

        async loadCart() {
          try {
            let response = await fetch('/cart');
            if (response.ok) {
              this.cart = await response.json();
            }
          } catch (e) {
            console.error('Failed to load cart from database:', e);
          }
        },

        async initApp() {
          await this.loadCart();
          // Pre-select comparison default products
          if (this.products.length >= 2) {
            this.compareProduct1 = this.products[0];
            this.compareProduct2 = this.products[4] || this.products[1]; // Kopi Klepon
            this.compareProduct1Id = this.compareProduct1.id;
            this.compareProduct2Id = this.compareProduct2.id;
            this.generateAiComparison();
          }
          // Generate initial AI bundle demo
          this.generateGiftBundle();
        },

        // Computed filtered products
        get filteredProducts() {
          return this.products.filter(p => {
            const matchesSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
              p.umkm.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
              p.region.toLowerCase().includes(this.searchQuery.toLowerCase());
            const matchesCat = this.activeCategory === 'all' || p.category === this.activeCategory;
            const matchesImpact = this.selectedImpactFilter === 'all' || p.tags.includes(this.selectedImpactFilter);
            return matchesSearch && matchesCat && matchesImpact;
          });
        },

        // Cart Calculations
        get cartTotalCount() {
          return this.cart.reduce((sum, item) => sum + item.qty, 0);
        },

        get cartTotalPrice() {
          return this.cart.reduce((sum, item) => sum + (item.product.price * item.qty), 0);
        },

        addToCart(product) {
          const idx = this.cart.findIndex(i => i.product.id === product.id);
          if (idx > -1) {
            this.cart[idx].qty++;
          } else {
            this.cart.push({ product: product, qty: 1 });
          }
          this.saveCart();
          this.showCartDrawer = true;
        },

        updateQty(idx, change) {
          this.cart[idx].qty += change;
          if (this.cart[idx].qty <= 0) {
            this.cart.splice(idx, 1);
          }
          this.saveCart();
        },

        calculateCartImpactText() {
          if (this.cart.length === 0) return '0 Pekerja';
          let count = this.cart.reduce((acc, item) => acc + (2 * item.qty), 0);
          return count + ' Pekerja Lokal & ' + Math.ceil(count / 2) + ' Desa Terbantu';
        },

        openCheckoutModal() {
          this.showCartDrawer = false;
          this.showCheckoutModal = true;
        },

        async processPaymentSuccess() {
          const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
          if (this.cart.length > 0) {
            try {
              await fetch('/checkout', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': token || ''
                },
                body: JSON.stringify({ cart: this.cart })
              });
            } catch (err) {
              console.error('Gagal mengirim pesanan ke server:', err);
            }
          }

          this.lastOrderImpactSummary = "3 Pekerja Lokal, 1 Desa Berkembang, & 2 Penenun Terbantu";
          this.userImpact.totalJobs += 3;
          this.userImpact.villagesHelped += 1;
          this.cart = [];
          this.saveCart();
          this.showCheckoutModal = false;
          this.showSuccessModal = true;
        },

        scrollToBottom() {
          this.$nextTick(() => {
            ['#chatContainer', '#fullChatContainer'].forEach(selector => {
              const el = document.querySelector(selector);
              if (el) {
                el.scrollTo({
                  top: el.scrollHeight,
                  behavior: 'smooth'
                });
              }
            });
          });
        },

        // AI Shopping Assistant Actions
        sendAiQuery(queryText) {
          if (!queryText.trim()) return;

          this.chatMessages.push({ sender: 'user', text: queryText });
          this.customChatInput = '';
          this.aiIsTyping = true;
          this.scrollToBottom();

          setTimeout(() => {
            this.aiIsTyping = false;
            if (queryText.toLowerCase().includes('ibu') || queryText.toLowerCase().includes('50 tahun') || queryText.toLowerCase().includes('hadiah')) {
              this.chatMessages.push({
                sender: 'ai',
                text: 'AI telah menganalisis preferensi untuk Ibu (50th). Paket kerajinan kain tradisional & tas anyaman pandan berkualitas tinggi sangat cocok untuk kenyamanan & gaya beliau:',
                products: [this.products[1], this.products[2]]
              });
            } else if (queryText.toLowerCase().includes('kopi')) {
              this.chatMessages.push({
                sender: 'ai',
                text: 'Berikut rekomendasi kopi terbaik dari UMKM mitra dengan impak sosial tertinggi:',
                products: [this.products[0], this.products[4]]
              });
            } else {
              this.chatMessages.push({
                sender: 'ai',
                text: 'Berdasarkan keinginan Anda, berikut pilihan produk karya UMKM lokal terbaik:',
                products: [this.products[0], this.products[2]]
              });
            }

            this.scrollToBottom();
          }, 800);
        },

        // AI Gift Recommendation Bundle Creator
        generateGiftBundle() {
          let total = 0;
          let items = [];
          // Pick items fitting budget
          for (let p of this.products) {
            if (total + p.price <= this.giftBudget) {
              items.push(p);
              total += p.price;
            }
          }
          this.generatedBundle = {
            items: items,
            totalPrice: total,
            impact: items.length * 2 + ' Pekerja Lokal & 1 Desa Berkembang Terbantu'
          };
        },

        addBundleToCart() {
          if (!this.generatedBundle) return;
          for (let item of this.generatedBundle.items) {
            this.addToCart(item);
          }
        },

        async generateAiComparison() {
          this.compareProduct1 = this.products.find(p => p.id == this.compareProduct1Id) || null;
          this.compareProduct2 = this.products.find(p => p.id == this.compareProduct2Id) || null;

          let p1 = this.compareProduct1;
          let p2 = this.compareProduct2;
          if (!p1 || !p2) return;

          this.aiCompareAnalysis = {
            verdict: 'AI sedang menganalisis perbandingan kedua produk...',
            recommendation: ''
          };

          const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

          try {
            let response = await fetch('/user/ai/compare', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token || ''
              },
              body: JSON.stringify({
                product1_id: p1.id,
                product2_id: p2.id
              })
            });

            if (response.ok) {
              let data = await response.json();
              if (data && data.success) {
                this.aiCompareAnalysis = {
                  verdict: data.verdict,
                  recommendation: data.recommendation
                };
                return;
              }
            }
          } catch (err) {
            console.error('Error fetching AI Comparison:', err);
          }

          const price1Fmt = (prod) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(prod.price);
          this.aiCompareAnalysis = {
            verdict: (p1.price) < (p2.price)
              ? `${p1.name} (${price1Fmt(p1)}) menawarkan pilihan lebih hemat dari ${p2.name} (${price1Fmt(p2)}).`
              : `${p2.name} (${price1Fmt(p2)}) menawarkan pilihan lebih hemat dari ${p1.name} (${price1Fmt(p1)}).`,
            recommendation: `Pilih ${p1.price < p2.price ? p1.name : p2.name} untuk alternatif hemat.`
          };
        },

        // Role Switcher Modal
        openRolePreview(role) {
          if (role === 'business') {
            this.previewRoleTitle = 'Business Account';
            this.previewRoleDescription = 'Business Account merupakan pusat operasional digital bagi pelaku usaha. Semua aktivitas bisnis (produk, pemasaran, inventaris, hingga analisis) dilakukan dalam satu dashboard berbasis AI.';
            this.previewRoleFeatures = [
              { title: 'Business Dashboard', desc: 'Omzet, statistik penjualan, produk terlaris, AI Insight.' },
              { title: 'AI Product Optimizer', desc: 'Mendeteksi foto terlalu gelap & memberi rekomendasi perbaikan otomatis.' },
              { title: 'AI Inventory Prediction', desc: 'Memprediksi stok habis berdasarkan tren musiman.' },
              { title: 'AI Marketing Center', desc: 'Pembuatan caption, hashtag, poster, dan banner otomatis.' },
              { title: 'Omnichannel & Financial Assistant', desc: 'Integrasi WhatsApp & estimasi BEP, cashflow, margin.' }
            ];
          } else if (role === 'government') {
            this.previewRoleTitle = 'Government Dashboard';
            this.previewRoleDescription = 'Government Dashboard merupakan dashboard khusus instansi pemerintah (Dinas Koperasi/Perdagangan) untuk memantau perkembangan ekonomi daerah berbasis data agregat UMKM.';
            this.previewRoleFeatures = [
              { title: 'Economic Dashboard', desc: 'Jumlah UMKM aktif, omzet agregat, pertumbuhan bulanan.' },
              { title: 'AI Economic Insight', desc: 'Rekomendasi otomatis daerah berpotensi sentra kopi/kerajinan.' },
              { title: 'Program & Policy Simulator', desc: 'Simulasi dampak kebijakan sebelum program dijalankan.' },
              { title: 'Event Management', desc: 'Pengelolaan bazar & pameran UMKM daerah.' }
            ];
          } else if (role === 'superadmin') {
            this.previewRoleTitle = 'Super Admin';
            this.previewRoleDescription = 'Pusat kontrol dan keamanan platform Grownesia secara menyeluruh untuk memantau performa server, transaksi, dan kecerdasan buatan.';
            this.previewRoleFeatures = [
              { title: 'Platform Dashboard', desc: 'Total User, Transaksi, Server Status, & AI Requests.' },
              { title: 'Business Verification', desc: 'Verifikasi legalitas dan sertifikasi UMKM.' },
              { title: 'AI & Integration Center', desc: 'Manajemen model AI & API pengiriman/pembayaran.' }
            ];
          }
          this.showRolePreviewModal = true;
        },

        formatRupiah(number) {
          return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
        },

        scrollToSection(id) {
          const el = document.getElementById(id);
          if (el) el.scrollIntoView({ behavior: 'smooth' });
        }
      }
    }
  </script>
</body>

</html>