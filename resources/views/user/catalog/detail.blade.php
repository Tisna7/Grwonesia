<!-- DEDICATED PAGE 2: DETAIL PRODUK & REVIEWS -->
<div x-show="activeTab === 'detail' && selectedProductDetail" class="space-y-6">
  <button @click="activeTab = 'katalog'"
    class="inline-flex items-center gap-2 text-xs text-purple-300 hover:text-white transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <span>Kembali ke Katalog Produk</span>
  </button>

  <template x-if="selectedProductDetail">
    <div class="space-y-6">
      <!-- Main Product Detail Card -->
      <div class="p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
          <div class="flex items-center gap-3">
            <span
              class="px-3 py-1 rounded-full text-xs font-bold bg-purple-800/40 text-purple-300 border border-purple-400/30"
              x-text="selectedProductDetail.category"></span>
            <span class="text-xs text-emerald-400 font-semibold flex items-center gap-1">
              <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <span x-text="selectedProductDetail.rating"></span> (<span
                x-text="getProductReviews(selectedProductDetail.id).length + 45"></span> ulasan pembeli)
            </span>
          </div>
          <button @click="toggleFavorite(selectedProductDetail)"
            class="px-3 py-1.5 rounded-xl bg-purple-950 border border-purple-500/30 text-xs font-bold text-purple-200 flex items-center gap-1.5">
            <svg class="w-4 h-4"
              :class="isFavorite(selectedProductDetail.id) ? 'text-red-500 fill-current' : 'text-purple-300'"
              fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span x-text="isFavorite(selectedProductDetail.id) ? 'Disimpan di Favorit' : '+ Simpan ke Favorit'"></span>
          </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
          <div class="md:col-span-5 h-72 rounded-2xl overflow-hidden bg-purple-950 border border-purple-400/30">
            <img :src="selectedProductDetail.image" loading="lazy" class="w-full h-full object-cover">
          </div>

          <div class="md:col-span-7 space-y-4">
            <span class="text-xs font-bold text-purple-400 uppercase tracking-widest"
              x-text="'Penjual: ' + selectedProductDetail.umkm + ' (' + selectedProductDetail.region + ')'"></span>
            <h2 class="text-2xl font-extrabold text-white font-heading" x-text="selectedProductDetail.name"></h2>
            <p class="text-xs sm:text-sm text-purple-200/80 leading-relaxed" x-text="selectedProductDetail.description">
            </p>

            <div
              class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-xs space-y-1">
              <div class="flex items-center gap-1.5 font-bold">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Social Impact Certified: <span x-text="selectedProductDetail.impact_text"></span></span>
              </div>
              <p class="text-[11px] text-emerald-400/80">Setiap pembelian produk ini berkontribusi langsung pada
                pendapatan keluarga mitra UMKM lokal.</p>
            </div>

            <div class="flex items-baseline gap-3 pt-2">
              <span class="text-3xl font-extrabold text-white"
                x-text="formatRupiah(selectedProductDetail.price)"></span>
              <span class="text-sm text-purple-400/60 line-through"
                x-text="formatRupiah(selectedProductDetail.original_price)"></span>
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
              <button @click="addToCart(selectedProductDetail)"
                class="px-6 py-3.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition shadow-lg">
                + Tambah ke Keranjang
              </button>
              <button @click="addToCart(selectedProductDetail)"
                class="px-6 py-3.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs transition shadow-lg">
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
              <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <span>Ulasan & Rating Pembeli</span>
              <span
                class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Verified
                Buyers</span>
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
                  <div
                    class="w-8 h-8 rounded-full bg-purple-800 text-white font-bold flex items-center justify-center text-xs"
                    x-text="rev.userName.charAt(0)"></div>
                  <div>
                    <span class="text-xs font-bold text-white" x-text="rev.userName"></span>
                    <span
                      class="text-[10px] ml-1.5 px-2 py-0.5 rounded bg-emerald-950 text-emerald-300 border border-emerald-500/30">Pembeli
                      Terverifikasi</span>
                  </div>
                </div>
                <span class="text-[10px] text-purple-400/70" x-text="rev.date"></span>
              </div>

              <div class="flex items-center gap-1 text-amber-400 text-xs">
                <template x-for="star in rev.rating" :key="'st-'+star">
                  <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
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