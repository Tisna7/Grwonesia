@extends('layouts.app')

@section('content')

<!-- HERO SECTION -->
<section class="relative overflow-hidden pt-12 pb-20 border-b border-purple-500/20 bg-gradient-to-b from-purple-950/60 via-purple-dark to-purple-dark">
    <!-- Glowing background elements -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-purple-600/20 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-purple-500/10 border border-purple-400/30 text-purple-300 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
                    Ekosistem Digital UMKM Indonesia Berbasis AI
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight font-heading text-white leading-tight">
                    Belanja Produk UMKM <br>
                    <span class="text-gradient-purple">Berdampak Nyata</span> dengan AI
                </h1>

                <p class="text-base sm:text-lg text-purple-200/80 max-w-2xl leading-relaxed">
                    Setiap transaksi di <strong>Grownesia</strong> didukung kecerdasan buatan untuk membantu Anda menemukan produk terbaik sekaligus memberikan <em>Impact Score</em> langsung kepada pekerja lokal dan pengrajin desa.
                </p>

                <!-- Hero Buttons -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                    <button @click="scrollToSection('katalog')" class="px-7 py-3.5 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-sm transition shadow-lg shadow-purple-900/50 flex items-center gap-2">
                        <span>Jelajahi Produk UMKM</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <button @click="showAiDrawer = true" class="px-7 py-3.5 rounded-xl bg-purple-950/80 hover:bg-purple-900 text-purple-200 font-bold text-sm border border-purple-500/30 transition flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Tanya AI Assistant</span>
                    </button>
                </div>

                <!-- Stats Ticker -->
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-purple-500/20">
                    <div>
                        <div class="text-2xl font-extrabold text-white font-heading">1,420+</div>
                        <div class="text-xs text-purple-300/70">UMKM Mitra</div>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-emerald-400 font-heading">18.5k+</div>
                        <div class="text-xs text-purple-300/70">Pekerja Terbantu</div>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-purple-300 font-heading">120+</div>
                        <div class="text-xs text-purple-300/70">Desa Berkembang</div>
                    </div>
                </div>
            </div>

            <!-- Right Hero Visual Card -->
            <div class="lg:col-span-5">
                <div class="relative rounded-3xl p-6 bg-purple-card border border-purple-500/30 shadow-2xl glow-purple space-y-5">
                    <!-- Top AI Status -->
                    <div class="flex items-center justify-between border-b border-purple-500/20 pb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-emerald-400 animate-ping"></div>
                            <span class="text-xs font-bold text-purple-200">AI Personal Shopper Active</span>
                        </div>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-800/40 text-purple-300 border border-purple-500/30">User: Budi S.</span>
                    </div>

                    <!-- Personal Shopper Greeting -->
                    <div class="p-4 rounded-2xl bg-purple-900/40 border border-purple-500/20 space-y-2">
                        <div class="flex items-center gap-2 text-xs font-bold text-purple-300">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Rekomendasi Berdasarkan History Anda:
                        </div>
                        <p class="text-xs text-purple-200/90 leading-relaxed">
                            "Berdasarkan minat Anda pada <em>Kopi Robusta Sumsel</em> & <em>Kain Tenun</em>, AI merekomendasikan produk unggulan minggu ini:"
                        </p>
                    </div>

                    <!-- Highlighted Product Card inside Hero -->
                    <div class="p-3.5 rounded-2xl bg-purple-950/80 border border-purple-500/30 flex items-center gap-4">
                        <img src="/images/products/kopi_gula_aren.png" class="w-20 h-20 rounded-xl object-cover border border-purple-400/30 shrink-0">
                        <div class="flex-1 min-w-0 space-y-1">
                            <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider">Top Impact Product</span>
                            <h4 class="text-xs font-bold text-white truncate">Kopi Gula Aren Nusantara 500ml</h4>
                            <p class="text-[11px] text-purple-300/70">Kopi Wong Kito • Palembang</p>
                            <div class="flex items-center justify-between pt-1">
                                <span class="text-xs font-extrabold text-white">Rp 45.000</span>
                                <button @click="addToCart(products[0])" class="px-3 py-1 rounded-lg bg-purple-600 hover:bg-purple-500 text-white text-xs font-semibold">
                                    + Keranjang
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Prompt Bar -->
                    <button @click="showAiDrawer = true; sendAiQuery('Saya ingin hadiah untuk ibu umur 50 tahun.')" class="w-full py-2.5 px-4 rounded-xl bg-purple-800/40 hover:bg-purple-700/50 border border-purple-400/30 text-purple-200 text-xs font-medium transition flex items-center justify-between">
                        <span>💬 "Saya ingin hadiah untuk ibu umur 50 tahun."</span>
                        <span class="text-purple-400">Tanyakan AI →</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- KATALOG PRODUK UMKM (SEARCH, FILTER & IMPACT SCORE BADGES) -->
