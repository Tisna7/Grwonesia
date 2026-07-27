@extends('layouts.sidebar')

@section('content')

<div class="space-y-8">
    
    <!-- TOP STATS BANNER (SHOWN ON HOME/KATALOG TAB) -->
    <div x-show="activeTab === 'katalog'" class="p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl glow-purple relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center relative z-10">
            <div class="lg:col-span-8 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/20 border border-purple-400/30 text-purple-300 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    AI Personal Shopper Active
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-white font-heading">
                    Portal Belanja UMKM Berdampak (User Dashboard)
                </h1>

                <p class="text-xs sm:text-sm text-purple-200/80 leading-relaxed max-w-2xl">
                    Jelajahi produk UMKM Indonesia pilihan, baca ulasan dari pembeli terverifikasi, berikan ulasan pesanan Anda, dan bantu gerakkan ekonomi lokal.
                </p>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button @click="activeTab = 'ai-assistant'; sendAiQuery('Saya ingin hadiah untuk ibu umur 50 tahun.')" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-xs transition shadow-md flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Buka AI Shopping Assistant</span>
                    </button>
                    <button @click="activeTab = 'orders'" class="px-4 py-2.5 rounded-xl bg-purple-950/80 hover:bg-purple-900 border border-purple-500/30 text-purple-200 font-bold text-xs transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>Beri Ulasan Pesanan</span>
                    </button>
                </div>
            </div>

            <!-- Quick Impact Summary Widget -->
            <div class="lg:col-span-4 p-4 rounded-2xl bg-purple-950/90 border border-purple-500/30 space-y-2 text-center">
                <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-widest">Akumulasi Dampak Sosial Anda</span>
                <div class="text-3xl font-extrabold text-white font-heading" x-text="userImpact.totalJobs + ' Pekerja'"></div>
                <p class="text-[11px] text-purple-300/70" x-text="userImpact.villagesHelped + ' Desa & ' + userImpact.craftswomenHelped + ' Pengrajin Wanita Terbantu'"></p>
                <button @click="activeTab = 'impact'" class="w-full py-1.5 rounded-lg bg-emerald-950/80 hover:bg-emerald-900 border border-emerald-500/30 text-emerald-300 text-xs font-semibold flex items-center justify-center gap-1">
                    <span>Buka Halaman Sertifikat Dampak</span>
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
                <p class="text-xs text-purple-300/70">PILIH DAN BELI PRODUK BERKUALITAS SECARA LANGSUNG</p>
            </div>

            <!-- Search Bar inside Workspace -->
            <div class="relative w-full md:w-80">
                <input type="text"
                       x-model="searchQuery"
                       placeholder="Cari batik, kopi, kerajinan..."
                       class="w-full pl-9 pr-4 py-2 rounded-xl bg-purple-950/80 border border-purple-500/30 text-xs text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">
                <svg class="w-4 h-4 text-purple-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <!-- Category & Impact Filter Pills -->
        <div class="flex flex-wrap items-center justify-between gap-3 bg-purple-950/40 p-3 rounded-2xl border border-purple-500/20 text-xs">
            <div class="flex flex-wrap items-center gap-2">
                <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-900/40 text-purple-300 hover:bg-purple-800 border-purple-500/20'" class="px-3 py-1.5 rounded-xl border font-semibold transition">
                    Semua
                </button>
                <button @click="activeCategory = 'kopi'" :class="activeCategory === 'kopi' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-900/40 text-purple-300 hover:bg-purple-800 border-purple-500/20'" class="px-3 py-1.5 rounded-xl border font-semibold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                    <span>Kopi & Minuman</span>
                </button>
                <button @click="activeCategory = 'batik'" :class="activeCategory === 'batik' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-900/40 text-purple-300 hover:bg-purple-800 border-purple-500/20'" class="px-3 py-1.5 rounded-xl border font-semibold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-fuchsia-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-23"/></svg>
                    <span>Batik & Fashion</span>
                </button>
                <button @click="activeCategory = 'kerajinan'" :class="activeCategory === 'kerajinan' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-900/40 text-purple-300 hover:bg-purple-800 border-purple-500/20'" class="px-3 py-1.5 rounded-xl border font-semibold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>Kerajinan & Craft</span>
                </button>
                <button @click="activeCategory = 'makanan'" :class="activeCategory === 'makanan' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-900/40 text-purple-300 hover:bg-purple-800 border-purple-500/20'" class="px-3 py-1.5 rounded-xl border font-semibold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Makanan Ringan</span>
                </button>
            </div>

            <div class="flex items-center gap-1.5">
                <span class="text-purple-400 font-medium">Impact Filter:</span>
                <select x-model="selectedImpactFilter" class="p-1 rounded-lg bg-purple-900/80 border border-purple-500/30 text-white text-[11px]">
                    <option value="all">Semua Impact</option>
                    <option value="Pemberdayaan Wanita">Pemberdayaan Wanita</option>
                    <option value="Eco-Friendly">Eco-Friendly</option>
                    <option value="Desa Berkembang">Desa Berkembang</option>
                </select>
            </div>
        </div>

        <!-- Product Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="product in filteredProducts" :key="product.id">
                <div class="group rounded-2xl bg-purple-card border border-purple-500/20 overflow-hidden bg-purple-card-hover transition duration-300 flex flex-col justify-between relative">
                    <div>
                        <div class="relative h-48 overflow-hidden bg-purple-950 cursor-pointer" @click="openProductDetail(product)">
                            <img :src="product.image" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-md bg-purple-950/80 border border-purple-400/30 text-[10px] text-purple-200 flex items-center gap-1">
                                <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span x-text="product.region"></span>
                            </div>

                            <!-- Heart Favorite Button -->
                            <button @click.stop="toggleFavorite(product)" class="absolute top-2.5 right-2.5 p-1.5 rounded-full bg-purple-950/80 text-white border border-purple-400/30 hover:scale-110 transition z-10">
                                <svg class="w-4 h-4" :class="isFavorite(product.id) ? 'text-red-500 fill-current' : 'text-purple-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>

                            <div class="absolute bottom-2.5 left-2.5 right-2.5 px-2.5 py-1 rounded-lg bg-emerald-950/80 border border-emerald-500/40 text-emerald-300 text-[11px] font-semibold flex items-center justify-between">
                                <span class="truncate" x-text="product.impact_text"></span>
                                <span class="font-bold bg-emerald-500/30 px-1 rounded text-white" x-text="product.impact_score"></span>
                            </div>
                        </div>

                        <div class="p-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-semibold text-purple-400 uppercase tracking-wider" x-text="product.umkm"></span>
                                <button @click="openProductDetail(product)" class="text-[10px] text-purple-300 hover:text-white underline flex items-center gap-1">
                                    <span>Detail & Review</span>
                                    <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                            <h4 @click="openProductDetail(product)" class="text-sm font-bold text-white group-hover:text-purple-300 transition line-clamp-1 cursor-pointer" x-text="product.name"></h4>
                            <p class="text-xs text-purple-300/70 line-clamp-2" x-text="product.description"></p>
                        </div>
                    </div>

                    <div class="p-4 pt-0 space-y-2.5">
                        <div class="flex items-baseline justify-between border-t border-purple-500/10 pt-2.5">
                            <div>
                                <span class="text-[11px] text-purple-400/60 line-through mr-1" x-text="formatRupiah(product.original_price)"></span>
                                <span class="text-base font-extrabold text-white" x-text="formatRupiah(product.price)"></span>
                            </div>
                            <button @click="compareProduct2 = product; activeTab = 'ai-compare'" class="text-[11px] text-purple-300 hover:text-white underline flex items-center gap-1">
                                <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Bandingkan</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <button @click="addToCart(product)" class="w-full py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition">
                                + Keranjang
                            </button>
                            <button @click="addToCart(product)" class="w-full py-2 rounded-xl bg-purple-950 hover:bg-purple-900 border border-purple-500/30 text-purple-200 font-bold text-xs transition">
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
        <button @click="activeTab = 'katalog'" class="inline-flex items-center gap-2 text-xs text-purple-300 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Katalog Produk</span>
        </button>

        <template x-if="selectedProductDetail">
            <div class="space-y-6">
                <!-- Main Product Detail Card -->
                <div class="p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-6">
                    <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-800/40 text-purple-300 border border-purple-400/30" x-text="selectedProductDetail.category"></span>
                            <span class="text-xs text-emerald-400 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span x-text="selectedProductDetail.rating"></span> (<span x-text="getProductReviews(selectedProductDetail.id).length + 45"></span> ulasan pembeli)
                            </span>
                        </div>
                        <button @click="toggleFavorite(selectedProductDetail)" class="px-3 py-1.5 rounded-xl bg-purple-950 border border-purple-500/30 text-xs font-bold text-purple-200 flex items-center gap-1.5">
                            <svg class="w-4 h-4" :class="isFavorite(selectedProductDetail.id) ? 'text-red-500 fill-current' : 'text-purple-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span x-text="isFavorite(selectedProductDetail.id) ? 'Disimpan di Favorit' : '+ Simpan ke Favorit'"></span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                        <div class="md:col-span-5 h-72 rounded-2xl overflow-hidden bg-purple-950 border border-purple-400/30">
                            <img :src="selectedProductDetail.image" class="w-full h-full object-cover">
                        </div>

                        <div class="md:col-span-7 space-y-4">
                            <span class="text-xs font-bold text-purple-400 uppercase tracking-widest" x-text="'Penjual: ' + selectedProductDetail.umkm + ' (' + selectedProductDetail.region + ')'"></span>
                            <h2 class="text-2xl font-extrabold text-white font-heading" x-text="selectedProductDetail.name"></h2>
                            <p class="text-xs sm:text-sm text-purple-200/80 leading-relaxed" x-text="selectedProductDetail.description"></p>
                            
                            <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-xs space-y-1">
                                <div class="flex items-center gap-1.5 font-bold">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Social Impact Certified: <span x-text="selectedProductDetail.impact_text"></span></span>
                                </div>
                                <p class="text-[11px] text-emerald-400/80">Setiap pembelian produk ini berkontribusi langsung pada pendapatan keluarga mitra UMKM lokal.</p>
                            </div>

                            <div class="flex items-baseline gap-3 pt-2">
                                <span class="text-3xl font-extrabold text-white" x-text="formatRupiah(selectedProductDetail.price)"></span>
                                <span class="text-sm text-purple-400/60 line-through" x-text="formatRupiah(selectedProductDetail.original_price)"></span>
                            </div>

                            <div class="flex flex-wrap items-center gap-3 pt-2">
                                <button @click="addToCart(selectedProductDetail)" class="px-6 py-3.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition shadow-lg">
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
                <div class="p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 space-y-6 shadow-xl">
                    <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-white font-heading flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span>Ulasan & Rating Pembeli</span>
                                <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Verified Buyers</span>
                            </h3>
                            <p class="text-xs text-purple-300/70">Pengalaman nyata dari pembeli yang telah membantu UMKM ini.</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-extrabold text-white" x-text="selectedProductDetail.rating + ' / 5.0'"></div>
                            <span class="text-[11px] text-purple-400">Rating Keseluruhan</span>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="space-y-4">
                        <template x-for="rev in getProductReviews(selectedProductDetail.id)" :key="'rev-'+rev.id">
                            <div class="p-4 rounded-2xl bg-purple-950/70 border border-purple-500/20 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-purple-800 text-white font-bold flex items-center justify-center text-xs" x-text="rev.userName.charAt(0)"></div>
                                        <div>
                                            <span class="text-xs font-bold text-white" x-text="rev.userName"></span>
                                            <span class="text-[10px] ml-1.5 px-2 py-0.5 rounded bg-emerald-950 text-emerald-300 border border-emerald-500/30">Pembeli Terverifikasi</span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-purple-400/70" x-text="rev.date"></span>
                                </div>

                                <div class="flex items-center gap-1 text-amber-400 text-xs">
                                    <template x-for="star in rev.rating" :key="'st-'+star">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    </template>
                                </div>

                                <p class="text-xs text-purple-200/90 leading-relaxed" x-text="rev.comment"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- DEDICATED PAGE 3: KERANJANG & CHECKOUT -->
    <div x-show="activeTab === 'cart'" class="space-y-6">
        <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
            <div>
                <h3 class="text-xl font-bold text-white font-heading">Halaman Keranjang & Checkout Pembayaran</h3>
                <p class="text-xs text-purple-300/70">Periksa item belanja Anda dan lakukan checkout secara aman.</p>
            </div>
            <button @click="activeTab = 'katalog'" class="text-xs text-purple-300 hover:text-white underline">← Kembali Belanja</button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left: Cart Items List -->
            <div class="lg:col-span-7 space-y-4">
                <h4 class="text-sm font-bold text-purple-300 uppercase tracking-wider">Item dalam Keranjang</h4>

                <template x-if="cart.length === 0">
                    <div class="p-8 text-center bg-purple-card rounded-2xl border border-purple-500/20 space-y-3">
                        <p class="text-xs text-purple-300/70">Keranjang Anda masih kosong.</p>
                        <button @click="activeTab = 'katalog'" class="px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold">Mulai Belanja</button>
                    </div>
                </template>

                <template x-for="(item, idx) in cart" :key="'citem-'+idx">
                    <template x-if="item.product">
                        <div class="p-4 rounded-2xl bg-purple-card border border-purple-500/20 flex gap-4 items-center">
                            <img :src="item.product.image" class="w-16 h-16 rounded-xl object-cover border border-purple-400/30 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-white truncate" x-text="item.product.name"></h4>
                                <p class="text-xs text-purple-300/70" x-text="item.product.umkm"></p>
                                <p class="text-xs font-bold text-purple-300 mt-1" x-text="formatRupiah(item.product.price)"></p>
                            </div>
                            <div class="flex items-center gap-2 bg-purple-950 rounded-xl p-1.5 border border-purple-500/30">
                                <button @click="updateQty(idx, -1)" class="w-6 h-6 rounded text-purple-300 hover:bg-purple-800 text-xs font-bold flex items-center justify-center">-</button>
                                <span class="text-xs font-bold px-2 text-white" x-text="item.qty"></span>
                                <button @click="updateQty(idx, 1)" class="w-6 h-6 rounded text-purple-300 hover:bg-purple-800 text-xs font-bold flex items-center justify-center">+</button>
                            </div>
                        </div>
                    </template>
                </template>
            </div>

            <!-- Right: Checkout Details & Payment -->
            <div class="lg:col-span-5 space-y-4">
                <div class="p-6 rounded-3xl bg-purple-card border border-purple-500/30 space-y-4 shadow-xl">
                    <h4 class="text-sm font-bold text-white border-b border-purple-500/20 pb-2">Informasi Alamat & Pembayaran</h4>

                    <div class="space-y-3 text-xs">
                        <div>
                            <label class="block font-semibold text-purple-200 mb-1">Alamat Pengiriman Utama</label>
                            <textarea x-model="userProfile.address" class="w-full p-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-purple-100" rows="2"></textarea>
                        </div>

                        <div>
                            <label class="block font-semibold text-purple-200 mb-1">Pilih Metode Pembayaran</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button @click="selectedPaymentMethod = 'qris'" :class="selectedPaymentMethod === 'qris' ? 'border-purple-400 bg-purple-800/60' : 'border-purple-500/30 bg-purple-950/80'" class="p-2.5 rounded-xl border text-center font-bold text-white text-[11px]">
                                    QRIS Instant
                                </button>
                                <button @click="selectedPaymentMethod = 'gopay'" :class="selectedPaymentMethod === 'gopay' ? 'border-purple-400 bg-purple-800/60' : 'border-purple-500/30 bg-purple-950/80'" class="p-2.5 rounded-xl border text-center font-bold text-white text-[11px]">
                                    GoPay / OVO
                                </button>
                                <button @click="selectedPaymentMethod = 'bank'" :class="selectedPaymentMethod === 'bank' ? 'border-purple-400 bg-purple-800/60' : 'border-purple-500/30 bg-purple-950/80'" class="p-2.5 rounded-xl border text-center font-bold text-white text-[11px]">
                                    Transfer Bank
                                </button>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-xs flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Social Impact: <strong x-text="calculateCartImpactText()"></strong></span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-purple-950/90 border border-purple-500/30 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-purple-300/70">Subtotal Produk</span>
                                <span class="text-white font-semibold" x-text="formatRupiah(cartTotalPrice)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-purple-300/70">Ongkos Kirim (Subsidi UMKM)</span>
                                <span class="text-emerald-400 font-semibold">GRATIS</span>
                            </div>
                            <div class="pt-2 border-t border-purple-500/20 flex justify-between text-base font-extrabold">
                                <span class="text-purple-200">Total Akhir:</span>
                                <span class="text-purple-300" x-text="formatRupiah(cartTotalPrice)"></span>
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
        <div class="max-w-2xl mx-auto p-8 rounded-3xl bg-purple-card border border-purple-400/40 text-center space-y-5 shadow-2xl relative overflow-hidden">
            <div class="w-20 h-20 rounded-full bg-emerald-500/20 border-2 border-emerald-400 text-emerald-400 flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>

            <h2 class="text-2xl font-extrabold text-white font-heading">Pembayaran Berhasil!</h2>
            <p class="text-xs text-purple-200/90 max-w-md mx-auto">Terima kasih atas pesanan Anda. Transaksi Anda membantu menggerakkan roda ekonomi UMKM lokal Indonesia secara langsung!</p>

            <div class="p-6 rounded-2xl bg-gradient-to-br from-purple-900/90 via-purple-950 to-indigo-950 border border-purple-400/40 text-left space-y-3 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-purple-400 uppercase tracking-widest">Sertifikat Dampak Sosial (Verified Impact)</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-semibold border border-emerald-400/30">Verified</span>
                </div>
                <div class="text-lg font-bold text-white" x-text="lastOrderImpactSummary"></div>
                <p class="text-xs text-purple-300/80 leading-relaxed">Pendapatan dari transaksi ini diteruskan ke UMKM mitra Grownesia di Solo, Palembang, dan Kebumen untuk mendukung upah layak bagi para pengrajin wanita dan petani lokal.</p>
            </div>

            <div class="flex justify-center gap-4 pt-2">
                <button @click="activeTab = 'orders'" class="px-6 py-3 rounded-xl bg-purple-950 border border-purple-500/30 hover:bg-purple-900 text-purple-200 font-bold text-xs">
                    Lihat Riwayat & Beri Ulasan
                </button>
                <button @click="activeTab = 'katalog'" class="px-6 py-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs">
                    Kembali ke Katalog
                </button>
            </div>
        </div>
    </div>

    <!-- DEDICATED PAGE 5: IMPACT SCORE DASHBOARD -->
    <div x-show="activeTab === 'impact'" class="space-y-6">
        <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
            <div>
                <h3 class="text-xl font-bold text-white font-heading">Pencapaian Impact Score Saya</h3>
                <p class="text-xs text-purple-300/70">Laporan akumulasi dampak pemberdayaan UMKM dari aktivitas belanja Anda.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30">Impact Buyer Level 2</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl bg-purple-card border border-purple-500/30 space-y-2 text-center">
                <span class="text-xs text-purple-400 font-semibold">Total Pekerja Terbantu</span>
                <div class="text-3xl font-extrabold text-white" x-text="userImpact.totalJobs + ' Pekerja'"></div>
            </div>

            <div class="p-6 rounded-2xl bg-purple-card border border-purple-500/30 space-y-2 text-center">
                <span class="text-xs text-emerald-400 font-semibold">Desa Berkembang</span>
                <div class="text-3xl font-extrabold text-white" x-text="userImpact.villagesHelped + ' Desa'"></div>
            </div>

            <div class="p-6 rounded-2xl bg-purple-card border border-purple-500/30 space-y-2 text-center">
                <span class="text-xs text-fuchsia-400 font-semibold">Pengrajin Wanita</span>
                <div class="text-3xl font-extrabold text-white" x-text="userImpact.craftswomenHelped + ' Pengrajin'"></div>
            </div>
        </div>
    </div>

    <!-- DEDICATED PAGE 6: AI SHOPPING ASSISTANT -->
    <div x-show="activeTab === 'ai-assistant'" class="space-y-6">
        <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
            <div>
                <h3 class="text-xl font-bold text-white font-heading">AI Shopping Assistant Interactive</h3>
                <p class="text-xs text-purple-300/70">Asisten kecerdasan buatan untuk rekomendasi produk dan kado personal.</p>
            </div>
        </div>

        <div class="p-6 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-4 min-h-[480px] flex flex-col justify-between">
            <div class="space-y-4 overflow-y-auto max-h-[400px] text-xs pr-2" id="fullChatContainer">
                <div class="p-4 rounded-2xl bg-purple-900/50 border border-purple-500/30 text-purple-200 space-y-2">
                    <div class="flex items-center gap-2 font-semibold text-purple-300">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-400 animate-pulse"></span> Grownesia AI Bot
                    </div>
                    <p>Halo {{ Auth::user()->name ?? 'Budi' }}! Saya AI Personal Shopper Anda. Tanyakan kebutuhan Anda atau pilih kueri favorit berikut:</p>
                    <div class="pt-2 flex flex-wrap gap-2">
                        <button @click="sendAiQuery('Saya ingin hadiah untuk ibu umur 50 tahun.')" class="px-3 py-1.5 rounded-xl bg-purple-950/80 hover:bg-purple-800 border border-purple-400/30 text-purple-200 text-xs transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Hadiah Ibu 50th</span>
                        </button>
                        <button @click="sendAiQuery('Saya punya budget Rp300.000 untuk hampers.')" class="px-3 py-1.5 rounded-xl bg-purple-950/80 hover:bg-purple-800 border border-purple-400/30 text-purple-200 text-xs transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span>Hampers Budget 300rb</span>
                        </button>
                        <button @click="sendAiQuery('Kopi gula aren atau kopi klepon mana yang lebih disukai?')" class="px-3 py-1.5 rounded-xl bg-purple-950/80 hover:bg-purple-800 border border-purple-400/30 text-purple-200 text-xs transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                            <span>Komparasi Kopi</span>
                        </button>
                    </div>
                </div>

                <template x-for="(msg, index) in chatMessages" :key="'fmsg-'+index">
                    <div :class="msg.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="msg.sender === 'user' ? 'bg-purple-600 text-white rounded-2xl rounded-tr-none p-4 max-w-[80%]' : 'bg-purple-950/90 border border-purple-500/30 text-purple-100 rounded-2xl rounded-tl-none p-4 max-w-[85%] space-y-3'">
                            <p x-text="msg.text"></p>
                            
                            <template x-if="msg.products && msg.products.length">
                                <div class="mt-2 space-y-2 pt-2 border-t border-purple-500/20">
                                    <template x-for="prod in msg.products" :key="'fprod-'+prod.id">
                                        <div class="p-3 rounded-xl bg-purple-900/40 border border-purple-500/20 flex items-center gap-3">
                                            <img :src="prod.image" class="w-14 h-14 rounded-lg object-cover border border-purple-400/30">
                                            <div class="flex-1 min-w-0">
                                                <h5 class="text-xs font-bold text-white truncate" x-text="prod.name"></h5>
                                                <p class="text-[11px] text-emerald-400 font-medium" x-text="prod.impact_text"></p>
                                                <p class="text-xs font-semibold text-purple-300" x-text="formatRupiah(prod.price)"></p>
                                            </div>
                                            <button @click="addToCart(prod)" class="px-3 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-semibold shrink-0">
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
                <input type="text" x-model="customChatInput" placeholder="Tanyakan rekomendasi kado, kopi, atau fashion UMKM..." class="flex-1 px-4 py-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-xs text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">
                <button type="submit" class="px-6 py-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition">Kirim</button>
            </form>
        </div>
    </div>

    <!-- DEDICATED PAGE 7: FAVORIT SAYA -->
    <div x-show="activeTab === 'favorites'" class="space-y-6">
        <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
            <div>
                <h3 class="text-xl font-bold text-white font-heading">Produk Favorit Saya (Wishlist)</h3>
                <p class="text-xs text-purple-300/70">Daftar produk UMKM yang Anda simpan untuk dibeli nanti.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-red-500/20 text-red-300 text-xs font-bold border border-red-500/30" x-text="favorites.length + ' Produk Disimpan'"></span>
        </div>

        <template x-if="favoriteProducts.length === 0">
            <div class="text-center py-16 bg-purple-card rounded-3xl border border-purple-500/20 space-y-3">
                <p class="text-xs text-purple-300/70">Belum ada produk favorit. Tekan ikon simpan pada katalog untuk menyimpan produk.</p>
                <button @click="activeTab = 'katalog'" class="px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold">Jelajahi Katalog</button>
            </div>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="product in favoriteProducts" :key="'dfav-'+product.id">
                <div class="rounded-2xl bg-purple-card border border-purple-500/20 overflow-hidden flex flex-col justify-between">
                    <div class="relative h-48 bg-purple-950">
                        <img :src="product.image" class="w-full h-full object-cover">
                        <button @click="toggleFavorite(product)" class="absolute top-2.5 right-2.5 p-1.5 rounded-full bg-purple-950/80 text-red-500 border border-purple-400/30">
                            <svg class="w-4 h-4 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    </div>

                    <div class="p-4 space-y-2">
                        <span class="text-[10px] font-bold text-purple-400 uppercase" x-text="product.umkm"></span>
                        <h4 class="text-sm font-bold text-white" x-text="product.name"></h4>
                        <div class="text-base font-extrabold text-purple-300" x-text="formatRupiah(product.price)"></div>
                    </div>

                    <div class="p-4 pt-0">
                        <button @click="addToCart(product)" class="w-full py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition">
                            + Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- DEDICATED PAGE 8: RIWAYAT PESANAN & ULASAN PRODUK -->
    <div x-show="activeTab === 'orders'" class="space-y-6">
        <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
            <div>
                <h3 class="text-xl font-bold text-white font-heading">Riwayat Pesanan & Beri Ulasan</h3>
                <p class="text-xs text-purple-300/70">Daftar transaksi dan kesempatan memberikan ulasan untuk produk yang telah selesai dibeli.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30" x-text="ordersHistory.length + ' Pesanan'"></span>
        </div>

        <div class="space-y-4">
            <template x-for="order in ordersHistory" :key="'dorder-'+order.id">
                <div class="p-6 rounded-2xl bg-purple-card border border-purple-500/30 space-y-4 shadow-lg">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-purple-500/20 pb-3">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-purple-300" x-text="order.id"></span>
                            <span class="text-[11px] text-purple-400/70" x-text="order.date"></span>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                              :class="order.status === 'Selesai' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30' : 'bg-purple-500/20 text-purple-300 border border-purple-400/30'"
                              x-text="order.status"></span>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-white" x-text="order.items"></h4>
                            <p class="text-xs text-emerald-400 font-semibold" x-text="'Dampak: ' + order.impact"></p>
                        </div>
                        
                        <div class="flex flex-col md:items-end gap-2">
                            <div class="text-right">
                                <span class="text-[10px] text-purple-300/70">Total Pembayaran:</span>
                                <div class="text-base font-extrabold text-white" x-text="formatRupiah(order.total)"></div>
                            </div>

                            <!-- Ulasan Button Trigger -->
                            <template x-if="order.status === 'Selesai'">
                                <div>
                                    <template x-if="!order.reviewed">
                                        <button @click="openWriteReview(order)" class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-xs transition shadow-md flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span>Tulis Ulasan Produk</span>
                                        </button>
                                    </template>
                                    <template x-if="order.reviewed">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-950 text-purple-300 border border-purple-500/30 text-[11px] font-semibold">
                                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span>Ulasan Terpublikasi</span>
                                        </span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- DEDICATED PAGE 8B: FORM TULIS ULASAN PRODUK -->
    <div x-show="activeTab === 'write-review' && reviewingOrder" class="space-y-6">
        <button @click="activeTab = 'orders'" class="inline-flex items-center gap-2 text-xs text-purple-300 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Riwayat Pesanan</span>
        </button>

        <template x-if="reviewingOrder">
            <div class="max-w-xl p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-6">
                <div class="space-y-1 border-b border-purple-500/20 pb-4">
                    <span class="text-xs font-bold text-purple-400 uppercase tracking-widest" x-text="'Pesanan: ' + reviewingOrder.id"></span>
                    <h3 class="text-xl font-bold text-white font-heading">Berikan Ulasan Produk & Pengalaman Belanja</h3>
                    <p class="text-xs text-purple-300/70" x-text="reviewingOrder.items"></p>
                </div>

                <form @submit.prevent="submitProductReview()" class="space-y-5">
                    <!-- Rating Star Selection -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-purple-200">Pilih Rating Bintang:</label>
                        <div class="flex items-center gap-2">
                            <template x-for="star in [1, 2, 3, 4, 5]" :key="'wstar-'+star">
                                <button type="button" @click="newReviewForm.rating = star" class="transition hover:scale-125 focus:outline-none" :class="star <= newReviewForm.rating ? 'text-amber-400' : 'text-purple-950 hover:text-amber-300'">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </button>
                            </template>
                            <span class="text-xs font-bold text-purple-300 ml-2" x-text="newReviewForm.rating + ' dari 5 Bintang'"></span>
                        </div>
                    </div>

                    <!-- Review Text Field -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-purple-200">Tulis Ulasan Anda:</label>
                        <textarea x-model="newReviewForm.comment" rows="4" placeholder="Ceritakan kualitas produk, rasa, aroma, kain, atau dampak sosial yang Anda rasakan..." class="w-full p-4 rounded-2xl bg-purple-950/80 border border-purple-500/30 text-xs text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400 leading-relaxed"></textarea>
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
        <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
            <div>
                <h3 class="text-xl font-bold text-white font-heading">Pengaturan Profil & Alamat Pengiriman</h3>
                <p class="text-xs text-purple-300/70">Kelola informasi data diri Anda di ekosistem Grownesia.</p>
            </div>
        </div>

        <div class="max-w-xl p-6 rounded-3xl bg-purple-card border border-purple-500/30 space-y-4 shadow-xl">
            <form @submit.prevent="saveProfile()" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-purple-200 mb-1">Nama Lengkap</label>
                    <input type="text" x-model="userProfile.name" class="w-full p-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white">
                </div>

                <div>
                    <label class="block font-semibold text-purple-200 mb-1">Alamat Email</label>
                    <input type="email" x-model="userProfile.email" class="w-full p-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white">
                </div>

                <div>
                    <label class="block font-semibold text-purple-200 mb-1">Nomor WhatsApp</label>
                    <input type="text" x-model="userProfile.phone" class="w-full p-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white">
                </div>

                <div>
                    <label class="block font-semibold text-purple-200 mb-1">Alamat Pengiriman Utama</label>
                    <textarea x-model="userProfile.address" class="w-full p-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white" rows="3"></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition shadow-lg">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>
    </div>

    <!-- DEDICATED PAGE 10: AI GIFT RECOMMENDATION -->
    <div x-show="activeTab === 'ai-gift'" class="p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
            <div class="space-y-1">
                <span class="text-xs font-extrabold uppercase tracking-widest text-fuchsia-400">Racikan AI Kustom</span>
                <h3 class="text-xl font-bold text-white font-heading">AI Gift & Custom Package Creator</h3>
                <p class="text-xs text-purple-300/70">Atur anggaran dan ketik penerima/tujuan kado apa saja secara bebas!</p>
            </div>
            
            <div class="flex bg-purple-950 p-1 rounded-xl border border-purple-500/30 text-xs font-semibold">
                <button @click="giftRecipientMode = 'preset'; generateGiftBundle()" :class="giftRecipientMode === 'preset' ? 'bg-purple-600 text-white' : 'text-purple-300 hover:text-white'" class="px-3 py-1.5 rounded-lg transition">
                    Pilihan Preset
                </button>
                <button @click="giftRecipientMode = 'custom'; generateGiftBundle()" :class="giftRecipientMode === 'custom' ? 'bg-purple-600 text-white' : 'text-purple-300 hover:text-white'" class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <span>Input Custom</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
            <div class="md:col-span-5 space-y-5 bg-purple-950/60 p-5 rounded-2xl border border-purple-500/20">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-purple-200">
                        Anggaran Belanja: <span class="text-purple-300 text-base font-extrabold" x-text="formatRupiah(giftBudget)"></span>
                    </label>
                    <input type="range" min="50000" max="600000" step="25000" x-model="giftBudget" @input="generateGiftBundle()" class="w-full accent-purple-500 cursor-pointer">
                </div>

                <div x-show="giftRecipientMode === 'preset'" class="space-y-2">
                    <label class="block text-xs font-bold text-purple-200">Target Penerima (Preset):</label>
                    <select x-model="giftRecipient" @change="generateGiftBundle()" class="w-full p-3 rounded-xl bg-purple-900/80 border border-purple-500/30 text-xs text-white">
                        <option value="Hadiah untuk Ibu / Orang Tua (Usia 50th)">Hadiah untuk Ibu / Orang Tua</option>
                        <option value="Hadiah Ulang Tahun untuk Sahabat">Hadiah Ulang Tahun untuk Sahabat</option>
                        <option value="Hampers Formal untuk Rekan Kerja & Dosen">Hampers Rekan Kerja / Dosen</option>
                        <option value="Souvenir Penggemar Kopi Nusantara">Souvenir Penggemar Kopi</option>
                    </select>
                </div>

                <div x-show="giftRecipientMode === 'custom'" class="space-y-3">
                    <label class="block text-xs font-bold text-purple-200">Ketik Kebutuhan Hadiah / Penerima Kustom:</label>
                    <input type="text"
                           x-model="customGiftRecipientInput"
                           @input.debounce.300ms="generateGiftBundle()"
                           placeholder="Contoh: Hadiah dosen pembimbing 45th..."
                           class="w-full p-3 rounded-xl bg-purple-900/80 border border-purple-400/40 text-xs text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">
                    
                    <div class="space-y-1.5 pt-1">
                        <span class="text-[10px] text-purple-400 font-semibold uppercase tracking-wider">Coba Kueri Cepat:</span>
                        <div class="flex flex-wrap gap-1.5 text-[11px]">
                            <button @click="giftRecipientMode = 'custom'; customGiftRecipientInput = 'Hadiah ulang tahun untuk dosen pembimbing 45th'; generateGiftBundle()" class="px-2.5 py-1 rounded-lg bg-purple-900/60 hover:bg-purple-800 text-purple-200 border border-purple-500/30 transition flex items-center gap-1">
                                <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                                <span>Dosen Pembimbing</span>
                            </button>
                            <button @click="giftRecipientMode = 'custom'; customGiftRecipientInput = 'Hampers wisuda batik & kopi untuk sahabat'; generateGiftBundle()" class="px-2.5 py-1 rounded-lg bg-purple-900/60 hover:bg-purple-800 text-purple-200 border border-purple-500/30 transition flex items-center gap-1">
                                <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <span>Hampers Wisuda</span>
                            </button>
                            <button @click="giftRecipientMode = 'custom'; customGiftRecipientInput = 'Kado syukuran rumah baru produk eco-friendly'; generateGiftBundle()" class="px-2.5 py-1 rounded-lg bg-purple-900/60 hover:bg-purple-800 text-purple-200 border border-purple-500/30 transition flex items-center gap-1">
                                <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <span>Syukuran Rumah</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-7">
                <div x-show="generatedBundle" class="p-6 rounded-2xl bg-purple-950/90 border border-purple-400/40 space-y-4 shadow-xl">
                    <div class="flex items-center justify-between border-b border-purple-500/20 pb-3">
                        <div class="space-y-0.5">
                            <span class="text-[10px] font-bold text-purple-400 uppercase tracking-widest">Racikan AI Terkomposisi</span>
                            <h4 class="text-sm font-bold text-white" x-text="'Kriteria: ' + generatedBundle.recipientLabel"></h4>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-purple-300/70">Total Estimasi:</span>
                            <div class="text-lg font-extrabold text-purple-300" x-text="formatRupiah(generatedBundle.totalPrice)"></div>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-purple-900/50 border border-purple-500/30 text-xs text-purple-200 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-purple-300">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Analisis Rekomendasi AI:</span>
                        </div>
                        <p class="text-[11px] text-purple-200/90" x-text="generatedBundle.aiReasoning"></p>
                    </div>

                    <div class="space-y-2">
                        <span class="text-xs font-semibold text-purple-300">Produk yang Termasuk dalam Paket ini:</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="item in generatedBundle.items" :key="'gbund2-'+item.id">
                                <div class="p-3 rounded-xl bg-purple-900/40 border border-purple-500/20 flex items-center gap-3">
                                    <img :src="item.image" class="w-12 h-12 rounded-lg object-cover border border-purple-400/30 shrink-0">
                                    <div class="min-w-0">
                                        <h5 class="text-xs font-bold text-white truncate" x-text="item.name"></h5>
                                        <p class="text-[10px] text-purple-300/70" x-text="item.umkm"></p>
                                        <span class="text-xs font-bold text-purple-200" x-text="formatRupiah(item.price)"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Social Impact: <strong x-text="generatedBundle.impact"></strong></span>
                    </div>

                    <button @click="addBundleToCart()" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-600 hover:from-fuchsia-500 hover:to-purple-500 text-white font-bold text-xs transition shadow-lg flex items-center justify-center gap-2">
                        <span>+ Tambah Semua Paket Ini ke Keranjang & Checkout</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- DEDICATED PAGE 11: AI PRODUCT COMPARISON -->
    <div x-show="activeTab === 'ai-compare'" class="p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-6">
        <div class="space-y-1">
            <span class="text-xs font-extrabold uppercase tracking-widest text-purple-400">Komparator Produk AI</span>
            <h3 class="text-xl font-bold text-white font-heading">AI Product Comparison</h3>
            <p class="text-xs text-purple-300/70">Bandingkan dua produk UMKM secara berdampingan untuk keputusan belanja tepat.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-purple-200 mb-1">Produk A:</label>
                <select x-model="compareProduct1" @change="generateAiComparison()" class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-xs text-white">
                    <template x-for="p in products" :key="'cmp1-'+p.id">
                        <option :value="p" x-text="p.name + ' (' + formatRupiah(p.price) + ')'"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-purple-200 mb-1">Produk B:</label>
                <select x-model="compareProduct2" @change="generateAiComparison()" class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-xs text-white">
                    <template x-for="p in products" :key="'cmp2-'+p.id">
                        <option :value="p" x-text="p.name + ' (' + formatRupiah(p.price) + ')'"></option>
                    </template>
                </select>
            </div>
        </div>

        <template x-if="compareProduct1 && compareProduct2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div class="p-4 rounded-2xl bg-purple-950/80 border border-purple-500/30 space-y-2 text-xs">
                    <span class="font-bold text-purple-300">Produk A: <span x-text="compareProduct1.name"></span></span>
                    <div class="text-emerald-400 font-semibold" x-text="compareProduct1.impact_text"></div>
                    <div class="text-white font-bold" x-text="formatRupiah(compareProduct1.price)"></div>
                </div>

                <div class="p-4 rounded-2xl bg-purple-950/80 border border-purple-500/30 space-y-2 text-xs">
                    <span class="font-bold text-fuchsia-300">Produk B: <span x-text="compareProduct2.name"></span></span>
                    <div class="text-emerald-400 font-semibold" x-text="compareProduct2.impact_text"></div>
                    <div class="text-white font-bold" x-text="formatRupiah(compareProduct2.price)"></div>
                </div>
            </div>
        </template>

        <template x-if="aiCompareAnalysis">
            <div class="p-4 rounded-2xl bg-purple-900/60 border border-purple-400/40 text-xs space-y-1">
                <div class="font-bold text-purple-200 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Analisis AI:</span>
                </div>
                <p class="text-purple-200/90" x-text="aiCompareAnalysis.verdict"></p>
            </div>
        </template>
    </div>

</div>

@endsection
