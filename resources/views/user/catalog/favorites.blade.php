<!-- DEDICATED PAGE 7: FAVORIT SAYA -->
<div x-show="activeTab === 'favorites'" class="space-y-6">
  <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
    <div>
      <h3 class="text-xl font-bold text-white font-heading">Produk Favorit Saya (Wishlist)</h3>
      <p class="text-xs text-purple-300/70">Daftar produk UMKM yang Anda simpan untuk dibeli nanti.</p>
    </div>
    <span class="px-3 py-1 rounded-full bg-red-500/20 text-red-300 text-xs font-bold border border-red-500/30"
      x-text="favorites.length + ' Produk Disimpan'"></span>
  </div>

  <template x-if="favoriteProducts.length === 0">
    <div class="text-center py-16 bg-purple-card rounded-3xl border border-purple-500/20 space-y-3">
      <p class="text-xs text-purple-300/70">Belum ada produk favorit. Tekan ikon simpan pada katalog untuk menyimpan
        produk.</p>
      <button @click="activeTab = 'katalog'"
        class="px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold">Jelajahi Katalog</button>
    </div>
  </template>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <template x-for="product in favoriteProducts" :key="'dfav-'+product.id">
      <div class="rounded-2xl bg-purple-card border border-purple-500/20 overflow-hidden flex flex-col justify-between">
        <div class="relative h-48 bg-purple-950">
          <img :src="product.image" loading="lazy" class="w-full h-full object-cover">
          <button @click="toggleFavorite(product)"
            class="absolute top-2.5 right-2.5 p-1.5 rounded-full bg-purple-950/80 text-red-500 border border-purple-400/30">
            <svg class="w-4 h-4 text-red-500 fill-current" viewBox="0 0 24 24">
              <path
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
          </button>
        </div>

        <div class="p-4 space-y-2">
          <span class="text-[10px] font-bold text-purple-400 uppercase" x-text="product.umkm"></span>
          <h4 class="text-sm font-bold text-white" x-text="product.name"></h4>
          <div class="text-base font-extrabold text-purple-300" x-text="formatRupiah(product.price)"></div>
        </div>

        <div class="p-4 pt-0">
          <button @click="addToCart(product)"
            class="w-full py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition">
            + Tambah ke Keranjang
          </button>
        </div>
      </div>
    </template>
  </div>
</div>