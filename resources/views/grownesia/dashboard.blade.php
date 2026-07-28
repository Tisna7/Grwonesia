@extends('layouts.sidebar')

@section('content')

<div class="space-y-8">
    
    <!-- TOP STATS BANNER (SHOWN ON HOME/KATALOG TAB) (DESAIN.MD SPEC 1.1 & 6.1) -->
    <div x-show="activeTab === 'katalog'" class="p-6 sm:p-8 rounded-3xl glass-card border-primary-400/15 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-primary-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center relative z-10">
            <div class="lg:col-span-8 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-500/20 border border-primary-400/20 text-primary-300 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>AI Shopping Assistant Active</span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold text-white font-heading tracking-tight">
                    Belanja Produk UMKM <span class="ai-gradient-text">Terbaik AI</span>
                </h1>

                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed max-w-2xl">
                    Jelajahi produk UMKM Indonesia pilihan berbasis AI, dapatkan rekomendasi hampers personal, baca ulasan pembeli terverifikasi, dan nikmati belanja yang aman & nyaman.
                </p>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button @click="showFloatingAiWidget = true" class="btn-primary text-xs shadow-lg">
                        <svg class="w-4 h-4 text-amber-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Buka AI Shopping Assistant</span>
                    </button>
                    <button @click="activeTab = 'orders'" class="px-4 py-2.5 rounded-xl glass-card !bg-primary-950/30 hover:bg-primary-900 border-primary-400/15 text-primary-200 font-bold text-xs transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>Beri Ulasan Pesanan</span>
                    </button>
                </div>
            </div>

            <!-- Quick Shopping Summary Widget -->
            <div class="lg:col-span-4 p-5 rounded-2xl bg-gradient-to-br from-primary-950/90 via-primary-900/60 to-indigo-950/90 border-primary-400/15 space-y-3 text-center shadow-xl">
                <div class="inline-flex items-center gap-1.5 text-[11px] font-bold text-primary-300 px-3 py-1 rounded-full bg-primary-900/30 border-primary-400/15">
                    <svg class="w-3.5 h-3.5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>Status Transaksi Anda</span>
                </div>
                <div class="flex items-center justify-around py-1">
                    <div>
                        <div class="text-2xl font-extrabold text-white font-heading" x-text="ordersHistory.length"></div>
                        <div class="text-[10px] text-primary-300 font-semibold">Total Pesanan</div>
                    </div>
                    <div class="w-px h-8 bg-primary-500/15"></div>
                    <div>
                        <div class="text-2xl font-extrabold text-white font-heading" x-text="favorites.length"></div>
                        <div class="text-[10px] text-primary-300 font-semibold">Produk Favorit</div>
                    </div>
                </div>
                <button @click="activeTab = 'tracking'" class="w-full py-2 rounded-xl bg-primary-900/40 hover:bg-primary-800 border border-primary-400/20 text-primary-200 text-xs font-bold flex items-center justify-center gap-1.5 transition">
                    <span>Lacak Pengiriman Aktif</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- DEDICATED PAGE 1: KATALOG PRODUK -->
    <div x-show="activeTab === 'katalog'" class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-white font-heading">Katalog Produk UMKM Nusantara</h3>
                <p class="text-xs text-slate-500">PILIH DAN BELI PRODUK BERKUALITAS SECARA LANGSUNG</p>
            </div>

            <!-- Search Bar inside Workspace -->
            <div class="relative w-full md:w-80">
                <input type="text"
                       x-model="searchQuery"
                       placeholder="Cari batik, kopi, kerajinan..."
                       class="w-full pl-9 pr-4 py-2 rounded-xl glass-card !bg-primary-950/30 border-primary-400/15 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-primary-400">
                <svg class="w-4 h-4 text-primary-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <!-- Category Filter Pills -->
        <div class="flex flex-wrap items-center justify-between gap-3 bg-primary-950/40 p-3 rounded-2xl border-primary-400/10 text-xs">
            <div class="flex flex-wrap items-center gap-2">
                <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-primary-600 text-white border-purple-400' : 'bg-primary-900/20 text-primary-300 hover:bg-primary-800 border-primary-400/10'" class="px-3 py-1.5 rounded-xl border font-semibold transition">
                    Semua
                </button>
                <button @click="activeCategory = 'kopi'" :class="activeCategory === 'kopi' ? 'bg-primary-600 text-white border-purple-400' : 'bg-primary-900/20 text-primary-300 hover:bg-primary-800 border-primary-400/10'" class="px-3 py-1.5 rounded-xl border font-semibold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                    <span>Kopi & Minuman</span>
                </button>
                <button @click="activeCategory = 'batik'" :class="activeCategory === 'batik' ? 'bg-primary-600 text-white border-purple-400' : 'bg-primary-900/20 text-primary-300 hover:bg-primary-800 border-primary-400/10'" class="px-3 py-1.5 rounded-xl border font-semibold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-23"/></svg>
                    <span>Batik & Fashion</span>
                </button>
                <button @click="activeCategory = 'kerajinan'" :class="activeCategory === 'kerajinan' ? 'bg-primary-600 text-white border-purple-400' : 'bg-primary-900/20 text-primary-300 hover:bg-primary-800 border-primary-400/10'" class="px-3 py-1.5 rounded-xl border font-semibold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>Kerajinan & Craft</span>
                </button>
                <button @click="activeCategory = 'makanan'" :class="activeCategory === 'makanan' ? 'bg-primary-600 text-white border-purple-400' : 'bg-primary-900/20 text-primary-300 hover:bg-primary-800 border-primary-400/10'" class="px-3 py-1.5 rounded-xl border font-semibold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Makanan Ringan</span>
                </button>
            </div>
        </div>

        <!-- Product Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="product in filteredProducts" :key="product.id">
                <div class="group rounded-2xl glass-card border-primary-400/10 overflow-hidden glass-card-hover transition duration-300 flex flex-col justify-between relative">
                    <div>
                        <div class="relative h-48 overflow-hidden bg-primary-950 cursor-pointer" @click="openProductDetail(product)">
                            <img :src="product.image" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-md glass-card !bg-primary-950/30 border border-primary-400/20 text-[10px] text-primary-200 flex items-center gap-1">
                                <svg class="w-3 h-3 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span x-text="product.region"></span>
                            </div>

                            <!-- Heart Favorite Button -->
                            <button @click.stop="toggleFavorite(product)" class="absolute top-2.5 right-2.5 p-1.5 rounded-full glass-card !bg-primary-950/30 text-white border border-primary-400/20 hover:scale-110 transition z-10">
                                <svg class="w-4 h-4" :class="isFavorite(product.id) ? 'text-red-500 fill-current' : 'text-primary-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>

                        <div class="p-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-primary-400 uppercase tracking-wider" x-text="product.umkm"></span>
                                <button @click="openProductDetail(product)" class="text-[10px] text-primary-300 hover:text-white underline flex items-center gap-1">
                                    <span>Detail & Review</span>
                                    <svg class="w-3 h-3 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                            <h4 @click="openProductDetail(product)" class="text-sm font-bold text-white group-hover:text-primary-300 transition line-clamp-1 cursor-pointer" x-text="product.name"></h4>
                            <p class="text-xs text-slate-500 line-clamp-2" x-text="product.description"></p>
                        </div>
                    </div>

                    <div class="p-4 pt-0 space-y-2.5">
                        <div class="flex items-baseline justify-between border-t border-primary-400/10 pt-2.5">
                            <div>
                                <span class="text-[11px] text-slate-600 line-through mr-1" x-text="formatRupiah(product.original_price)"></span>
                                <span class="text-base font-extrabold text-white" x-text="formatRupiah(product.price)"></span>
                            </div>
                            <button @click="compareProduct2 = product; activeTab = 'ai-compare'" class="text-[11px] text-primary-300 hover:text-white underline flex items-center gap-1">
                                <svg class="w-3 h-3 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Bandingkan</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <button @click="addToCart(product)" class="w-full py-2 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-bold text-xs transition">
                                + Keranjang
                            </button>
                            <button @click="addToCart(product)" class="w-full py-2 rounded-xl bg-primary-950 hover:bg-primary-900 border-primary-400/15 text-primary-200 font-bold text-xs transition">
                                Beli Langsung
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- DEDICATED PAGE 2: DETAIL PRODUK & REVIEWS -->
    <div x-show="activeTab === 'detail' && selectedProductDetail" class="space-y-6">
        <button @click="activeTab = 'katalog'" class="inline-flex items-center gap-2 text-xs text-primary-300 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Katalog Produk</span>
        </button>

        <template x-if="selectedProductDetail">
            <div class="space-y-6">
                <!-- Main Product Detail Card -->
                <div class="p-6 sm:p-8 rounded-3xl glass-card border-primary-400/15 shadow-2xl space-y-6">
                    <div class="flex items-center justify-between border-b border-primary-400/10 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary-800/30 text-primary-300 border border-primary-400/20" x-text="selectedProductDetail.category"></span>
                            <span class="text-xs text-emerald-400 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span x-text="selectedProductDetail.rating"></span> (<span x-text="getProductReviews(selectedProductDetail.id).length + 45"></span> ulasan pembeli)
                            </span>
                        </div>
                        <button @click="toggleFavorite(selectedProductDetail)" class="px-3 py-1.5 rounded-xl bg-primary-950 border-primary-400/15 text-xs font-bold text-primary-200 flex items-center gap-1.5">
                            <svg class="w-4 h-4" :class="isFavorite(selectedProductDetail.id) ? 'text-red-500 fill-current' : 'text-primary-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span x-text="isFavorite(selectedProductDetail.id) ? 'Disimpan di Favorit' : '+ Simpan ke Favorit'"></span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                        <div class="md:col-span-5 h-72 rounded-2xl overflow-hidden bg-primary-950 border border-primary-400/20">
                            <img :src="selectedProductDetail.image" class="w-full h-full object-cover">
                        </div>

                        <div class="md:col-span-7 space-y-4">
                            <span class="text-xs font-bold text-primary-400 uppercase tracking-widest" x-text="'Penjual: ' + selectedProductDetail.umkm + ' (' + selectedProductDetail.region + ')'"></span>
                            <h2 class="text-2xl font-extrabold text-white font-heading" x-text="selectedProductDetail.name"></h2>
                            <p class="text-xs sm:text-sm text-slate-400 leading-relaxed" x-text="selectedProductDetail.description"></p>
                            
                            <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-xs space-y-1">
                                <div class="flex items-center gap-1.5 font-bold">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Social Impact Certified: <span x-text="selectedProductDetail.impact_text"></span></span>
                                </div>
                                <p class="text-[11px] text-emerald-400/80">Setiap pembelian produk ini berkontribusi langsung pada pendapatan keluarga mitra UMKM lokal.</p>
                            </div>

                            <div class="flex items-baseline gap-3 pt-2">
                                <span class="text-3xl font-extrabold text-white" x-text="formatRupiah(selectedProductDetail.price)"></span>
                                <span class="text-sm text-slate-600 line-through" x-text="formatRupiah(selectedProductDetail.original_price)"></span>
                            </div>

                            <div class="flex flex-wrap items-center gap-3 pt-2">
                                <button @click="addToCart(selectedProductDetail)" class="px-6 py-3.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-bold text-xs transition shadow-lg">
                                    + Tambah ke Keranjang
                                </button>
                                <button @click="addToCart(selectedProductDetail)" class="px-6 py-3.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs transition shadow-lg">
                                    Beli & Checkout Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Reviews Section (Ulasan Pembeli) -->
                <div class="p-6 sm:p-8 rounded-3xl glass-card border-primary-400/15 space-y-6 shadow-xl">
                    <div class="flex items-center justify-between border-b border-primary-400/10 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-white font-heading flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span>Ulasan & Rating Pembeli</span>
                                <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Verified Buyers</span>
                            </h3>
                            <p class="text-xs text-slate-500">Pengalaman nyata dari pembeli yang telah membantu UMKM ini.</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-extrabold text-white" x-text="selectedProductDetail.rating + ' / 5.0'"></div>
                            <span class="text-[11px] text-primary-400">Rating Keseluruhan</span>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="space-y-4">
                        <template x-for="rev in getProductReviews(selectedProductDetail.id)" :key="'rev-'+rev.id">
                            <div class="p-4 rounded-2xl glass-card !bg-primary-950/20 border-primary-400/10 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-primary-800 text-white font-bold flex items-center justify-center text-xs" x-text="rev.userName.charAt(0)"></div>
                                        <div>
                                            <span class="text-xs font-bold text-white" x-text="rev.userName"></span>
                                            <span class="text-[10px] ml-1.5 px-2 py-0.5 rounded bg-emerald-950 text-emerald-300 border border-emerald-500/30">Pembeli Terverifikasi</span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-slate-500" x-text="rev.date"></span>
                                </div>

                                <div class="flex items-center gap-1 text-amber-400 text-xs">
                                    <template x-for="star in rev.rating" :key="'st-'+star">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    </template>
                                </div>

                                <p class="text-xs text-slate-300 leading-relaxed" x-text="rev.comment"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- DEDICATED PAGE 3: KERANJANG & CHECKOUT -->
    <div x-show="activeTab === 'cart'" class="space-y-6">
        <div class="flex items-center justify-between border-b border-primary-400/10 pb-4">
            <div>
                <h3 class="text-xl font-bold text-white font-heading">Halaman Keranjang & Checkout Pembayaran</h3>
                <p class="text-xs text-slate-500">Periksa item belanja Anda dan lakukan checkout secara aman.</p>
            </div>
            <button @click="activeTab = 'katalog'" class="text-xs text-primary-300 hover:text-white underline">← Kembali Belanja</button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left: Cart Items List -->
            <div class="lg:col-span-7 space-y-4">
                <h4 class="text-sm font-bold text-primary-300 uppercase tracking-wider">Item dalam Keranjang</h4>

                <template x-if="cart.length === 0">
                    <div class="p-8 text-center glass-card rounded-2xl border-primary-400/10 space-y-3">
                        <p class="text-xs text-slate-500">Keranjang Anda masih kosong.</p>
                        <button @click="activeTab = 'katalog'" class="px-4 py-2 rounded-xl bg-primary-600 text-white text-xs font-bold">Mulai Belanja</button>
                    </div>
                </template>

                <template x-for="(item, idx) in cart" :key="'citem-'+idx">
                    <template x-if="item.product">
                        <div class="p-4 rounded-2xl glass-card border-primary-400/10 flex gap-4 items-center">
                            <img :src="item.product.image" class="w-16 h-16 rounded-xl object-cover border border-primary-400/20 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-white truncate" x-text="item.product.name"></h4>
                                <p class="text-xs text-slate-500" x-text="item.product.umkm"></p>
                                <p class="text-xs font-bold text-primary-300 mt-1" x-text="formatRupiah(item.product.price)"></p>
                            </div>
                            <div class="flex items-center gap-2 bg-primary-950 rounded-xl p-1.5 border-primary-400/15">
                                <button @click="updateQty(idx, -1)" class="w-6 h-6 rounded text-primary-300 hover:bg-primary-800 text-xs font-bold flex items-center justify-center">-</button>
                                <span class="text-xs font-bold px-2 text-white" x-text="item.qty"></span>
                                <button @click="updateQty(idx, 1)" class="w-6 h-6 rounded text-primary-300 hover:bg-primary-800 text-xs font-bold flex items-center justify-center">+</button>
                            </div>
                        </div>
                    </template>
                </template>
            </div>

            <!-- Right: Checkout Details & Payment -->
            <div class="lg:col-span-5 space-y-4">
                <div class="p-6 rounded-3xl glass-card border-primary-400/15 space-y-4 shadow-xl">
                    <h4 class="text-sm font-bold text-white border-b border-primary-400/10 pb-2">Informasi Alamat & Pembayaran</h4>

                    <div class="space-y-3 text-xs">
                        <div>
                            <label class="block font-semibold text-primary-200 mb-1">Alamat Pengiriman Utama</label>
                            <textarea x-model="userProfile.address" class="w-full p-3 rounded-xl glass-card !bg-primary-950/30 border-primary-400/15 text-slate-200" rows="2"></textarea>
                        </div>

                        <div>
                            <label class="block font-semibold text-primary-200 mb-1">Pilih Metode Pembayaran</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button @click="selectedPaymentMethod = 'qris'" :class="selectedPaymentMethod === 'qris' ? 'border-primary-400 bg-primary-800/60' : 'border-primary-400/15 glass-card !bg-primary-950/30'" class="p-2.5 rounded-xl border text-center font-bold text-white text-[11px]">
                                    QRIS Instant
                                </button>
                                <button @click="selectedPaymentMethod = 'gopay'" :class="selectedPaymentMethod === 'gopay' ? 'border-primary-400 bg-primary-800/60' : 'border-primary-400/15 glass-card !bg-primary-950/30'" class="p-2.5 rounded-xl border text-center font-bold text-white text-[11px]">
                                    GoPay / OVO
                                </button>
                                <button @click="selectedPaymentMethod = 'bank'" :class="selectedPaymentMethod === 'bank' ? 'border-primary-400 bg-primary-800/60' : 'border-primary-400/15 glass-card !bg-primary-950/30'" class="p-2.5 rounded-xl border text-center font-bold text-white text-[11px]">
                                    Transfer Bank
                                </button>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-xs flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Social Impact: <strong x-text="calculateCartImpactText()"></strong></span>
                        </div>

                        <div class="p-3.5 rounded-xl glass-card !bg-primary-950/40 border-primary-400/15 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Subtotal Produk</span>
                                <span class="text-white font-semibold" x-text="formatRupiah(cartTotalPrice)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Ongkos Kirim (Subsidi UMKM)</span>
                                <span class="text-emerald-400 font-semibold">GRATIS</span>
                            </div>
                            <div class="pt-2 border-t border-primary-400/10 flex justify-between text-base font-extrabold">
                                <span class="text-primary-200">Total Akhir:</span>
                                <span class="text-primary-300" x-text="formatRupiah(cartTotalPrice)"></span>
                            </div>
                        </div>
                    </div>

                    <button @click="processPaymentSuccess()" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs transition shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Bayar Sekarang & Terbitkan Sertifikat Dampak</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- DEDICATED PAGE 4: PASCA TRANSAKSI -->
    <div x-show="activeTab === 'success-impact'" class="space-y-6">
        <div class="max-w-2xl mx-auto p-8 rounded-3xl glass-card border border-primary-400/25 text-center space-y-5 shadow-2xl relative overflow-hidden">
            <div class="w-20 h-20 rounded-full bg-emerald-500/20 border-2 border-emerald-400 text-emerald-400 flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>

            <h2 class="text-2xl font-extrabold text-white font-heading">Pembayaran Berhasil!</h2>
            <p class="text-xs text-slate-300 max-w-md mx-auto">Terima kasih atas pesanan Anda. Transaksi Anda membantu menggerakkan roda ekonomi UMKM lokal Indonesia secara langsung!</p>

            <div class="p-6 rounded-2xl bg-gradient-to-br from-primary-900/90 via-primary-950 to-indigo-950 border border-primary-400/25 text-left space-y-3 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-primary-400 uppercase tracking-widest">Sertifikat Dampak Sosial (Verified Impact)</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-semibold border border-emerald-400/30">Verified</span>
                </div>
                <div class="text-lg font-bold text-white" x-text="lastOrderImpactSummary"></div>
                <p class="text-xs text-slate-400 leading-relaxed">Pendapatan dari transaksi ini diteruskan ke UMKM mitra Grownesia di Solo, Palembang, dan Kebumen untuk mendukung upah layak bagi para pengrajin wanita dan petani lokal.</p>
            </div>

            <div class="flex justify-center gap-4 pt-2">
                <button @click="activeTab = 'orders'" class="px-6 py-3 rounded-xl bg-primary-950 border-primary-400/15 hover:bg-primary-900 text-primary-200 font-bold text-xs">
                    Lihat Riwayat & Beri Ulasan
                </button>
                <button @click="activeTab = 'katalog'" class="px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-bold text-xs">
                    Kembali ke Katalog
                </button>
            </div>
        </div>
    </div>



    <!-- DEDICATED PAGE 6: AI SHOPPING ASSISTANT -->
    <div x-show="activeTab === 'ai-assistant'" class="space-y-6">
        <div class="flex items-center justify-between border-b border-primary-400/10 pb-4">
            <div>
                <h3 class="text-xl font-bold text-white font-heading">AI Shopping Assistant Interactive</h3>
                <p class="text-xs text-slate-500">Asisten kecerdasan buatan untuk rekomendasi produk dan kado personal.</p>
            </div>
        </div>

        <div class="p-6 rounded-3xl glass-card border-primary-400/15 shadow-2xl space-y-4 min-h-[480px] flex flex-col justify-between">
            <div class="space-y-4 overflow-y-auto max-h-[400px] text-xs pr-2" id="fullChatContainer">
                <div class="p-4 rounded-2xl bg-primary-900/30 border-primary-400/15 text-primary-200 space-y-2">
                    <div class="flex items-center gap-2 font-semibold text-primary-300">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary-400 animate-pulse"></span> Grownesia AI Bot
                    </div>
                    <p>Halo {{ Auth::user()->name ?? 'Budi' }}! Saya AI Personal Shopper Anda. Tanyakan kebutuhan Anda atau pilih kueri favorit berikut:</p>
                    <div class="pt-2 flex flex-wrap gap-2">
                        <button @click="sendAiQuery('Saya ingin hadiah untuk ibu umur 50 tahun.')" class="px-3 py-1.5 rounded-xl glass-card !bg-primary-950/30 hover:bg-primary-800 border border-primary-400/20 text-primary-200 text-xs transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Hadiah Ibu 50th</span>
                        </button>
                        <button @click="sendAiQuery('Saya punya budget Rp300.000 untuk hampers.')" class="px-3 py-1.5 rounded-xl glass-card !bg-primary-950/30 hover:bg-primary-800 border border-primary-400/20 text-primary-200 text-xs transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span>Hampers Budget 300rb</span>
                        </button>
                        <button @click="sendAiQuery('Kopi gula aren atau kopi klepon mana yang lebih disukai?')" class="px-3 py-1.5 rounded-xl glass-card !bg-primary-950/30 hover:bg-primary-800 border border-primary-400/20 text-primary-200 text-xs transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                            <span>Komparasi Kopi</span>
                        </button>
                    </div>
                </div>

                <template x-for="(msg, index) in chatMessages" :key="'fmsg-'+index">
                    <div :class="msg.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="msg.sender === 'user' ? 'bg-primary-600 text-white rounded-2xl rounded-tr-none p-4 max-w-[80%]' : 'glass-card !bg-primary-950/40 border-primary-400/15 text-slate-200 rounded-2xl rounded-tl-none p-4 max-w-[85%] space-y-3'">
                            <p x-text="msg.text"></p>
                            
                            <template x-if="msg.products && msg.products.length">
                                <div class="mt-2 space-y-2 pt-2 border-t border-primary-400/10">
                                    <template x-for="prod in msg.products" :key="'fprod-'+prod.id">
                                        <div class="p-3 rounded-xl bg-primary-900/20 border-primary-400/10 flex items-center gap-3">
                                            <img :src="prod.image" class="w-14 h-14 rounded-lg object-cover border border-primary-400/20">
                                            <div class="flex-1 min-w-0">
                                                <h5 class="text-xs font-bold text-white truncate" x-text="prod.name"></h5>
                                                <p class="text-[11px] text-emerald-400 font-medium" x-text="prod.impact_text"></p>
                                                <p class="text-xs font-semibold text-primary-300" x-text="formatRupiah(prod.price)"></p>
                                            </div>
                                            <button @click="addToCart(prod)" class="px-3 py-2 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-semibold shrink-0">
                                                + Beli
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <form @submit.prevent="sendAiQuery(customChatInput)" class="flex gap-3 pt-2">
                <input type="text" x-model="customChatInput" placeholder="Tanyakan rekomendasi kado, kopi, atau fashion UMKM..." class="flex-1 px-4 py-3 rounded-xl glass-card !bg-primary-950/30 border-primary-400/15 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-primary-400">
                <button type="submit" class="px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-bold text-xs transition">Kirim</button>
            </form>
        </div>
    </div>

    <!-- DEDICATED PAGE 7: FAVORIT SAYA -->
    <div x-show="activeTab === 'favorites'" class="space-y-6">
        <div class="flex items-center justify-between border-b border-primary-400/10 pb-4">
            <div>
                <h3 class="text-xl font-bold text-white font-heading">Produk Favorit Saya (Wishlist)</h3>
                <p class="text-xs text-slate-500">Daftar produk UMKM yang Anda simpan untuk dibeli nanti.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-red-500/20 text-red-300 text-xs font-bold border border-red-500/30" x-text="favorites.length + ' Produk Disimpan'"></span>
        </div>

        <template x-if="favoriteProducts.length === 0">
            <div class="text-center py-16 glass-card rounded-3xl border-primary-400/10 space-y-3">
                <p class="text-xs text-slate-500">Belum ada produk favorit. Tekan ikon simpan pada katalog untuk menyimpan produk.</p>
                <button @click="activeTab = 'katalog'" class="px-4 py-2 rounded-xl bg-primary-600 text-white text-xs font-bold">Jelajahi Katalog</button>
            </div>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="product in favoriteProducts" :key="'dfav-'+product.id">
                <div class="rounded-2xl glass-card border-primary-400/10 overflow-hidden flex flex-col justify-between">
                    <div class="relative h-48 bg-primary-950">
                        <img :src="product.image" class="w-full h-full object-cover">
                        <button @click="toggleFavorite(product)" class="absolute top-2.5 right-2.5 p-1.5 rounded-full glass-card !bg-primary-950/30 text-red-500 border border-primary-400/20">
                            <svg class="w-4 h-4 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    </div>

                    <div class="p-4 space-y-2">
                        <span class="text-[10px] font-bold text-primary-400 uppercase" x-text="product.umkm"></span>
                        <h4 class="text-sm font-bold text-white" x-text="product.name"></h4>
                        <div class="text-base font-extrabold text-primary-300" x-text="formatRupiah(product.price)"></div>
                    </div>

                    <div class="p-4 pt-0">
                        <button @click="addToCart(product)" class="w-full py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-bold text-xs transition">
                            + Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- DEDICATED PAGE 8: RIWAYAT PESANAN & ULASAN PRODUK -->
    <div x-show="activeTab === 'orders'" class="space-y-6">
        <div class="flex items-center justify-between border-b border-primary-400/10 pb-4">
            <div>
                <h3 class="text-xl font-bold text-white font-heading">Riwayat Pesanan & Beri Ulasan</h3>
                <p class="text-xs text-slate-500">Daftar transaksi dan kesempatan memberikan ulasan untuk produk yang telah selesai dibeli.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30" x-text="ordersHistory.length + ' Pesanan'"></span>
        </div>

        <div class="space-y-4">
            <template x-for="order in ordersHistory" :key="'dorder-'+order.id">
                <div class="p-6 rounded-2xl glass-card border-primary-400/15 space-y-4 shadow-lg">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-primary-400/10 pb-3">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-primary-300" x-text="order.id"></span>
                            <span class="text-[11px] text-slate-500" x-text="order.date"></span>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                              :class="order.status === 'Selesai' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30' : 'bg-primary-500/20 text-primary-300 border border-primary-400/20'"
                              x-text="order.status"></span>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-white" x-text="order.items"></h4>
                            <p class="text-xs text-emerald-400 font-semibold" x-text="'Dampak: ' + order.impact"></p>
                        </div>
                        
                        <div class="flex flex-col md:items-end gap-2">
                            <div class="text-right">
                                <span class="text-[10px] text-slate-500">Total Pembayaran:</span>
                                <div class="text-base font-extrabold text-white" x-text="formatRupiah(order.total)"></div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <button @click="openTracking(order)" class="px-3.5 py-2 rounded-xl bg-primary-900/30 hover:bg-primary-800 border border-primary-400/20 text-primary-200 font-bold text-xs transition flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Lacak Pengiriman</span>
                                </button>

                                <!-- Ulasan Button Trigger -->
                                <template x-if="order.status === 'Selesai'">
                                    <div>
                                        <template x-if="!order.reviewed">
                                            <button @click="openWriteReview(order)" class="px-4 py-2 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-bold text-xs transition shadow-md flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                <span>Tulis Ulasan Produk</span>
                                            </button>
                                        </template>
                                        <template x-if="order.reviewed">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary-950 text-primary-300 border-primary-400/15 text-[11px] font-semibold">
                                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                <span>Ulasan Terpublikasi</span>
                                            </span>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- DEDICATED PAGE 8B: FORM TULIS ULASAN PRODUK -->
    <div x-show="activeTab === 'write-review' && reviewingOrder" class="space-y-6">
        <button @click="activeTab = 'orders'" class="inline-flex items-center gap-2 text-xs text-primary-300 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Riwayat Pesanan</span>
        </button>

        <template x-if="reviewingOrder">
            <div class="max-w-xl p-6 sm:p-8 rounded-3xl glass-card border-primary-400/15 shadow-2xl space-y-6">
                <div class="space-y-1 border-b border-primary-400/10 pb-4">
                    <span class="text-xs font-bold text-primary-400 uppercase tracking-widest" x-text="'Pesanan: ' + reviewingOrder.id"></span>
                    <h3 class="text-xl font-bold text-white font-heading">Berikan Ulasan Produk & Pengalaman Belanja</h3>
                    <p class="text-xs text-slate-500" x-text="reviewingOrder.items"></p>
                </div>

                <form @submit.prevent="submitProductReview()" class="space-y-5">
                    <!-- Rating Star Selection -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-primary-200">Pilih Rating Bintang:</label>
                        <div class="flex items-center gap-2">
                            <template x-for="star in [1, 2, 3, 4, 5]" :key="'wstar-'+star">
                                <button type="button" @click="newReviewForm.rating = star" class="transition hover:scale-125 focus:outline-none" :class="star <= newReviewForm.rating ? 'text-amber-400' : 'text-purple-950 hover:text-amber-300'">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </button>
                            </template>
                            <span class="text-xs font-bold text-primary-300 ml-2" x-text="newReviewForm.rating + ' dari 5 Bintang'"></span>
                        </div>
                    </div>

                    <!-- Review Text Field -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-primary-200">Tulis Ulasan Anda:</label>
                        <textarea x-model="newReviewForm.comment" rows="4" placeholder="Ceritakan kualitas produk, rasa, aroma, kain, atau dampak sosial yang Anda rasakan..." class="w-full p-4 rounded-2xl glass-card !bg-primary-950/30 border-primary-400/15 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-primary-400 leading-relaxed"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs transition shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>Publikasikan Ulasan Saya</span>
                    </button>
                </form>
            </div>
        </template>
    </div>

    <!-- DEDICATED PAGE 9: PENGATURAN PROFIL -->
    <div x-show="activeTab === 'profile'" class="space-y-6">
        <div class="flex items-center justify-between border-b border-primary-400/10 pb-4">
            <div>
                <h3 class="text-xl font-bold text-white font-heading">Pengaturan Profil & Alamat Pengiriman</h3>
                <p class="text-xs text-slate-500">Kelola informasi data diri Anda di ekosistem Grownesia.</p>
            </div>
        </div>

        <div class="max-w-xl p-6 rounded-3xl glass-card border-primary-400/15 space-y-4 shadow-xl">
            <form @submit.prevent="saveProfile()" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-primary-200 mb-1">Nama Lengkap</label>
                    <input type="text" x-model="userProfile.name" class="w-full p-3 rounded-xl glass-card !bg-primary-950/30 border-primary-400/15 text-white">
                </div>

                <div>
                    <label class="block font-semibold text-primary-200 mb-1">Alamat Email</label>
                    <input type="email" x-model="userProfile.email" class="w-full p-3 rounded-xl glass-card !bg-primary-950/30 border-primary-400/15 text-white">
                </div>

                <div>
                    <label class="block font-semibold text-primary-200 mb-1">Nomor WhatsApp</label>
                    <input type="text" x-model="userProfile.phone" class="w-full p-3 rounded-xl glass-card !bg-primary-950/30 border-primary-400/15 text-white">
                </div>

                <div>
                    <label class="block font-semibold text-primary-200 mb-1">Alamat Pengiriman Utama</label>
                    <textarea x-model="userProfile.address" class="w-full p-3 rounded-xl glass-card !bg-primary-950/30 border-primary-400/15 text-white" rows="3"></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-bold text-xs transition shadow-lg">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>
    </div>

    <!-- DEDICATED PAGE 10: AI GIFT RECOMMENDATION -->
    <div x-show="activeTab === 'ai-gift'" class="p-6 sm:p-8 rounded-3xl glass-card border-primary-400/15 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-primary-400/10 pb-4">
            <div class="space-y-1">
                <span class="text-xs font-extrabold uppercase tracking-widest text-primary-400">Racikan AI Kustom</span>
                <h3 class="text-xl font-bold text-white font-heading">AI Gift & Custom Package Creator</h3>
                <p class="text-xs text-slate-500">Atur anggaran dan ketik penerima/tujuan kado apa saja secara bebas!</p>
            </div>
            
            <div class="flex bg-primary-950 p-1 rounded-xl border-primary-400/15 text-xs font-semibold">
                <button @click="giftRecipientMode = 'preset'; generateGiftBundle()" :class="giftRecipientMode === 'preset' ? 'bg-primary-600 text-white' : 'text-primary-300 hover:text-white'" class="px-3 py-1.5 rounded-lg transition">
                    Pilihan Preset
                </button>
                <button @click="giftRecipientMode = 'custom'; generateGiftBundle()" :class="giftRecipientMode === 'custom' ? 'bg-primary-600 text-white' : 'text-primary-300 hover:text-white'" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <span>Input Custom</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
            <div class="md:col-span-5 space-y-5 glass-card !bg-primary-950/20 p-5 rounded-2xl border-primary-400/10">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-primary-200">
                        Anggaran Belanja: <span class="text-primary-300 text-base font-extrabold" x-text="formatRupiah(giftBudget)"></span>
                    </label>
                    <input type="range" min="50000" max="600000" step="25000" x-model="giftBudget" @input="generateGiftBundle()" class="w-full accent-primary-500 cursor-pointer">
                </div>

                <div x-show="giftRecipientMode === 'preset'" class="space-y-2">
                    <label class="block text-xs font-bold text-primary-200">Target Penerima (Preset):</label>
                    <select x-model="giftRecipient" @change="generateGiftBundle()" class="w-full p-3 rounded-xl bg-primary-900/40 border-primary-400/15 text-xs text-white">
                        <option value="Hadiah untuk Ibu / Orang Tua (Usia 50th)">Hadiah untuk Ibu / Orang Tua</option>
                        <option value="Hadiah Ulang Tahun untuk Sahabat">Hadiah Ulang Tahun untuk Sahabat</option>
                        <option value="Hampers Formal untuk Rekan Kerja & Dosen">Hampers Rekan Kerja / Dosen</option>
                        <option value="Souvenir Penggemar Kopi Nusantara">Souvenir Penggemar Kopi</option>
                    </select>
                </div>

                <div x-show="giftRecipientMode === 'custom'" class="space-y-3">
                    <label class="block text-xs font-bold text-primary-200">Ketik Kebutuhan Hadiah / Penerima Kustom:</label>
                    <input type="text"
                           x-model="customGiftRecipientInput"
                           @input.debounce.300ms="generateGiftBundle()"
                           placeholder="Contoh: Hadiah dosen pembimbing 45th..."
                           class="w-full p-3 rounded-xl bg-primary-900/40 border border-primary-400/25 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-primary-400">
                    
                    <div class="space-y-1.5 pt-1">
                        <span class="text-[10px] text-primary-400 font-semibold uppercase tracking-wider">Coba Kueri Cepat:</span>
                        <div class="flex flex-wrap gap-1.5 text-[11px]">
                            <button @click="giftRecipientMode = 'custom'; customGiftRecipientInput = 'Hadiah ulang tahun untuk dosen pembimbing 45th'; generateGiftBundle()" class="px-2.5 py-1 rounded-lg bg-primary-900/30 hover:bg-primary-800 text-primary-200 border-primary-400/15 transition flex items-center gap-1">
                                <svg class="w-3 h-3 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                                <span>Dosen Pembimbing</span>
                            </button>
                            <button @click="giftRecipientMode = 'custom'; customGiftRecipientInput = 'Hampers wisuda batik & kopi untuk sahabat'; generateGiftBundle()" class="px-2.5 py-1 rounded-lg bg-primary-900/30 hover:bg-primary-800 text-primary-200 border-primary-400/15 transition flex items-center gap-1">
                                <svg class="w-3 h-3 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <span>Hampers Wisuda</span>
                            </button>
                            <button @click="giftRecipientMode = 'custom'; customGiftRecipientInput = 'Kado syukuran rumah baru produk eco-friendly'; generateGiftBundle()" class="px-2.5 py-1 rounded-lg bg-primary-900/30 hover:bg-primary-800 text-primary-200 border-primary-400/15 transition flex items-center gap-1">
                                <svg class="w-3 h-3 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <span>Syukuran Rumah</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-7">
                <div x-show="generatedBundle" class="p-6 rounded-2xl glass-card !bg-primary-950/40 border border-primary-400/25 space-y-4 shadow-xl">
                    <div class="flex items-center justify-between border-b border-primary-400/10 pb-3">
                        <div class="space-y-0.5">
                            <span class="text-[10px] font-bold text-primary-400 uppercase tracking-widest">Racikan AI Terkomposisi</span>
                            <h4 class="text-sm font-bold text-white" x-text="'Kriteria: ' + generatedBundle.recipientLabel"></h4>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-slate-500">Total Estimasi:</span>
                            <div class="text-lg font-extrabold text-primary-300" x-text="formatRupiah(generatedBundle.totalPrice)"></div>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-primary-900/30 border-primary-400/15 text-xs text-primary-200 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-primary-300">
                            <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Analisis Rekomendasi AI:</span>
                        </div>
                        <p class="text-[11px] text-slate-300" x-text="generatedBundle.aiReasoning"></p>
                    </div>

                    <div class="space-y-2">
                        <span class="text-xs font-semibold text-primary-300">Produk yang Termasuk dalam Paket ini:</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="item in generatedBundle.items" :key="'gbund2-'+item.id">
                                <div class="p-3 rounded-xl bg-primary-900/20 border-primary-400/10 flex items-center gap-3">
                                    <img :src="item.image" class="w-12 h-12 rounded-lg object-cover border border-primary-400/20 shrink-0">
                                    <div class="min-w-0">
                                        <h5 class="text-xs font-bold text-white truncate" x-text="item.name"></h5>
                                        <p class="text-[10px] text-slate-500" x-text="item.umkm"></p>
                                        <span class="text-xs font-bold text-primary-200" x-text="formatRupiah(item.price)"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Social Impact: <strong x-text="generatedBundle.impact"></strong></span>
                    </div>

                    <button @click="addBundleToCart()" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-bold text-xs transition shadow-lg flex items-center justify-center gap-2">
                        <span>+ Tambah Semua Paket Ini ke Keranjang & Checkout</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- DEDICATED PAGE 11: AI PRODUCT COMPARISON -->
    <div x-show="activeTab === 'ai-compare'" class="p-6 sm:p-8 rounded-3xl glass-card border-primary-400/15 shadow-2xl space-y-6">
        <div class="space-y-1">
            <span class="text-xs font-extrabold uppercase tracking-widest text-primary-400">Komparator Produk AI</span>
            <h3 class="text-xl font-bold text-white font-heading">AI Product Comparison</h3>
            <p class="text-xs text-slate-500">Bandingkan dua produk UMKM secara berdampingan untuk keputusan belanja tepat.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-primary-200 mb-1">Produk A:</label>
                <select x-model="compareProduct1" @change="generateAiComparison()" class="w-full p-2.5 rounded-xl glass-card !bg-primary-950/30 border-primary-400/15 text-xs text-white">
                    <template x-for="p in products" :key="'cmp1-'+p.id">
                        <option :value="p" x-text="p.name + ' (' + formatRupiah(p.price) + ')'"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-primary-200 mb-1">Produk B:</label>
                <select x-model="compareProduct2" @change="generateAiComparison()" class="w-full p-2.5 rounded-xl glass-card !bg-primary-950/30 border-primary-400/15 text-xs text-white">
                    <template x-for="p in products" :key="'cmp2-'+p.id">
                        <option :value="p" x-text="p.name + ' (' + formatRupiah(p.price) + ')'"></option>
                    </template>
                </select>
            </div>
        </div>

        <template x-if="compareProduct1 && compareProduct2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div class="p-4 rounded-2xl glass-card !bg-primary-950/30 border-primary-400/15 space-y-2 text-xs">
                    <span class="font-bold text-primary-300">Produk A: <span x-text="compareProduct1.name"></span></span>
                    <div class="text-emerald-400 font-semibold" x-text="compareProduct1.impact_text"></div>
                    <div class="text-white font-bold" x-text="formatRupiah(compareProduct1.price)"></div>
                </div>

                <div class="p-4 rounded-2xl glass-card !bg-primary-950/30 border-primary-400/15 space-y-2 text-xs">
                    <span class="font-bold text-primary-300">Produk B: <span x-text="compareProduct2.name"></span></span>
                    <div class="text-emerald-400 font-semibold" x-text="compareProduct2.impact_text"></div>
                    <div class="text-white font-bold" x-text="formatRupiah(compareProduct2.price)"></div>
                </div>
            </div>
        </template>

        <template x-if="aiCompareAnalysis">
            <div class="p-4 rounded-2xl bg-primary-900/30 border border-primary-400/25 text-xs space-y-1">
                <div class="font-bold text-primary-200 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Analisis AI:</span>
                </div>
                <p class="text-slate-300" x-text="aiCompareAnalysis.verdict"></p>
            </div>
        </template>
    </div>

    <!-- DEDICATED PAGE 12: ORDER TRACKING (CUSTOMER DASHBOARD) -->
    <div x-show="activeTab === 'tracking'" class="space-y-6">
        
        <!-- HEADER SECTION -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-primary-400/10 pb-4">
            <div>
                <span class="text-[10px] font-bold text-primary-400 uppercase tracking-widest">Customer Portal — Live Logistics</span>
                <h3 class="text-xl font-bold text-white font-heading flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Order Tracking (Pelacakan Pengiriman)</span>
                </h3>
                <p class="text-xs text-slate-500">Monitor progress pengiriman paket pesanan Anda secara real-time dengan dukungan prediksi AI.</p>
            </div>

            <!-- Order Picker Dropdown -->
            <template x-if="ordersHistory.length > 0">
                <div class="flex items-center gap-2 glass-card !bg-primary-950/30 p-1.5 rounded-2xl border-primary-400/15">
                    <span class="text-[11px] font-bold text-primary-300 px-2">Pilih Pesanan:</span>
                    <select x-model="trackingOrder" class="bg-primary-900 text-white text-xs font-bold rounded-xl px-3 py-1.5 border border-primary-400/20 focus:outline-none">
                        <template x-for="ord in ordersHistory" :key="'tr-sel-'+ord.id">
                            <option :value="ord" x-text="ord.id + ' — ' + ord.productName"></option>
                        </template>
                    </select>
                </div>
            </template>
        </div>

        <template x-if="trackingOrder">
            <div class="space-y-6">

                <!-- SUCCESS BANNER (IF DELIVERED) -->
                <template x-if="trackingOrder.shippingStatus === 'Delivered'">
                    <div class="p-5 rounded-2xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-lg shadow-emerald-950/50">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Paket Telah Tiba dengan Selamat! 🎉</h4>
                                <p class="text-xs text-emerald-300/80">Paket telah diterima pada <span x-text="trackingOrder.lastUpdated"></span>. Terima kasih telah mendukung ekonomi lokal!</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="openWriteReview(trackingOrder)" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition shadow-md">
                                Tulis Ulasan
                            </button>
                            <button @click="addToCart(products[0])" class="px-4 py-2 rounded-xl bg-primary-900 hover:bg-primary-800 text-primary-200 font-bold text-xs transition border border-primary-400/20">
                                Beli Lagi
                            </button>
                        </div>
                    </div>
                </template>

                <!-- LARGE PREMIUM TRACKING CARD -->
                <div class="p-6 sm:p-8 rounded-3xl glass-card border-primary-400/15 shadow-2xl relative overflow-hidden space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-primary-400/10 pb-5">
                        <div class="flex items-center gap-4">
                            <!-- Courier Badge / Logo -->
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-primary-900 to-primary-700 border border-primary-400/25 flex items-center justify-center text-white font-black text-sm tracking-wider shadow-lg">
                                <span x-text="trackingOrder.courierLogo || 'JNE'"></span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-lg font-bold text-white font-heading" x-text="trackingOrder.courier"></h4>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30" x-text="trackingOrder.shippingStatus"></span>
                                </div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-xs text-primary-300 font-mono" x-text="'Resi: ' + trackingOrder.trackingNumber"></span>
                                    <span class="text-[10px] text-slate-600">• Updated <span x-text="trackingOrder.lastUpdated"></span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons: Track Shipment & Copy Tracking Number -->
                        <div class="flex items-center gap-3">
                            <button @click="refreshTracking()" class="px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold transition shadow-lg flex items-center gap-2">
                                <svg class="w-4 h-4" :class="isRefreshingTracking ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Lacak Ulang</span>
                            </button>
                            <button @click="copyTrackingNumber(trackingOrder.trackingNumber)" class="px-4 py-2.5 rounded-xl bg-primary-950 hover:bg-primary-900 border border-purple-500/40 text-primary-200 text-xs font-bold transition flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Salin Resi</span>
                            </button>
                        </div>
                    </div>

                    <!-- Current Status Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <div class="p-4 rounded-2xl glass-card !bg-primary-950/30 border-primary-400/10 space-y-1">
                            <span class="text-[10px] text-primary-400 uppercase font-bold">Status & Lokasi Saat Ini</span>
                            <p class="font-bold text-white text-sm" x-text="trackingOrder.currentLocation"></p>
                        </div>
                        <div class="p-4 rounded-2xl glass-card !bg-primary-950/30 border-primary-400/10 space-y-1">
                            <span class="text-[10px] text-primary-400 uppercase font-bold">Estimasi Tiba</span>
                            <p class="font-bold text-emerald-400 text-sm" x-text="trackingOrder.estimatedArrival"></p>
                        </div>
                        <div class="p-4 rounded-2xl glass-card !bg-primary-950/30 border-primary-400/10 space-y-1">
                            <span class="text-[10px] text-primary-400 uppercase font-bold">Alamat Tujuan</span>
                            <p class="text-slate-300 truncate" x-text="trackingOrder.shippingAddress"></p>
                        </div>
                    </div>
                </div>

                <!-- AI DELIVERY INSIGHT CARD (DESIGN SYSTEM) -->
                <div class="p-6 rounded-3xl bg-gradient-to-r from-primary-900/80 via-primary-800/50 to-primary-950/90 border border-primary-400/25 shadow-2xl relative overflow-hidden space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-primary-500/20 border border-primary-400/20 flex items-center justify-center text-primary-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h4 class="text-sm font-bold text-white font-heading">AI Delivery Insight</h4>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30" x-text="(trackingOrder.aiConfidence || '96%') + ' Confidence'"></span>
                    </div>

                    <p class="text-xs text-slate-200 leading-relaxed" x-text="trackingOrder.aiInsight || 'Pengiriman berjalan lancar sesuai estimasi.'"></p>

                    <div class="pt-2 flex items-center gap-4 text-[11px] text-slate-400 border-t border-primary-400/10">
                        <span>💡 Rekomendasi: Pastikan seseorang berada di alamat tujuan untuk penerimaan paket.</span>
                    </div>
                </div>

                <!-- VERTICAL DELIVERY TIMELINE COMPONENT -->
                <div class="p-6 sm:p-8 rounded-3xl glass-card border-primary-400/15 shadow-2xl space-y-6">
                    <h4 class="text-base font-bold text-white font-heading flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>Timeline Logistik Pengiriman (Vertical Timeline)</span>
                    </h4>

                    <!-- Vertical Timeline Steps -->
                    <div class="relative pl-8 space-y-6 border-l-2 border-primary-400/15 ml-4">
                        <template x-for="(step, idx) in trackingOrder.timeline" :key="'tl-step-'+idx">
                            <div class="relative group">
                                <!-- Status Node Icon -->
                                <div class="absolute -left-[45px] top-0 w-8 h-8 rounded-full border-2 flex items-center justify-center transition"
                                     :class="step.done ? 'bg-gradient-to-r from-primary-600 to-primary-500 border-primary-300 text-white shadow-lg shadow-primary-900/50 scale-105' : 'bg-primary-950 border-primary-400/15 text-purple-500'">
                                    <template x-if="step.done">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                    <template x-if="!step.done">
                                        <span class="w-2.5 h-2.5 rounded-full bg-primary-500/50"></span>
                                    </template>
                                </div>

                                <div class="space-y-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h5 class="text-xs font-bold" :class="step.done ? 'text-white' : 'text-slate-600'" x-text="step.desc"></h5>
                                        <span class="text-[10px] text-slate-500" x-text="step.time"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-400" x-text="step.location"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- ORDER DETAIL SUMMARY -->
                <div class="p-6 rounded-3xl glass-card border-primary-400/15 shadow-2xl space-y-4">
                    <h4 class="text-base font-bold text-white font-heading">Rincian Produk & Pembayaran</h4>
                    
                    <div class="flex items-center gap-4 p-3 rounded-2xl glass-card !bg-primary-950/30 border-primary-400/10">
                        <img :src="trackingOrder.productImage" class="w-16 h-16 rounded-xl object-cover border border-primary-400/20">
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-primary-400 uppercase" x-text="trackingOrder.businessName"></span>
                            <h5 class="text-xs font-bold text-white" x-text="trackingOrder.productName"></h5>
                            <span class="text-[11px] text-primary-300 font-semibold" x-text="trackingOrder.items"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs pt-2 border-t border-primary-400/10">
                        <div>
                            <span class="text-[10px] text-primary-400 block">Metode Bayar:</span>
                            <span class="font-bold text-white" x-text="trackingOrder.paymentMethod || 'QRIS'"></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-primary-400 block">Ongkos Kirim:</span>
                            <span class="font-bold text-emerald-400">Rp 0 (Subsidi UMKM)</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-primary-400 block">Total Transaksi:</span>
                            <span class="font-bold text-white" x-text="formatRupiah(trackingOrder.total)"></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-primary-400 block">No. Transaksi:</span>
                            <span class="font-mono text-primary-300 font-bold" x-text="trackingOrder.id"></span>
                        </div>
                    </div>
                </div>

            </div>
        </template>

        <!-- EMPTY STATE (IF NO SHIPMENT IS SELECTED OR FOUND) -->
        <template x-if="!trackingOrder || ordersHistory.length === 0">
            <div class="text-center py-20 p-8 rounded-3xl glass-card border-primary-400/10 space-y-4">
                <div class="w-20 h-20 rounded-3xl bg-primary-950 border-primary-400/15 flex items-center justify-center mx-auto text-primary-400 shadow-xl">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="space-y-1">
                    <h4 class="text-lg font-bold text-white font-heading">No shipment available.</h4>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">Anda belum memiliki transaksi pengiriman yang aktif. Silakan pilih produk dari katalog UMKM dan selesaikan pesanan Anda.</p>
                </div>
                <button @click="activeTab = 'katalog'" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-bold text-xs transition shadow-lg">
                    Continue Shopping (Lanjutkan Belanja)
                </button>
            </div>
        </template>

    </div>

    <!-- COPY TOAST NOTIFICATION -->
    <div x-show="copyToast" x-cloak class="fixed bottom-6 right-6 z-50 px-4 py-3 rounded-2xl bg-primary-900 border border-primary-400 text-white text-xs font-bold shadow-2xl flex items-center gap-2 animate-bounce">
        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        <span>Nomor Resi Berhasil Disalin ke Clipboard!</span>
    </div>

</div>

@endsection