<section id="katalog" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="text-xs font-extrabold uppercase tracking-widest text-purple-400">Katalog Terverifikasi</span>
            <h2 class="text-3xl font-extrabold text-white font-heading mt-1">Produk UMKM Nusantara</h2>
            <p class="text-xs sm:text-sm text-purple-300/70">Cari & dukung produk lokal berkualitas dengan transparansi dampak sosial.</p>
        </div>

        <!-- Category Filters -->
        <div class="flex flex-wrap items-center gap-2">
            <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-950/60 text-purple-300 hover:bg-purple-900 border-purple-500/20'" class="px-3.5 py-1.5 rounded-xl border text-xs font-semibold transition">
                Semua Kategori
            </button>
            <button @click="activeCategory = 'kopi'" :class="activeCategory === 'kopi' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-950/60 text-purple-300 hover:bg-purple-900 border-purple-500/20'" class="px-3.5 py-1.5 rounded-xl border text-xs font-semibold transition">
                ☕ Kopi & Minuman
            </button>
            <button @click="activeCategory = 'batik'" :class="activeCategory === 'batik' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-950/60 text-purple-300 hover:bg-purple-900 border-purple-500/20'" class="px-3.5 py-1.5 rounded-xl border text-xs font-semibold transition">
                👗 Batik & Fashion
            </button>
            <button @click="activeCategory = 'kerajinan'" :class="activeCategory === 'kerajinan' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-950/60 text-purple-300 hover:bg-purple-900 border-purple-500/20'" class="px-3.5 py-1.5 rounded-xl border text-xs font-semibold transition">
                🎒 Kerajinan & Craft
            </button>
            <button @click="activeCategory = 'makanan'" :class="activeCategory === 'makanan' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-950/60 text-purple-300 hover:bg-purple-900 border-purple-500/20'" class="px-3.5 py-1.5 rounded-xl border text-xs font-semibold transition">
                🍟 Makanan Ringan
            </button>
        </div>
    </div>

    <!-- Impact Tag Filters -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-purple-500/10 text-xs">
        <span class="text-purple-400 font-semibold shrink-0">Filter Impact:</span>
        <button @click="selectedImpactFilter = 'all'" :class="selectedImpactFilter === 'all' ? 'bg-emerald-600/30 text-emerald-300 border-emerald-400/50' : 'bg-purple-950/40 text-purple-300/60 border-purple-500/20'" class="px-2.5 py-1 rounded-lg border text-[11px] font-medium transition shrink-0">
            Semua Impact
        </button>
        <button @click="selectedImpactFilter = 'Pemberdayaan Wanita'" :class="selectedImpactFilter === 'Pemberdayaan Wanita' ? 'bg-emerald-600/30 text-emerald-300 border-emerald-400/50' : 'bg-purple-950/40 text-purple-300/60 border-purple-500/20'" class="px-2.5 py-1 rounded-lg border text-[11px] font-medium transition shrink-0">
            👩 Pemberdayaan Wanita
        </button>
        <button @click="selectedImpactFilter = 'Eco-Friendly'" :class="selectedImpactFilter === 'Eco-Friendly' ? 'bg-emerald-600/30 text-emerald-300 border-emerald-400/50' : 'bg-purple-950/40 text-purple-300/60 border-purple-500/20'" class="px-2.5 py-1 rounded-lg border text-[11px] font-medium transition shrink-0">
            🌱 Eco-Friendly
        </button>
        <button @click="selectedImpactFilter = 'Desa Berkembang'" :class="selectedImpactFilter === 'Desa Berkembang' ? 'bg-emerald-600/30 text-emerald-300 border-emerald-400/50' : 'bg-purple-950/40 text-purple-300/60 border-purple-500/20'" class="px-2.5 py-1 rounded-lg border text-[11px] font-medium transition shrink-0">
            🏡 Desa Berkembang
        </button>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="product in filteredProducts" :key="product.id">
            <div class="group rounded-2xl bg-purple-card border border-purple-500/20 overflow-hidden bg-purple-card-hover transition-all duration-300 flex flex-col justify-between">
                
                <div>
                    <!-- Product Image Container -->
                    <div class="relative h-56 overflow-hidden bg-purple-950">
                        <img :src="product.image" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-purple-950 via-transparent to-transparent opacity-80"></div>
                        
                        <!-- Region Tag -->
                        <div class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-purple-950/80 backdrop-blur-md border border-purple-400/30 text-[11px] font-medium text-purple-200">
                            📍 <span x-text="product.region"></span>
                        </div>

                        <!-- Rating Badge -->
                        <div class="absolute top-3 right-3 px-2 py-1 rounded-lg bg-amber-500/20 backdrop-blur-md border border-amber-400/40 text-[11px] font-bold text-amber-300 flex items-center gap-1">
                            ⭐ <span x-text="product.rating"></span> (<span x-text="product.reviews_count"></span>)
                        </div>

                        <!-- Social Impact Score Badge -->
                        <div class="absolute bottom-3 left-3 right-3 px-3 py-1.5 rounded-xl bg-emerald-950/80 backdrop-blur-md border border-emerald-500/40 text-emerald-300 text-xs font-semibold flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span x-text="product.impact_text"></span>
                            </span>
                            <span class="text-[10px] bg-emerald-500/30 px-1.5 py-0.5 rounded text-white font-bold" x-text="product.impact_score + '/100'"></span>
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="p-5 space-y-3">
                        <div class="text-[11px] font-semibold text-purple-400 uppercase tracking-wider" x-text="product.umkm"></div>
                        <h3 class="text-base font-bold text-white group-hover:text-purple-300 transition line-clamp-1" x-text="product.name"></h3>
                        <p class="text-xs text-purple-300/70 line-clamp-2 leading-relaxed" x-text="product.description"></p>
                    </div>
                </div>

                <!-- Footer Price & Buy Buttons -->
                <div class="p-5 pt-0 space-y-3">
                    <div class="flex items-baseline justify-between border-t border-purple-500/10 pt-3">
                        <div>
                            <span class="text-xs text-purple-400/60 line-through mr-1" x-text="formatRupiah(product.original_price)"></span>
                            <span class="text-lg font-extrabold text-white" x-text="formatRupiah(product.price)"></span>
                        </div>
                        
                        <!-- AI Compare selection trigger -->
                        <button @click="compareProduct2 = product; scrollToSection('ai-compare')" class="text-[11px] text-purple-300 hover:text-white underline">
                            🔍 Bandingkan AI
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button @click="addToCart(product)" class="w-full py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition shadow-md flex items-center justify-center gap-1">
                            <span>+ Keranjang</span>
                        </button>
                        <button @click="addToCart(product); openCheckoutModal()" class="w-full py-2.5 rounded-xl bg-purple-950 hover:bg-purple-900 border border-purple-500/30 text-purple-200 font-bold text-xs transition flex items-center justify-center gap-1">
                            <span>Beli Langsung</span>
                        </button>
                    </div>
                </div>

            </div>
        </template>
    </div>
