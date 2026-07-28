<!-- AI SHOPPING ASSISTANT FLOATING DRAWER -->
<div x-show="showAiDrawer" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
  class="fixed inset-0 z-50 overflow-hidden bg-purple-950/60 backdrop-blur-md flex justify-end" style="display: none;">

  <div @click.away="showAiDrawer = false"
    class="w-full max-w-md bg-purple-card border-l border-purple-500/30 h-full flex flex-col shadow-2xl">
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
      <button @click="showAiDrawer = false" class="p-1.5 rounded-lg text-purple-400 hover:text-white">✕</button>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-4 text-xs" id="chatContainer">
      <div class="p-3.5 rounded-2xl bg-purple-900/50 border border-purple-500/30 text-purple-200 space-y-2">
        <div class="flex items-center gap-2 font-semibold text-purple-300">
          <span class="w-2 h-2 rounded-full bg-purple-400"></span> Grownesia AI Bot
        </div>
        <p>Halo {{ Auth::user()->name ?? 'Budi' }}! Saya asisten belanja berbasis AI Anda. Ketik pertanyaan Anda atau
          pilih opsi cepat:</p>
        <div class="pt-1 flex flex-col gap-1.5">
          <button @click="sendAiQuery('Saya ingin hadiah untuk ibu umur 50 tahun.')"
            class="text-left p-2 rounded-xl bg-purple-950/60 hover:bg-purple-800/60 border border-purple-400/30 text-purple-200 text-xs transition flex items-center justify-between">
            <span>💡 "Saya ingin hadiah untuk ibu umur 50 tahun."</span>
            <span class="text-purple-400">→</span>
          </button>
          <button @click="sendAiQuery('Saya mau kopi khas nusantara terbaik.')"
            class="text-left p-2 rounded-xl bg-purple-950/60 hover:bg-purple-800/60 border border-purple-400/30 text-purple-200 text-xs transition flex items-center justify-between">
            <span>☕ "Kopi khas nusantara terbaik."</span>
            <span class="text-purple-400">→</span>
          </button>
        </div>
      </div>

      <template x-for="(msg, index) in chatMessages" :key="index">
        <div :class="msg.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
          <div
            :class="msg.sender === 'user' ? 'bg-purple-600 text-white rounded-2xl rounded-tr-none p-3.5 max-w-[85%]' : 'bg-purple-950/80 border border-purple-500/30 text-purple-100 rounded-2xl rounded-tl-none p-3.5 max-w-[90%] space-y-2'">
            <p x-text="msg.text"></p>

            <template x-if="msg.products && msg.products.length">
              <div class="mt-2 space-y-2 pt-2 border-t border-purple-500/20">
                <template x-for="prod in msg.products" :key="prod.id">
                  <div class="p-2.5 rounded-xl bg-purple-900/40 border border-purple-500/20 flex items-center gap-3">
                    <img :src="prod.image" loading="lazy"
                      class="w-12 h-12 rounded-lg object-cover border border-purple-400/30">
                    <div class="flex-1 min-w-0">
                      <h5 class="text-xs font-bold text-white truncate" x-text="prod.name"></h5>
                      <p class="text-[10px] text-purple-300 font-medium" x-text="prod.umkm"></p>
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

<!-- CART DRAWER -->
<div x-show="showCartDrawer" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
  class="fixed inset-0 z-50 overflow-hidden bg-purple-950/60 backdrop-blur-md flex justify-end" style="display: none;">

  <div @click.away="showCartDrawer = false"
    class="w-full max-w-md bg-purple-card border-l border-purple-500/30 h-full flex flex-col shadow-2xl">
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

    <div x-show="cart.length > 0" class="p-5 border-t border-purple-500/20 bg-purple-950/80 space-y-4">

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

