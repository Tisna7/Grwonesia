<!-- DEDICATED PAGE 1: KATALOG PRODUK -->
<div x-show="activeTab === 'katalog'" class="space-y-6">
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h3 class="text-xl font-bold text-white font-heading">Katalog Produk UMKM Nusantara</h3>
      <p class="text-xs text-purple-300/70">PILIH DAN BELI PRODUK BERKUALITAS SECARA LANGSUNG</p>
    </div>

    <!-- Search Bar inside Workspace -->
    <div class="relative w-full md:w-80">
      <input type="text" x-model="searchQuery" placeholder="Cari batik, kopi, kerajinan..."
        class="w-full pl-9 pr-4 py-2 rounded-xl bg-purple-950/80 border border-purple-500/30 text-xs text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">
      <svg class="w-4 h-4 text-purple-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </div>
  </div>

  <!-- Category Filter Pills -->
  <div
    class="flex flex-wrap items-center justify-between gap-3 bg-purple-950/40 p-3 rounded-2xl border border-purple-500/20 text-xs">
    <div class="flex flex-wrap items-center gap-2">
      <button @click="activeCategory = 'all'"
        :class="activeCategory === 'all' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-900/40 text-purple-300 hover:bg-purple-800 border-purple-500/20'"
        class="px-3 py-1.5 rounded-xl border font-semibold transition">
        Semua
      </button>
      <button @click="activeCategory = 'kopi'"
        :class="activeCategory === 'kopi' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-900/40 text-purple-300 hover:bg-purple-800 border-purple-500/20'"
        class="px-3 py-1.5 rounded-xl border font-semibold transition flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0" />
        </svg>
        <span>Kopi & Minuman</span>
      </button>
      <button @click="activeCategory = 'batik'"
        :class="activeCategory === 'batik' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-900/40 text-purple-300 hover:bg-purple-800 border-purple-500/20'"
        class="px-3 py-1.5 rounded-xl border font-semibold transition flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5 text-fuchsia-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-23" />
        </svg>
        <span>Batik & Fashion</span>
      </button>
      <button @click="activeCategory = 'kerajinan'"
        :class="activeCategory === 'kerajinan' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-900/40 text-purple-300 hover:bg-purple-800 border-purple-500/20'"
        class="px-3 py-1.5 rounded-xl border font-semibold transition flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <span>Kerajinan & Craft</span>
      </button>
      <button @click="activeCategory = 'makanan'"
        :class="activeCategory === 'makanan' ? 'bg-purple-600 text-white border-purple-400' : 'bg-purple-900/40 text-purple-300 hover:bg-purple-800 border-purple-500/20'"
        class="px-3 py-1.5 rounded-xl border font-semibold transition flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <span>Makanan Ringan</span>
      </button>
    </div>
  </div>

  <!-- Product Cards Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <template x-for="product in filteredProducts" :key="product.id">
      <div
        class="group rounded-2xl bg-purple-card border border-purple-500/20 overflow-hidden bg-purple-card-hover transition duration-300 flex flex-col justify-between relative">
        <div>
          <div class="relative h-48 overflow-hidden bg-purple-950 cursor-pointer" @click="openProductDetail(product)">
            <img :src="product.image" loading="lazy"
              class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
            <div
              class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-md bg-purple-950/80 border border-purple-400/30 text-[10px] text-purple-200 flex items-center gap-1">
              <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span x-text="product.region"></span>
            </div>

            <!-- Heart Favorite Button -->
            <button @click.stop="toggleFavorite(product)"
              class="absolute top-2.5 right-2.5 p-1.5 rounded-full bg-purple-950/80 text-white border border-purple-400/30 hover:scale-110 transition z-10">
              <svg class="w-4 h-4" :class="isFavorite(product.id) ? 'text-red-500 fill-current' : 'text-purple-300'"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </button>
          </div>

          <div class="p-4 space-y-2">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-semibold text-purple-400 uppercase tracking-wider"
                x-text="product.umkm"></span>
              <button @click="openProductDetail(product)"
                class="text-[10px] text-purple-300 hover:text-white underline flex items-center gap-1">
                <span>Detail & Review</span>
                <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            <h4 @click="openProductDetail(product)"
              class="text-sm font-bold text-white group-hover:text-purple-300 transition line-clamp-1 cursor-pointer"
              x-text="product.name"></h4>
            <p class="text-xs text-purple-300/70 line-clamp-2" x-text="product.description"></p>
          </div>
        </div>

        <div class="p-4 pt-0 space-y-2.5">
          <div class="flex items-baseline justify-between border-t border-purple-500/10 pt-2.5">
            <div>
              <span class="text-[11px] text-purple-400/60 line-through mr-1"
                x-text="formatRupiah(product.original_price)"></span>
              <span class="text-base font-extrabold text-white" x-text="formatRupiah(product.price)"></span>
            </div>
            <button @click="compareProduct2 = product; activeTab = 'ai-compare'"
              class="text-[11px] text-purple-300 hover:text-white underline flex items-center gap-1">
              <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <span>Bandingkan</span>
            </button>
          </div>

          <div class="grid grid-cols-2 gap-2">
            <button @click="addToCart(product, false)"
              class="w-full py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition">
              + Keranjang
            </button>
            <button @click="addToCart(product)"
              class="w-full py-2 rounded-xl bg-purple-950 hover:bg-purple-900 border border-purple-500/30 text-purple-200 font-bold text-xs transition">
              Beli Langsung
            </button>
          </div>
        </div>
      </div>
    </template>
  </div>
</div>