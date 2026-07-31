<!-- DEDICATED PAGE 3: KERANJANG & CHECKOUT -->
<div x-show="activeTab === 'cart'" class="space-y-6">
  <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
    <div>
      <h3 class="text-xl font-bold text-white font-heading">Halaman Keranjang & Checkout Pembayaran</h3>
      <p class="text-xs text-purple-300/70">Periksa item belanja Anda dan lakukan checkout secara aman.</p>
    </div>
    <button @click="activeTab = 'katalog'" class="text-xs text-purple-300 hover:text-white underline">← Kembali
      Belanja</button>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Left: Cart Items List -->
    <div class="lg:col-span-7 space-y-4">
      <div class="flex items-center justify-between border-b border-purple-500/20 pb-2">
        <h4 class="text-sm font-bold text-purple-300 uppercase tracking-wider">Item dalam Keranjang</h4>
        
        <template x-if="cart.length > 0">
          <div @click="toggleSelectAll(!allSelected)" class="flex items-center gap-2 cursor-pointer select-none group">
            <div class="w-5 h-5 rounded-lg border transition-all duration-200 flex items-center justify-center shrink-0"
              :class="allSelected ? 'bg-gradient-to-tr from-purple-600 to-fuchsia-600 border-purple-400 text-white shadow-lg shadow-purple-900/50 scale-105' : 'bg-purple-950/80 border-purple-500/40 text-transparent group-hover:border-purple-400'">
              <svg class="w-3.5 h-3.5 stroke-current" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <span class="text-xs text-purple-300/90 group-hover:text-white font-semibold transition">
              Pilih Semua (<span x-text="selectedCartItems.length"></span>/<span x-text="cart.length"></span> Produk)
            </span>
          </div>
        </template>
      </div>

      <template x-if="cart.length === 0">
        <div class="p-8 text-center bg-purple-card rounded-2xl border border-purple-500/20 space-y-3">
          <p class="text-xs text-purple-300/70">Keranjang Anda masih kosong.</p>
          <button @click="activeTab = 'katalog'"
            class="px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold">Mulai Belanja</button>
        </div>
      </template>

      <template x-for="(item, idx) in cart" :key="'citem-'+idx">
        <template x-if="item.product">
          <div class="p-4 rounded-2xl bg-purple-card border transition flex gap-3.5 items-center"
            :class="item.selected !== false ? 'border-purple-500/60 bg-purple-900/20 shadow-md shadow-purple-950/50' : 'border-purple-500/10 opacity-60 bg-purple-950/30'">
            
            <!-- Custom Styled Checkbox Button -->
            <button type="button" @click="item.selected = !(item.selected !== false); saveCart()"
              class="w-5 h-5 rounded-lg border transition-all duration-200 flex items-center justify-center shrink-0 select-none group focus:outline-none"
              :class="item.selected !== false ? 'bg-gradient-to-tr from-purple-600 to-fuchsia-600 border-purple-400 text-white shadow-md shadow-purple-900/50 scale-105' : 'bg-purple-950/90 border-purple-500/40 text-transparent hover:border-purple-400'">
              <svg class="w-3.5 h-3.5 stroke-current" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
            </button>

            <img :src="item.product.image || item.product.image_url || '/images/products/kopi_gula_aren.webp'" loading="lazy"
              class="w-16 h-16 rounded-xl object-cover border border-purple-400/30 shrink-0">
            <div class="flex-1 min-w-0">
              <h4 class="text-sm font-bold text-white truncate" x-text="item.product.name"></h4>
              <p class="text-xs text-purple-300/70" x-text="item.product.umkm"></p>
              <p class="text-xs font-bold text-purple-300 mt-1" x-text="formatRupiah(item.product.price)"></p>
            </div>
            <div class="flex items-center gap-2 bg-purple-950 rounded-xl p-1.5 border border-purple-500/30">
              <button @click="updateQty(idx, -1)"
                class="w-6 h-6 rounded text-purple-300 hover:bg-purple-800 text-xs font-bold flex items-center justify-center">-</button>
              <span class="text-xs font-bold px-2 text-white" x-text="item.qty"></span>
              <button @click="updateQty(idx, 1)"
                class="w-6 h-6 rounded text-purple-300 hover:bg-purple-800 text-xs font-bold flex items-center justify-center">+</button>
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
            <textarea x-model="userProfile.address"
              class="w-full p-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-purple-100"
              rows="2"></textarea>
          </div>

          <div>
            <label class="block font-semibold text-purple-200 mb-1">Pilih Metode Pembayaran</label>
            <div class="grid grid-cols-3 gap-2">
              <button @click="selectedPaymentMethod = 'qris'"
                :class="selectedPaymentMethod === 'qris' ? 'border-purple-400 bg-purple-800/60' : 'border-purple-500/30 bg-purple-950/80'"
                class="p-2.5 rounded-xl border text-center font-bold text-white text-[11px]">
                QRIS Instant
              </button>
              <button @click="selectedPaymentMethod = 'gopay'"
                :class="selectedPaymentMethod === 'gopay' ? 'border-purple-400 bg-purple-800/60' : 'border-purple-500/30 bg-purple-950/80'"
                class="p-2.5 rounded-xl border text-center font-bold text-white text-[11px]">
                GoPay / OVO
              </button>
              <button @click="selectedPaymentMethod = 'bank'"
                :class="selectedPaymentMethod === 'bank' ? 'border-purple-400 bg-purple-800/60' : 'border-purple-500/30 bg-purple-950/80'"
                class="p-2.5 rounded-xl border text-center font-bold text-white text-[11px]">
                Transfer Bank
              </button>
            </div>
          </div>

          <div
            class="p-3.5 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-xs flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>Social Impact: <strong x-text="calculateCartImpactText()"></strong></span>
          </div>

          <div class="p-3.5 rounded-xl bg-purple-950/90 border border-purple-500/30 space-y-2">
            <div class="flex justify-between">
              <span class="text-purple-300/70">Subtotal Produk (<span x-text="selectedCartTotalCount"></span> item)</span>
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

        <button @click="processPaymentSuccess()"
          :disabled="selectedCartItems.length === 0"
          :class="selectedCartItems.length === 0 ? 'opacity-50 cursor-not-allowed from-gray-600 to-gray-700' : 'from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500'"
          class="w-full py-3.5 rounded-xl bg-gradient-to-r text-white font-bold text-xs transition shadow-lg flex items-center justify-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span x-text="selectedCartItems.length === 0 ? 'Pilih Produk untuk Checkout' : 'Bayar Sekarang (' + selectedCartItems.length + ' Produk)'"></span>
        </button>

        <div class="flex items-center gap-2 pt-2 border-t border-purple-500/20">
          <button @click="shareCheckoutToWhatsapp()"
            class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition shadow-md flex items-center justify-center gap-2"
            title="Bagikan Halaman Checkout ke WhatsApp">
            <svg class="w-4 h-4 fill-current text-white" viewBox="0 0 24 24">
              <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
            </svg>
            <span>Share Link Checkout ke WhatsApp</span>
          </button>
          <button @click="copyCurrentUrl()"
            class="px-3 py-2.5 rounded-xl bg-purple-950 hover:bg-purple-900 border border-purple-500/30 text-purple-200 font-bold text-xs transition flex items-center gap-1 shrink-0"
            title="Salin Link Checkout">
            <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>