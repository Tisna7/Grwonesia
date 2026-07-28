<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Grownesia User Dashboard - Ekosistem Pemberdayaan UMKM Indonesia Berbasis AI">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard User — Grownesia AI</title>

    <!-- Google Fonts Harmonization with Business Layout -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased"
      x-data="grownesiaUserDashboard()"
      x-init="initDashboard()">
    <div class="ambient-glow"></div>

    <!-- LEFT SIDEBAR NAVIGATION (LOGGED-IN USER LAYOUT) -->
    <aside class="fixed inset-y-0 left-0 z-40 w-64 glass-card !rounded-none border-r border-primary-400/15 flex flex-col transition-transform duration-300 lg:translate-x-0"
           :class="sidebarMobileOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- Sidebar Brand -->
        <div class="px-5 py-6 border-b border-primary-400/10">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl ai-gradient flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-primary-600/40" style="font-family: 'Space Grotesk'">G</span>
                <span>
                    <span class="block text-lg font-bold tracking-tight text-white leading-none" style="font-family: 'Space Grotesk'">Grownesia</span>
                    <span class="block mt-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-primary-300/80">Portal Pembeli</span>
                </span>
            </a>
        </div>

        <!-- Sidebar Menu Links -->
        <nav class="flex-1 px-3 py-3 overflow-y-auto">
            <p class="section-label">Navigasi Utama</p>
            <div class="space-y-1">
                <button @click="activeTab = 'katalog'; sidebarMobileOpen = false" class="sidebar-link" :class="(activeTab === 'katalog' || activeTab === 'detail') && 'active'">
                    <x-icon name="package"/> Katalog Produk
                </button>
                <button @click="activeTab = 'ai-assistant'; sidebarMobileOpen = false" class="sidebar-link" :class="activeTab === 'ai-assistant' && 'active'">
                    <x-icon name="sparkles"/> AI Personal Shopper
                    <span class="ml-auto w-2 h-2 rounded-full bg-emerald-400 animate-ping shrink-0"></span>
                </button>
                <button @click="activeTab = 'ai-gift'; sidebarMobileOpen = false" class="sidebar-link" :class="activeTab === 'ai-gift' && 'active'">
                    <x-icon name="zap"/> Rekomendasi Kado AI
                </button>
                <button @click="activeTab = 'ai-compare'; sidebarMobileOpen = false" class="sidebar-link" :class="activeTab === 'ai-compare' && 'active'">
                    <x-icon name="activity"/> Komparasi Produk AI
                </button>
            </div>

            <p class="section-label">Belanja & Transaksi</p>
            <div class="space-y-1">
                <button @click="activeTab = 'cart'; sidebarMobileOpen = false" class="sidebar-link" :class="activeTab === 'cart' && 'active'">
                    <x-icon name="store"/> Keranjang Belanja
                    <span x-show="cartTotalCount > 0" x-text="cartTotalCount" class="ml-auto badge-pill bg-primary-500/20 text-primary-300 border-primary-500/35 !text-[10px] !px-2 !py-0"></span>
                </button>
                <button @click="activeTab = 'favorites'; sidebarMobileOpen = false" class="sidebar-link" :class="activeTab === 'favorites' && 'active'">
                    <x-icon name="flame"/> Produk Favorit
                    <span x-show="favorites.length > 0" x-text="favorites.length" class="ml-auto badge-pill bg-rose-500/12 text-rose-400 border-rose-500/35 !text-[10px] !px-2 !py-0"></span>
                </button>
                <button @click="activeTab = 'orders'; sidebarMobileOpen = false" class="sidebar-link" :class="(activeTab === 'orders' || activeTab === 'write-review') && 'active'">
                    <x-icon name="file-text"/> Riwayat & Ulasan
                    <span x-text="ordersHistory.length" class="ml-auto badge-pill bg-emerald-500/12 text-emerald-400 border-emerald-500/35 !text-[10px] !px-2 !py-0"></span>
                </button>
                <button @click="activeTab = 'tracking'; sidebarMobileOpen = false" class="sidebar-link" :class="activeTab === 'tracking' && 'active'">
                    <x-icon name="truck"/> Lacak Pengiriman
                    <span class="ml-auto badge-pill bg-sky-500/12 text-sky-400 border-sky-500/35 !text-[10px] !px-2 !py-0">Live</span>
                </button>
            </div>

            <p class="section-label">Koneksi Ekosistem</p>
            <div class="space-y-1">
                <button @click="activeTab = 'profile'; sidebarMobileOpen = false" class="sidebar-link" :class="activeTab === 'profile' && 'active'">
                    <x-icon name="sliders"/> Profil & Pengaturan
                </button>
            </div>
        </nav>

        <!-- Sidebar Footer -->
        <div class="px-4 py-4 border-t border-primary-400/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 shrink-0 rounded-full ai-gradient flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name ?? 'B', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-white truncate" x-text="userProfile.name"></p>
                    <p class="text-[10px] text-slate-500 truncate">{{ Auth::user()->email ?? 'user@grownesia.id' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar" class="p-2 rounded-lg text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition-colors">
                        <x-icon name="log-out" class="w-4 h-4"/>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarMobileOpen" x-cloak @click="sidebarMobileOpen = false" class="fixed inset-0 z-30 bg-black/60 lg:hidden"></div>

    <!-- RIGHT MAIN WORKSPACE AREA -->
    <div class="lg:pl-64 flex flex-col min-h-screen">
        
        <!-- TOP HEADER FOR DASHBOARD WORKSPACE -->
        <header class="sticky top-0 z-20 glass-card !rounded-none border-b border-primary-400/10 px-6 py-4 flex items-center justify-between backdrop-blur-xl">
            <div class="flex items-center gap-3">
                <button class="lg:hidden text-slate-300 p-1" @click="sidebarMobileOpen = true">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>

            <!-- Header Quick Tools -->
            <div class="flex items-center gap-3">
                <!-- Notification Bell -->
                <div class="relative" x-data="{ openNotif: false }">
                    <button @click="openNotif = !openNotif" class="relative p-2 rounded-lg text-slate-400 hover:text-white hover:bg-primary-500/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span x-show="unreadNotifCount > 0" x-text="unreadNotifCount" class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center"></span>
                    </button>

                    <div x-show="openNotif" @click.away="openNotif = false" class="absolute right-0 mt-2 w-80 glass-card p-4 z-50 space-y-3" style="display: none;">
                        <div class="flex items-center justify-between border-b border-primary-400/10 pb-2">
                            <h4 class="text-xs font-bold text-white">Notifikasi</h4>
                            <button @click="markAllNotifRead()" class="text-[10px] text-slate-500 hover:text-white">Tandai Dibaca</button>
                        </div>
                        <div class="space-y-2 max-h-60 overflow-y-auto text-xs">
                            <template x-for="n in notifications" :key="n.id">
                                <div class="p-2.5 rounded-xl border border-primary-400/10 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-slate-200" x-text="n.title"></span>
                                        <span class="text-[9px] text-slate-600" x-text="n.time"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-500" x-text="n.desc"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- AI Assistant Quick Button -->
                <button @click="activeTab = 'ai-assistant'" class="btn-primary !py-2 !px-3.5 !text-xs !rounded-xl !gap-1.5">
                    <x-icon name="sparkles" class="w-4 h-4"/> <span class="hidden sm:inline">AI Assistant</span>
                </button>

                <!-- Cart Icon -->
                <button @click="activeTab = 'cart'" class="relative p-2 rounded-lg text-slate-400 hover:text-white hover:bg-primary-500/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span x-show="cartTotalCount > 0" x-text="cartTotalCount" class="absolute -top-1 -right-1 bg-magenta text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center"></span>
                </button>

                <!-- Profile Avatar -->
                <button @click="activeTab = 'profile'" class="w-9 h-9 rounded-full ai-gradient flex items-center justify-center text-xs font-bold text-white hover:opacity-90 transition">
                    {{ strtoupper(substr(Auth::user()->name ?? 'B', 0, 1)) }}
                </button>
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
                showFloatingAiWidget: false,
                sidebarMobileOpen: false,

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

                ordersHistory: @json($ordersHistory ?? []),

                trackingOrder: null,
                copyToast: false,
                isRefreshingTracking: false,

                reviewingOrder: null,
                newReviewForm: {
                    rating: 5,
                    comment: ''
                },

                notifications: [
                    { id: 1, title: 'Pesanan Dikirim', desc: 'Pesanan #GRW-2026-7812 sedang dibawa kurir ke lokasi Anda.', time: '10 menit yang lalu' },
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
                    if (this.ordersHistory.length > 0) {
                        this.trackingOrder = this.ordersHistory[1] || this.ordersHistory[0];
                    }
                    this.generateGiftBundle();
                },

                openTracking(order) {
                    this.trackingOrder = order;
                    this.activeTab = 'tracking';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                copyTrackingNumber(resi) {
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(resi);
                    }
                    this.copyToast = true;
                    setTimeout(() => { this.copyToast = false; }, 2500);
                },

                async refreshTracking() {
                    this.isRefreshingTracking = true;
                    if (this.trackingOrder && this.trackingOrder.db_id) {
                        try {
                            let res = await fetch('/user/shipping/' + this.trackingOrder.db_id);
                            if (res.ok) {
                                let data = await res.json();
                                if (data.success && data.order) {
                                    this.trackingOrder = data.order;
                                    let idx = this.ordersHistory.findIndex(o => o.db_id === data.order.db_id);
                                    if (idx !== -1) {
                                        this.ordersHistory[idx] = data.order;
                                    }
                                }
                            }
                        } catch (err) {
                            console.error('Error refreshing tracking info:', err);
                        }
                    }
                    setTimeout(() => { this.isRefreshingTracking = false; }, 600);
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
                        return matchesSearch && matchesCat;
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
    <!-- FLOATING AI ASSISTANT OVERLAY WIDGET (DESAIN.MD SPEC 4.1) -->
    <div class="fixed bottom-6 right-6 z-50">
        <!-- Floating Trigger Button with AI Pulsing Glow -->
        <button @click="showFloatingAiWidget = !showFloatingAiWidget" 
                class="relative px-4 py-3 rounded-full bg-gradient-to-r from-primary-600 via-primary-500 to-magenta text-white font-bold text-xs shadow-2xl ai-active-pulse hover:scale-105 transition flex items-center gap-2 border border-primary-400/25">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
            <svg class="w-5 h-5 text-amber-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span class="font-bold tracking-tight">Grownesia AI Assistant</span>
        </button>

        <!-- Floating Drawer Overlay Container -->
        <div x-show="showFloatingAiWidget" x-cloak 
             @click.away="showFloatingAiWidget = false"
             class="absolute bottom-16 right-0 w-80 sm:w-96 rounded-3xl bg-slate-950/95 border border-primary-400/25 shadow-2xl backdrop-blur-2xl overflow-hidden flex flex-col justify-between" style="box-shadow: 0 20px 50px rgba(124, 58, 237, 0.5);">
            
            <!-- Header Widget (linear-gradient(135deg, #7C3AED 0%, #EC4899 100%)) -->
            <div class="p-4 bg-gradient-to-r from-primary-600 to-magenta text-white flex items-center justify-between shadow-md">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold tracking-tight font-heading">Grownesia AI Shopping Assistant</h4>
                        <span class="text-[10px] text-slate-200/90 font-medium">Empowered Local Economy with AI</span>
                    </div>
                </div>
                <button @click="showFloatingAiWidget = false" class="text-white/80 hover:text-white p-1 font-bold">✕</button>
            </div>

            <!-- Quick Prompt Chips (Desain.md Section 4.1) -->
            <div class="p-3 glass-card !bg-primary-950/20 border-b border-primary-400/10 space-y-1.5">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Quick Prompts:</span>
                <div class="flex flex-wrap gap-1.5">
                    <button @click="sendAiQuery('Saya ingin hadiah untuk ibu umur 50 tahun.')" class="px-2.5 py-1 rounded-full bg-primary-900/40 hover:bg-primary-800 border border-primary-400/20 text-primary-200 text-[11px] font-semibold transition">
                        🎁 Hadiah Ibu 50th
                    </button>
                    <button @click="sendAiQuery('Saya punya budget Rp300.000 untuk hampers.')" class="px-2.5 py-1 rounded-full bg-primary-900/40 hover:bg-primary-800 border border-primary-400/20 text-primary-200 text-[11px] font-semibold transition">
                        💰 Budget Rp300.000
                    </button>
                    <button @click="sendAiQuery('Kopi gula aren atau kopi klepon mana yang lebih disukai?')" class="px-2.5 py-1 rounded-full bg-primary-900/40 hover:bg-primary-800 border border-primary-400/20 text-primary-200 text-[11px] font-semibold transition">
                        ⚖️ Komparasi Kopi
                    </button>
                </div>
            </div>

            <!-- Bubble Chat Content Area -->
            <div class="p-4 space-y-3 max-h-72 overflow-y-auto text-xs">
                <template x-for="(msg, idx) in chatMessages" :key="'fmsg-'+idx">
                    <div :class="msg.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="msg.sender === 'user' ? 'bg-[#5B21B6] text-white rounded-2xl rounded-tr-none p-3 max-w-[85%]' : 'glass-card !bg-primary-950/30 border border-primary-400/20 text-slate-200 rounded-2xl rounded-tl-none p-3 max-w-[90%] space-y-2'" style="box-shadow: inset 0 1px 0 rgba(255,255,255,0.05)">
                            <p x-text="msg.text" class="leading-relaxed"></p>
                            <template x-if="msg.products && msg.products.length">
                                <div class="pt-2 space-y-2 border-t border-primary-400/10">
                                    <template x-for="p in msg.products" :key="'fp-'+p.id">
                                        <div class="p-2 rounded-xl bg-primary-900/30 border border-primary-400/15 flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <img :src="p.image" class="w-9 h-9 rounded-lg object-cover border border-primary-400/20">
                                                <div class="min-w-0">
                                                    <p class="font-bold text-white truncate text-[11px]" x-text="p.name"></p>
                                                    <p class="text-primary-300 text-[10px]" x-text="formatRupiah(p.price)"></p>
                                                </div>
                                            </div>
                                            <button @click="addToCart(p)" class="px-2.5 py-1 rounded-lg bg-primary-600 hover:bg-primary-500 text-white font-bold text-[10px] shrink-0">
                                                + Beli
                                            </button>
                                        </div>
                                    </template>
                                    <button @click="addBundleToCart()" class="w-full py-1.5 rounded-xl bg-gradient-to-r from-primary-600 to-magenta text-white font-bold text-[11px] shadow-md hover:opacity-90 transition">
                                        Tambahkan Semua Paket ke Keranjang
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Input Form -->
            <form @submit.prevent="sendAiQuery(customChatInput)" class="p-3 glass-card !bg-primary-950/40 border-t border-primary-400/10 flex gap-2">
                <input type="text" x-model="customChatInput" placeholder="Tanyakan rekomendasi AI..." class="form-input flex-1 !py-2 text-xs">
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-bold text-xs shadow-md">Kirim</button>
            </form>
        </div>
    </div>
</body>
</html>