</section>

<!-- FEATURE SECTION: AI GIFT RECOMMENDATION (BUDGET BUNDLE CREATOR) -->
<section id="ai-gift" class="py-16 border-t border-purple-500/20 bg-gradient-to-b from-purple-dark via-purple-950/40 to-purple-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div class="text-center max-w-3xl mx-auto space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-widest text-fuchsia-400">Kombinasi Paket Cerdas</span>
            <h2 class="text-3xl font-extrabold text-white font-heading">AI Gift Recommendation & Budget Bundle</h2>
            <p class="text-xs sm:text-sm text-purple-300/70">
                Tentukan anggaran belanja Anda, dan biarkan AI meracik paket berisi produk UMKM terpilih yang memaksimalkan budget & dampak sosial!
            </p>
        </div>

        <!-- Budget Bundle Interactive Box -->
        <div class="max-w-4xl mx-auto p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-6">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
                <!-- Budget Slider -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-purple-200">
                        Target Budget Anda: <span class="text-purple-300 text-base" x-text="formatRupiah(giftBudget)"></span>
                    </label>
                    <input type="range" min="50000" max="600000" step="25000" x-model="giftBudget" @input="generateGiftBundle()" class="w-full accent-purple-500 cursor-pointer">
                    <div class="flex justify-between text-[10px] text-purple-400/60">
                        <span>Rp 50.000</span>
                        <span>Rp 300.000 (Populer)</span>
                        <span>Rp 600.000</span>
                    </div>
                </div>

                <!-- Recipient Selector -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-purple-200">Penerima Hadiah:</label>
                    <select x-model="giftRecipient" @change="generateGiftBundle()" class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-xs text-white">
                        <option value="ibu">Hadiah untuk Ibu / Orang Tua</option>
                        <option value="sahabat">Hadiah untuk Sahabat</option>
                        <option value="rekan">Hadiah Rekan Kerja / Hampers</option>
                        <option value="pasangan">Hadiah untuk Pasangan</option>
                    </select>
                </div>
            </div>

            <!-- Generated Bundle Output -->
            <div x-show="generatedBundle" class="p-5 rounded-2xl bg-purple-950/90 border border-purple-400/40 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-purple-500/20 pb-3">
                    <div>
                        <h4 class="text-sm font-bold text-white flex items-center gap-2">
                            <span>✨ Hasil Rekomendasi Paket AI</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-fuchsia-500/20 text-fuchsia-300 border border-fuchsia-400/30">Optimized</span>
                        </h4>
                        <p class="text-xs text-emerald-400 font-medium" x-text="generatedBundle.impact"></p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-purple-300/70">Total Estimasi:</span>
                        <div class="text-lg font-extrabold text-purple-300" x-text="formatRupiah(generatedBundle.totalPrice)"></div>
                    </div>
                </div>

                <!-- Items inside generated bundle -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <template x-for="item in generatedBundle.items" :key="item.id">
                        <div class="p-3 rounded-xl bg-purple-900/40 border border-purple-500/20 flex items-center gap-3">
                            <img :src="item.image" class="w-12 h-12 rounded-lg object-cover border border-purple-400/30 shrink-0">
                            <div class="min-w-0">
                                <h5 class="text-xs font-bold text-white truncate" x-text="item.name"></h5>
                                <p class="text-[10px] text-purple-300/70" x-text="item.umkm"></p>
                                <span class="text-xs font-semibold text-purple-200" x-text="formatRupiah(item.price)"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Action button -->
                <button @click="addBundleToCart()" class="w-full py-3 rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-600 hover:from-fuchsia-500 hover:to-purple-500 text-white font-bold text-xs transition shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Semua Paket Ini ke Keranjang
                </button>
            </div>

        </div>

    </div>
