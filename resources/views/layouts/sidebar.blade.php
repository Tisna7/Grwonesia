<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Grownesia User Dashboard - Ekosistem Pemberdayaan UMKM Indonesia Berbasis AI">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard User - Grownesia AI & Impact</title>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-purple-dark text-slate-100 min-h-screen font-sans selection:bg-purple-500 selection:text-white flex overflow-x-hidden"
      x-data="grownesiaUserDashboard()"
      x-init="initDashboard()">

    <!-- LEFT SIDEBAR NAVIGATION (LOGGED-IN USER LAYOUT) -->
    <aside class="w-64 bg-purple-950/90 backdrop-blur-2xl border-r border-purple-500/20 min-h-screen flex flex-col justify-between shrink-0 sticky top-0 h-screen z-40 hidden md:flex">
        
        <!-- Sidebar Brand & Logo -->
        <div class="p-6 border-b border-purple-500/20 space-y-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-700 via-purple-600 to-fuchsia-500 p-0.5 shadow-lg shadow-purple-500/30">
                        <div class="w-full h-full bg-purple-950 rounded-[10px] flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-400 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold tracking-tight font-heading text-white">Grownesia</h1>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-500/20 text-purple-300 border border-purple-400/30 font-semibold">User Portal</span>
                    </div>
                </a>
            </div>

            <!-- Profile Info Widget -->
            <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-purple-800/80 border-purple-400' : 'bg-purple-900/40 border-purple-500/20 hover:bg-purple-800/40'" class="w-full p-2.5 rounded-xl border text-xs flex items-center justify-between transition">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping shrink-0"></div>
                    <span class="text-purple-200/90 font-medium truncate">User: <strong x-text="userProfile.name"></strong></span>
                </div>
                <div class="flex items-center gap-1 text-[10px] text-purple-300 font-semibold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Profil</span>
                </div>
            </button>
        </div>

        <!-- Sidebar Menu Links -->
        <div class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto text-xs font-semibold">
            <div class="text-[10px] uppercase font-extrabold tracking-wider text-purple-400/60 px-3 mb-2">Navigasi Utama</div>

            <button @click="activeTab = 'katalog'" :class="activeTab === 'katalog' || activeTab === 'detail' ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-purple-300/80 hover:bg-purple-900/50 hover:text-white'" class="w-full px-3.5 py-3 rounded-xl transition flex items-center gap-3">
                <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Cari Produk & Katalog</span>
            </button>

            <button @click="activeTab = 'ai-assistant'" :class="activeTab === 'ai-assistant' ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-purple-300/80 hover:bg-purple-900/50 hover:text-white'" class="w-full px-3.5 py-3 rounded-xl transition flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>AI Shopping Assistant</span>
                </div>
                <span class="w-2 h-2 rounded-full bg-fuchsia-400 animate-pulse"></span>
            </button>

            <button @click="activeTab = 'ai-gift'" :class="activeTab === 'ai-gift' ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-purple-300/80 hover:bg-purple-900/50 hover:text-white'" class="w-full px-3.5 py-3 rounded-xl transition flex items-center gap-3">
                <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2z"/></svg>
                <span>AI Gift Recommendation</span>
            </button>

            <button @click="activeTab = 'ai-compare'" :class="activeTab === 'ai-compare' ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-purple-300/80 hover:bg-purple-900/50 hover:text-white'" class="w-full px-3.5 py-3 rounded-xl transition flex items-center gap-3">
                <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>AI Product Comparison</span>
            </button>

            <div class="text-[10px] uppercase font-extrabold tracking-wider text-purple-400/60 px-3 pt-4 mb-2">Belanja & Transaksi</div>

            <button @click="activeTab = 'cart'" :class="activeTab === 'cart' ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-purple-300/80 hover:bg-purple-900/50 hover:text-white'" class="w-full px-3.5 py-3 rounded-xl transition flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    <span>Keranjang & Checkout</span>
                </div>
                <span x-show="cartTotalCount > 0" x-text="cartTotalCount" class="bg-fuchsia-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full"></span>
            </button>

            <button @click="activeTab = 'favorites'" :class="activeTab === 'favorites' ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-purple-300/80 hover:bg-purple-900/50 hover:text-white'" class="w-full px-3.5 py-3 rounded-xl transition flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <span>Favorit Saya</span>
                </div>
                <span x-show="favorites.length > 0" x-text="favorites.length" class="bg-red-500/20 text-red-300 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-500/30"></span>
            </button>

            <button @click="activeTab = 'orders'" :class="activeTab === 'orders' || activeTab === 'write-review' ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-purple-300/80 hover:bg-purple-900/50 hover:text-white'" class="w-full px-3.5 py-3 rounded-xl transition flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Riwayat Pesanan & Review</span>
                </div>
                <span x-text="ordersHistory.length" class="bg-emerald-500/20 text-emerald-300 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-500/30"></span>
            </button>

            <div class="text-[10px] uppercase font-extrabold tracking-wider text-purple-400/60 px-3 pt-4 mb-2">Impact & Akun</div>

            <button @click="activeTab = 'impact'" :class="activeTab === 'impact' ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-purple-300/80 hover:bg-purple-900/50 hover:text-white'" class="w-full px-3.5 py-3 rounded-xl transition flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <span>Impact Score Saya</span>
                </div>
                <span class="text-[10px] font-extrabold bg-emerald-500/20 px-1.5 py-0.5 rounded text-emerald-300" x-text="userImpact.totalJobs + ' Pekerja'"></span>
            </button>

            <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-purple-300/80 hover:bg-purple-900/50 hover:text-white'" class="w-full px-3.5 py-3 rounded-xl transition flex items-center gap-3">
                <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Pengaturan Profil & Alamat</span>
            </button>
        </div>

        <!-- Sidebar Footer & Logout Button -->
        <div class="p-4 border-t border-purple-500/20 bg-purple-950/80">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-purple-900/50 hover:bg-red-900/50 border border-purple-500/30 hover:border-red-500/40 text-purple-300 hover:text-red-200 text-xs font-semibold transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-purple-400 group-hover:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- RIGHT MAIN WORKSPACE AREA -->
    <div class="flex-1 min-w-0 flex flex-col min-h-screen">
        
        <!-- TOP HEADER FOR DASHBOARD WORKSPACE -->
        <header class="sticky top-0 z-30 bg-purple-dark/85 backdrop-blur-xl border-b border-purple-500/20 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h2 class="text-lg font-bold text-white font-heading">
                    Selamat datang, <span x-text="userProfile.name"></span>!
                </h2>
                <span class="hidden lg:inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                    Impact Buyer Tier
                </span>
            </div>

            <!-- Header Quick Tools & Notifications & Profile -->
            <div class="flex items-center gap-3 relative">
                
                <!-- NOTIFICATION BELL WITH DROPDOWN -->
                <div class="relative" x-data="{ openNotif: false }">
                    <button @click="openNotif = !openNotif" class="relative p-2.5 rounded-xl bg-purple-950/70 border border-purple-500/30 hover:border-purple-400 text-purple-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span x-show="unreadNotifCount > 0" x-text="unreadNotifCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center animate-bounce"></span>
                    </button>

                    <div x-show="openNotif" @click.away="openNotif = false" class="absolute right-0 mt-2 w-80 bg-purple-card border border-purple-500/30 rounded-2xl shadow-2xl p-4 z-50 space-y-3" style="display: none;">
                        <div class="flex items-center justify-between border-b border-purple-500/20 pb-2">
                            <h4 class="text-xs font-bold text-white">Notifikasi (Pusat Informasi)</h4>
                            <button @click="markAllNotifRead()" class="text-[10px] text-purple-300/80 hover:text-white">Tandai Dibaca</button>
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

                <button @click="activeTab = 'ai-assistant'" :class="activeTab === 'ai-assistant' ? 'bg-purple-500 text-white' : 'bg-purple-600 hover:bg-purple-500 text-white'" class="px-3.5 py-2 rounded-xl text-xs font-semibold shadow-md flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>AI Assistant</span>
                </button>

                <button @click="activeTab = 'cart'" :class="activeTab === 'cart' ? 'border-purple-400 bg-purple-900' : 'bg-purple-950/70 border-purple-500/30 hover:border-purple-400'" class="relative p-2.5 rounded-xl border text-purple-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span x-show="cartTotalCount > 0" x-text="cartTotalCount" class="absolute -top-1 -right-1 bg-fuchsia-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center"></span>
                </button>

                <button @click="activeTab = 'profile'" class="w-9 h-9 rounded-xl bg-purple-800 border border-purple-400/40 flex items-center justify-center text-xs font-bold text-white hover:bg-purple-700 transition">
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
        function grownesiaUserDashboard() {
            return {
                products: @json($products ?? []),
                searchQuery: '',
                activeCategory: 'all',
                selectedImpactFilter: 'all',
                activeTab: 'katalog',

                userProfile: {
                    name: '{{ Auth::user()->name ?? "Budi Santoso" }}',
                    email: '{{ Auth::user()->email ?? "budi@grownesia.id" }}',
                    phone: '0812-3456-7890',
                    address: 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan'
                },

                favorites: [1, 3],

                reviews: [
                    {
                        id: 101,
                        productId: 1,
                        userName: 'Siti Rahmawati',
                        rating: 5,
                        date: '20 Juli 2026',
                        comment: 'Kopi gula aren ini rasanya mantap banget! Manisnya alami dari gula aren asli Palembang dan seneng banget dapet sertifikat pemberdayaan 2 petani lokal.',
                        verified: true
                    },
                    {
                        id: 102,
                        productId: 2,
                        userName: 'Rian Hidayat',
                        rating: 5,
                        date: '19 Juli 2026',
                        comment: 'Kain tenun ikatnya sangat halus dan motifnya sungguh eksklusif. Kemasan dilapisi besek ramah lingkungan. Sangat memuaskan!',
                        verified: true
                    },
                    {
                        id: 103,
                        productId: 3,
                        userName: 'Dewi Lestari',
                        rating: 4,
                        date: '15 Juli 2026',
                        comment: 'Tas anyaman serat pandannya sangat kokoh, muat banyak barang. Pengiriman cepat langsung dari pengrajin Kebumen.',
                        verified: true
                    }
                ],

                ordersHistory: [
                    {
                        id: 'GRW-2026-8941',
                        date: '23 Juli 2026',
                        productId: 1,
                        productName: 'Kopi Gula Aren Nusantara Premium 500ml',
                        items: 'Kopi Gula Aren Nusantara (2x)',
                        total: 90000,
                        status: 'Selesai',
                        impact: '2 Petani Terbantu',
                        reviewed: false
                    },
                    {
                        id: 'GRW-2026-7812',
                        date: '18 Juli 2026',
                        productId: 2,
                        productName: 'Kain Tenun Ikat NTT Handmade Royal Violet',
                        items: 'Kain Tenun Ikat NTT Handmade (1x)',
                        total: 195000,
                        status: 'Selesai',
                        impact: '3 Penenun Terbantu',
                        reviewed: true
                    }
                ],

                reviewingOrder: null,
                newReviewForm: {
                    rating: 5,
                    comment: ''
                },

                notifications: [
                    { id: 1, title: 'Pesanan Dikirim', desc: 'Pesanan #GRW-2026-8941 telah selesai dan siap diulas.', time: '10 menit yang lalu' },
                    { id: 2, title: 'Voucher Subsidi Ongkir', desc: 'Diskon ongkos kirim 100% aktif untuk produk UMKM mitra.', time: '2 jam yang lalu' },
                    { id: 3, title: 'AI Personal Shopper', desc: 'AI menemukan 2 kado batik yang cocok untuk anggaran Anda.', time: '1 hari yang lalu' }
                ],

                unreadNotifCount: 3,

                selectedProductDetail: null,

                userImpact: {
                    totalJobs: 8,
                    villagesHelped: 3,
                    craftswomenHelped: 5
                },

                cart: [],
                selectedPaymentMethod: 'qris',
                lastOrderImpactSummary: '',

                customChatInput: '',
                aiIsTyping: false,
                chatMessages: [
                    {
                        sender: 'ai',
                        text: 'Halo Budi! Saya AI Assistant Anda. Silakan tanyakan saran produk atau hadiah yang Anda butuhkan.',
                    }
                ],

                giftBudget: 300000,
                giftRecipientMode: 'preset',
                giftRecipient: 'Hadiah untuk Ibu / Orang Tua (Usia 50th)',
                customGiftRecipientInput: '',
                generatedBundle: null,

                compareProduct1: null,
                compareProduct2: null,
                aiCompareAnalysis: null,

                initDashboard() {
                    if (this.products.length >= 2) {
                        this.cart = [{ product: this.products[0], qty: 1 }];
                        this.selectedProductDetail = this.products[0];
                        this.compareProduct1 = this.products[0];
                        this.compareProduct2 = this.products[4];
                        this.generateAiComparison();
                    }
                    this.generateGiftBundle();
                },

                openProductDetail(product) {
                    this.selectedProductDetail = product;
                    this.activeTab = 'detail';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                getProductReviews(productId) {
                    return this.reviews.filter(r => r.productId === productId);
                },

                openWriteReview(order) {
                    this.reviewingOrder = order;
                    this.newReviewForm.rating = 5;
                    this.newReviewForm.comment = '';
                    this.activeTab = 'write-review';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                submitProductReview() {
                    if (!this.newReviewForm.comment.trim()) {
                        alert('Mohon tuliskan ulasan pengalaman belanja Anda.');
                        return;
                    }

                    this.reviews.unshift({
                        id: Date.now(),
                        productId: this.reviewingOrder.productId,
                        userName: this.userProfile.name,
                        rating: parseInt(this.newReviewForm.rating),
                        date: 'Hari ini',
                        comment: this.newReviewForm.comment,
                        verified: true
                    });

                    this.reviewingOrder.reviewed = true;

                    alert('Ulasan Anda telah berhasil dipublikasikan! Terima kasih telah mengulas produk UMKM mitra.');
                    this.activeTab = 'orders';
                },

                toggleFavorite(product) {
                    const id = product.id;
                    const idx = this.favorites.indexOf(id);
                    if (idx > -1) {
                        this.favorites.splice(idx, 1);
                    } else {
                        this.favorites.push(id);
                    }
                },

                isFavorite(productId) {
                    return this.favorites.includes(productId);
                },

                get favoriteProducts() {
                    return this.products.filter(p => this.favorites.includes(p.id));
                },

                markAllNotifRead() {
                    this.unreadNotifCount = 0;
                },

                saveProfile() {
                    alert('Profil dan Alamat berhasil diperbarui!');
                    this.activeTab = 'katalog';
                },

                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchesSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                              p.umkm.toLowerCase().includes(this.searchQuery.toLowerCase());
                        const matchesCat = this.activeCategory === 'all' || p.category === this.activeCategory;
                        const matchesImpact = this.selectedImpactFilter === 'all' || p.tags.includes(this.selectedImpactFilter);
                        return matchesSearch && matchesCat && matchesImpact;
                    });
                },

                get cartTotalCount() {
                    return this.cart.reduce((sum, item) => sum + (item.product ? item.qty : 0), 0);
                },

                get cartTotalPrice() {
                    return this.cart.reduce((sum, item) => sum + (item.product ? (item.product.price * item.qty) : 0), 0);
                },

                addToCart(product) {
                    const idx = this.cart.findIndex(i => i.product && i.product.id === product.id);
                    if (idx > -1) {
                        this.cart[idx].qty++;
                    } else {
                        this.cart.push({ product: product, qty: 1 });
                    }
                    this.activeTab = 'cart';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                updateQty(idx, change) {
                    this.cart[idx].qty += change;
                    if (this.cart[idx].qty <= 0) {
                        this.cart.splice(idx, 1);
                    }
                },

                calculateCartImpactText() {
                    if (this.cart.length === 0) return '0 Pekerja';
                    let count = this.cart.reduce((acc, item) => acc + (item.product ? (2 * item.qty) : 0), 0);
                    return count + ' Pekerja Lokal & ' + Math.ceil(count/2) + ' Desa Terbantu';
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

                    const newOrderId = 'GRW-2026-' + Math.floor(1000 + Math.random() * 9000);
                    const firstProd = this.cart.length > 0 ? this.cart[0].product : (this.products[0] || { id: 1, name: 'Produk UMKM' });
                    const itemsSummary = this.cart.length > 0 ? this.cart.map(c => `${c.product.name} (${c.qty}x)`).join(', ') : 'Pesanan UMKM';
                    
                    this.ordersHistory.unshift({
                        id: newOrderId,
                        date: 'Hari ini',
                        productId: firstProd.id,
                        productName: firstProd.name,
                        items: itemsSummary,
                        total: this.cartTotalPrice,
                        status: 'Selesai',
                        impact: '3 Pekerja Terbantu',
                        reviewed: false
                    });

                    this.lastOrderImpactSummary = "3 Pekerja Lokal, 1 Desa Berkembang, & 2 Penenun Terbantu";
                    this.userImpact.totalJobs += 3;
                    this.userImpact.villagesHelped += 1;
                    this.cart = [];
                    this.activeTab = 'success-impact';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                sendAiQuery(queryText) {
                    if (!queryText.trim()) return;
                    this.chatMessages.push({ sender: 'user', text: queryText });
                    this.customChatInput = '';
                    this.aiIsTyping = true;

                    setTimeout(() => {
                        this.aiIsTyping = false;
                        if (queryText.toLowerCase().includes('ibu') || queryText.toLowerCase().includes('50 tahun') || queryText.toLowerCase().includes('hadiah')) {
                            this.chatMessages.push({
                                sender: 'ai',
                                text: 'AI merekomendasikan hadiah berkesan untuk Ibu (50th): Kain batik khas Solo dan tas anyaman serat pandan alami:',
                                products: [this.products[1], this.products[2]]
                            });
                        } else if (queryText.toLowerCase().includes('kopi')) {
                            this.chatMessages.push({
                                sender: 'ai',
                                text: 'Rekomendasi kopi khas UMKM dengan nilai dampak sosial tertinggi:',
                                products: [this.products[0], this.products[4]]
                            });
                        } else {
                            this.chatMessages.push({
                                sender: 'ai',
                                text: 'Berikut pilihan produk UMKM terbaik sesuai pertanyaan Anda:',
                                products: [this.products[0], this.products[2]]
                            });
                        }
                    }, 800);
                },

                generateGiftBundle() {
                    let recipientText = (this.giftRecipientMode === 'custom' && this.customGiftRecipientInput.trim() !== '') 
                        ? this.customGiftRecipientInput 
                        : this.giftRecipient;

                    let sortedProducts = [...this.products];
                    const qLower = recipientText.toLowerCase();

                    if (qLower.includes('dosen') || qLower.includes('kantor') || qLower.includes('rekan') || qLower.includes('formal') || qLower.includes('guru')) {
                        sortedProducts.sort((a, b) => (a.category === 'kopi' || a.category === 'batik') ? -1 : 1);
                    } else if (qLower.includes('ibu') || qLower.includes('wanita') || qLower.includes('perempuan') || qLower.includes('bunda')) {
                        sortedProducts.sort((a, b) => (a.category === 'batik' || a.category === 'kerajinan') ? -1 : 1);
                    } else if (qLower.includes('makanan') || qLower.includes('cemilan') || qLower.includes('snack') || qLower.includes('kuliner')) {
                        sortedProducts.sort((a, b) => (a.category === 'makanan') ? -1 : 1);
                    } else if (qLower.includes('pria') || qLower.includes('bapak') || qLower.includes('ayah') || qLower.includes('suami')) {
                        sortedProducts.sort((a, b) => (a.category === 'kopi') ? -1 : 1);
                    }

                    let total = 0;
                    let items = [];

                    for (let p of sortedProducts) {
                        if (total + p.price <= this.giftBudget) {
                            items.push(p);
                            total += p.price;
                        }
                    }

                    this.generatedBundle = {
                        recipientLabel: recipientText,
                        items: items,
                        totalPrice: total,
                        impact: items.length * 2 + ' Pekerja Lokal & 1 Desa Terbantu',
                        aiReasoning: `AI menganalisis kriteria "${recipientText}" dan menyusun paket berisi ${items.length} produk pilihan UMKM dengan total harga ${this.formatRupiah(total)}.`
                    };
                },

                addBundleToCart() {
                    if (!this.generatedBundle || !this.generatedBundle.items.length) return;
                    for (let item of this.generatedBundle.items) {
                        this.addToCart(item);
                    }
                },

                generateAiComparison() {
                    if (!this.compareProduct1 || !this.compareProduct2) return;
                    const p1 = this.compareProduct1;
                    const p2 = this.compareProduct2;

                    this.aiCompareAnalysis = {
                        verdict: p1.price < p2.price ? `${p1.name} menawarkan harga hemat, sedangkan ${p2.name} unggul di aroma specialty khas Bromo.` : `${p2.name} sangat disukai pencinta rasa unik.`,
                        recommendation: p1.impact_score > p2.impact_score ? `Pilih ${p1.name} untuk Impact Score lebih tinggi!` : `Pilih ${p2.name} untuk varian rasa unggulan!`
                    };
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
                }
            }
        }
    </script>
</body>
</html>