<!-- CHECKOUT MODAL -->
<div x-show="showCheckoutModal"
  class="fixed inset-0 z-50 overflow-y-auto bg-purple-950/80 backdrop-blur-md flex items-center justify-center p-4"
  style="display: none;">
  <div @click.away="showCheckoutModal = false"
    class="w-full max-w-xl bg-purple-card border border-purple-500/30 rounded-2xl overflow-hidden shadow-2xl space-y-5 p-6">
    <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
      <h3 class="text-lg font-bold text-white font-heading">Checkout & Pembayaran</h3>
      <button @click="showCheckoutModal = false" class="text-purple-400 hover:text-white">✕</button>
    </div>

    <div class="space-y-4 text-xs">
      <div>
        <label class="block font-semibold text-purple-200 mb-1">Alamat Pengiriman</label>
        <textarea x-model="userProfile.address"
          class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-purple-100"
          rows="2"></textarea>
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

<!-- SUCCESS IMPACT CERTIFICATE MODAL -->
<div x-show="showSuccessModal"
  class="fixed inset-0 z-50 overflow-y-auto bg-purple-950/90 backdrop-blur-lg flex items-center justify-center p-4"
  style="display: none;">
  <div
    class="w-full max-w-lg bg-purple-card border border-purple-400/40 rounded-3xl p-6 text-center space-y-4 shadow-2xl relative overflow-hidden">
    <div
      class="w-16 h-16 rounded-full bg-emerald-500/20 border-2 border-emerald-400 text-emerald-400 flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
      </svg>
    </div>

    <h3 class="text-2xl font-extrabold text-white font-heading">Pembayaran Berhasil! 🎉</h3>
    <p class="text-xs text-purple-200/90">Terima kasih atas pesanan Anda. Transaksi Anda membantu menggerakkan roda
      ekonomi UMKM lokal Indonesia!</p>

    <div
      class="p-4 rounded-2xl bg-gradient-to-br from-purple-900/80 via-purple-950 to-indigo-950 border border-purple-400/40 text-left space-y-2">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-bold text-purple-400 uppercase tracking-widest">Rincian Transaksi</span>
        <span class="text-xs text-purple-300 font-semibold">Terverifikasi</span>
      </div>
      <div class="text-xs text-purple-200">Pesanan Anda telah diteruskan ke mitra seller UMKM dan sedang disiapkan untuk
        pengiriman.</div>
    </div>

    <button @click="showSuccessModal = false"
      class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition">
      Kembali ke Dashboard
    </button>
  </div>
</div>

<!-- PRODUCT DETAIL MODAL (PRD CUSTOMER REQUIREMENT) -->
<div x-show="showProductDetailModal && selectedProductDetail"
  class="fixed inset-0 z-50 overflow-y-auto bg-purple-950/85 backdrop-blur-md flex items-center justify-center p-4"
  style="display: none;">
  <div @click.away="showProductDetailModal = false"
    class="w-full max-w-2xl bg-purple-card border border-purple-500/30 rounded-3xl overflow-hidden shadow-2xl space-y-5 p-6">
    <template x-if="selectedProductDetail">
      <div class="space-y-5">
        <div class="flex items-center justify-between border-b border-purple-500/20 pb-3">
          <div class="flex items-center gap-2">
            <span
              class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-800/40 text-purple-300 border border-purple-400/30"
              x-text="selectedProductDetail.category"></span>
            <span class="text-xs text-emerald-400 font-semibold flex items-center gap-1">
              ⭐ <span x-text="selectedProductDetail.rating"></span> (48 ulasan)
            </span>
          </div>
          <button @click="showProductDetailModal = false" class="text-purple-400 hover:text-white">✕</button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-center">
          <div
            class="sm:col-span-5 relative h-56 rounded-2xl overflow-hidden bg-purple-950 border border-purple-400/30">
            <img :src="selectedProductDetail.image" loading="lazy" class="w-full h-full object-cover">
            <button @click="toggleFavorite(selectedProductDetail)"
              class="absolute top-3 right-3 p-2 rounded-full bg-purple-950/80 text-white border border-purple-400/30 hover:scale-110 transition">
              <svg class="w-5 h-5"
                :class="isFavorite(selectedProductDetail.id) ? 'text-red-500 fill-current' : 'text-purple-300'"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </button>
          </div>

          <div class="sm:col-span-7 space-y-3">
            <div class="text-xs font-bold text-purple-400 uppercase tracking-widest"
              x-text="'Penjual: ' + selectedProductDetail.umkm + ' (' + selectedProductDetail.region + ')'"></div>
            <h3 class="text-xl font-extrabold text-white font-heading" x-text="selectedProductDetail.name"></h3>
            <p class="text-xs text-purple-200/80 leading-relaxed" x-text="selectedProductDetail.description"></p>

            <div class="p-3 rounded-xl bg-purple-900/40 border border-purple-500/30 text-purple-200 text-xs">
              📍 <strong>Asal Produk:</strong> <span x-text="selectedProductDetail.region"></span> • Garansi Kualitas
              Original UMKM
            </div>

            <div class="flex items-baseline gap-2 pt-1">
              <span class="text-2xl font-extrabold text-white"
                x-text="formatRupiah(selectedProductDetail.price)"></span>
              <span class="text-xs text-purple-400/60 line-through"
                x-text="formatRupiah(selectedProductDetail.original_price)"></span>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3 pt-3 border-t border-purple-500/20">
          <button @click="addToCart(selectedProductDetail); showProductDetailModal = false"
            class="flex-1 py-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition flex items-center justify-center gap-2">
            + Tambah ke Keranjang
          </button>
          <button @click="addToCart(selectedProductDetail); showProductDetailModal = false; openCheckoutModal()"
            class="flex-1 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-xs transition">
            Beli Langsung Sekarang
          </button>
        </div>
      </div>
    </template>
  </div>