</section>

<!-- FEATURE SECTION: AI PRODUCT COMPARISON TOOL -->
<section id="ai-compare" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    <div class="text-center max-w-3xl mx-auto space-y-2">
        <span class="text-xs font-extrabold uppercase tracking-widest text-purple-400">Komparator Cerdas</span>
        <h2 class="text-3xl font-extrabold text-white font-heading">AI Product Comparison</h2>
        <p class="text-xs sm:text-sm text-purple-300/70">
            Bingung memilih antara dua produk? Biarkan AI membandingkan harga, kualitas, ulasan, hingga skor dampak sosialnya secara akurat!
        </p>
    </div>

    <!-- Comparison Selectors & Table -->
    <div class="p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-6">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Product 1 Selector -->
            <div>
                <label class="block text-xs font-bold text-purple-200 mb-1">Pilih Produk Pertama (A):</label>
                <select x-model="compareProduct1" @change="generateAiComparison()" class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-xs text-white">
                    <template x-for="p in products" :key="'p1-'+p.id">
                        <option :value="p" x-text="p.name + ' (' + formatRupiah(p.price) + ')'"></option>
                    </template>
                </select>
            </div>

            <!-- Product 2 Selector -->
            <div>
                <label class="block text-xs font-bold text-purple-200 mb-1">Pilih Produk Kedua (B):</label>
                <select x-model="compareProduct2" @change="generateAiComparison()" class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-xs text-white">
                    <template x-for="p in products" :key="'p2-'+p.id">
                        <option :value="p" x-text="p.name + ' (' + formatRupiah(p.price) + ')'"></option>
                    </template>
                </select>
            </div>
        </div>

        <!-- Comparison Cards Display -->
        <template x-if="compareProduct1 && compareProduct2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-purple-500/20">
                
                <!-- Product A Card -->
                <div class="p-5 rounded-2xl bg-purple-950/80 border border-purple-500/30 space-y-3">
                    <div class="flex items-center gap-3">
                        <img :src="compareProduct1.image" class="w-16 h-16 rounded-xl object-cover border border-purple-400/30">
                        <div>
                            <span class="text-[10px] font-bold text-purple-400 uppercase">Produk A</span>
                            <h4 class="text-sm font-bold text-white" x-text="compareProduct1.name"></h4>
                            <span class="text-xs font-extrabold text-purple-300" x-text="formatRupiah(compareProduct1.price)"></span>
                        </div>
                    </div>
                    <ul class="text-xs space-y-1.5 text-purple-300/80 pt-2 border-t border-purple-500/20">
                        <li>📍 <strong>UMKM:</strong> <span x-text="compareProduct1.umkm + ' (' + compareProduct1.region + ')'"></span></li>
                        <li>⭐ <strong>Rating:</strong> <span x-text="compareProduct1.rating + ' / 5.0'"></span></li>
                        <li>🌱 <strong>Dampak:</strong> <span class="text-emerald-400" x-text="compareProduct1.impact_text"></span></li>
                    </ul>
                    <button @click="addToCart(compareProduct1)" class="w-full py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs">Pilih Produk A</button>
                </div>

                <!-- Product B Card -->
                <div class="p-5 rounded-2xl bg-purple-950/80 border border-purple-500/30 space-y-3">
                    <div class="flex items-center gap-3">
                        <img :src="compareProduct2.image" class="w-16 h-16 rounded-xl object-cover border border-purple-400/30">
                        <div>
                            <span class="text-[10px] font-bold text-fuchsia-400 uppercase">Produk B</span>
                            <h4 class="text-sm font-bold text-white" x-text="compareProduct2.name"></h4>
                            <span class="text-xs font-extrabold text-purple-300" x-text="formatRupiah(compareProduct2.price)"></span>
                        </div>
                    </div>
                    <ul class="text-xs space-y-1.5 text-purple-300/80 pt-2 border-t border-purple-500/20">
                        <li>📍 <strong>UMKM:</strong> <span x-text="compareProduct2.umkm + ' (' + compareProduct2.region + ')'"></span></li>
                        <li>⭐ <strong>Rating:</strong> <span x-text="compareProduct2.rating + ' / 5.0'"></span></li>
                        <li>🌱 <strong>Dampak:</strong> <span class="text-emerald-400" x-text="compareProduct2.impact_text"></span></li>
                    </ul>
                    <button @click="addToCart(compareProduct2)" class="w-full py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs">Pilih Produk B</button>
                </div>

            </div>
        </template>

        <!-- AI Verdict Banner -->
        <template x-if="aiCompareAnalysis">
            <div class="p-4 rounded-2xl bg-gradient-to-r from-purple-900/80 to-indigo-900/80 border border-purple-400/40 space-y-1 text-xs">
                <div class="flex items-center gap-2 font-bold text-purple-200">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Analisis & Rekomendasi AI:</span>
                </div>
                <p class="text-purple-200/90 leading-relaxed" x-text="aiCompareAnalysis.verdict"></p>
                <div class="text-emerald-400 font-semibold" x-text="aiCompareAnalysis.recommendation"></div>
            </div>
        </template>

    </div>