</div>

<!-- PROFILE EDIT MODAL -->
<div x-show="showProfileModal"
  class="fixed inset-0 z-50 overflow-y-auto bg-purple-950/85 backdrop-blur-md flex items-center justify-center p-4"
  style="display: none;">
  <div @click.away="showProfileModal = false"
    class="w-full max-w-lg bg-purple-card border border-purple-500/30 rounded-3xl p-6 space-y-4 shadow-2xl">
    <div class="flex items-center justify-between border-b border-purple-500/20 pb-3">
      <h3 class="text-base font-bold text-white font-heading">Pengaturan Profil & Alamat</h3>
      <button @click="showProfileModal = false" class="text-purple-400 hover:text-white">✕</button>
    </div>

    <form @submit.prevent="saveProfile()" class="space-y-3 text-xs">
      <div>
        <label class="block font-semibold text-purple-200 mb-1">Nama Lengkap</label>
        <input type="text" x-model="userProfile.name"
          class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white">
      </div>

      <div>
        <label class="block font-semibold text-purple-200 mb-1">Alamat Email</label>
        <input type="email" x-model="userProfile.email"
          class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white">
      </div>

      <div>
        <label class="block font-semibold text-purple-200 mb-1">Nomor Telepon / WhatsApp</label>
        <input type="text" x-model="userProfile.phone"
          class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white">
      </div>

      <div>
        <label class="block font-semibold text-purple-200 mb-1">Alamat Pengiriman Utama</label>
        <textarea x-model="userProfile.address"
          class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white" rows="2"></textarea>
      </div>

      <button type="submit"
        class="w-full py-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition shadow-lg">
        Simpan Perubahan Profil
      </button>
    </form>
  </div>
</div>

<!-- ROLE PREVIEW MODAL -->
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
        <span class="text-xs text-purple-300/70">Preview Modul Ekosistem</span>
      </div>
      <button @click="showRolePreviewModal = false" class="text-purple-400 hover:text-white">✕</button>
    </div>

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
      <span>⚡ <strong>Info:</strong> Fitur <strong>Portal Pembeli Grownesia</strong> saat ini aktif penuh.</span>
      <button @click="showRolePreviewModal = false"
        class="px-4 py-2 rounded-xl bg-purple-600 text-white font-bold">Mengerti</button>
    </div>
  </div>
</div>