</section>

<!-- IMPACT SCORE COMMUNITY BANNER -->
<section class="py-16 border-t border-purple-500/20 bg-purple-950/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-purple-900 via-indigo-950 to-purple-950 border border-purple-400/30 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden shadow-2xl">
            
            <div class="space-y-4 max-w-2xl text-center md:text-left">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                    Impact Score System
                </span>
                <h2 class="text-3xl font-extrabold text-white font-heading">
                    Belanja Tanpa Ragu, Dorong Pertumbuhan Ekonomi Lokal
                </h2>
                <p class="text-xs sm:text-sm text-purple-200/80 leading-relaxed">
                    Di Grownesia, belanja bukan sekadar transaksi. Setiap rupiah yang Anda bayarkan tercatat dalam <em>Impact Score System</em> yang mendokumentasikan jumlah pekerja lokal, pemberdayaan wanita, dan desa yang terbantu.
                </p>
                <div class="flex items-center gap-4 text-xs font-semibold text-emerald-300 pt-2">
                    <span>✓ Transparansi 100%</span>
                    <span>✓ Langsung ke Rekening UMKM</span>
                    <span>✓ Verifikasi Komunitas</span>
                </div>
            </div>

            <div class="shrink-0 text-center space-y-3">
                <div class="w-32 h-32 rounded-3xl bg-purple-900/60 border border-purple-400/40 flex flex-col items-center justify-center p-4 glow-purple mx-auto">
                    <span class="text-3xl font-extrabold text-white font-heading" x-text="userImpact.totalJobs"></span>
                    <span class="text-[11px] text-purple-300 text-center font-medium">Pekerja Terbantu Oleh Anda</span>
                </div>
                <button @click="showImpactModal = true" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition">
                    Lihat Sertifikat Dampak
                </button>
            </div>

        </div>
    </div>
</section>

@endsection
