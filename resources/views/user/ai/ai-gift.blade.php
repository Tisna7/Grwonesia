<!-- DEDICATED PAGE 10: AI GIFT RECOMMENDATION -->
<div x-show="activeTab === 'ai-gift'"
  class="p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-6">
  <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
    <div class="space-y-0.5">
      <span class="text-xs font-extrabold uppercase tracking-widest text-fuchsia-400">Racikan AI Kustom</span>
      <h3 class="text-xl font-bold text-white font-heading">AI Gift & Custom Package Creator</h3>
      <p class="text-xs text-purple-300/70">Atur anggaran dan ketik penerima/tujuan kado apa saja secara bebas!</p>
    </div>

    <div class="flex bg-purple-950 p-1 rounded-xl border border-purple-500/30 text-xs font-semibold">
      <button @click="giftRecipientMode = 'preset'; generateGiftBundle()"
        :class="giftRecipientMode === 'preset' ? 'bg-purple-600 text-white' : 'text-purple-300 hover:text-white'"
        class="px-3 py-1.5 rounded-lg transition">
        Pilihan Preset
      </button>
      <button @click="giftRecipientMode = 'custom'; generateGiftBundle()"
        :class="giftRecipientMode === 'custom' ? 'bg-purple-600 text-white' : 'text-purple-300 hover:text-white'"
        class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
        </svg>
        <span>Input Custom</span>
        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
      </button>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
    <div class="md:col-span-5 space-y-5 bg-purple-950/60 p-5 rounded-2xl border border-purple-500/20">
      <div class="space-y-2">
        <label class="block text-xs font-bold text-purple-200">
          Anggaran Belanja: <span class="text-purple-300 text-base font-extrabold"
            x-text="formatRupiah(giftBudget)"></span>
        </label>
        <input type="range" min="50000" max="600000" step="25000" x-model="giftBudget" @change="generateGiftBundle()"
          class="w-full accent-purple-500 cursor-pointer">
      </div>

      <div x-show="giftRecipientMode === 'preset'" class="space-y-2">
        <label class="block text-xs font-bold text-purple-200">Target Penerima (Preset):</label>
        <select x-model="giftRecipient" @change="generateGiftBundle()"
          class="w-full p-3 rounded-xl bg-purple-900/80 border border-purple-500/30 text-xs text-white">
          <option value="Hadiah untuk Ibu / Orang Tua (Usia 50th)">Hadiah untuk Ibu / Orang Tua</option>
          <option value="Hadiah Ulang Tahun untuk Sahabat">Hadiah Ulang Tahun untuk Sahabat</option>
          <option value="Hampers Formal untuk Rekan Kerja & Dosen">Hampers Rekan Kerja / Dosen</option>
          <option value="Souvenir Penggemar Kopi Nusantara">Souvenir Penggemar Kopi</option>
        </select>
      </div>

      <div x-show="giftRecipientMode === 'custom'" class="space-y-3">
        <label class="block text-xs font-bold text-purple-200">Ketik Kebutuhan Hadiah / Penerima Kustom:</label>
        <input type="text" x-model="customGiftRecipientInput" @input.debounce.300ms="generateGiftBundle()"
          placeholder="Contoh: Hadiah dosen pembimbing 45th..."
          class="w-full p-3 rounded-xl bg-purple-900/80 border border-purple-400/40 text-xs text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">

        <div class="space-y-1.5 pt-1">
          <span class="text-[10px] text-purple-400 font-semibold uppercase tracking-wider">Coba Kueri Cepat:</span>
          <div class="flex flex-wrap gap-1.5 text-[11px]">
            <button
              @click="giftRecipientMode = 'custom'; customGiftRecipientInput = 'Hadiah ulang tahun untuk dosen pembimbing 45th'; generateGiftBundle()"
              class="px-2.5 py-1 rounded-lg bg-purple-900/60 hover:bg-purple-800 text-purple-200 border border-purple-500/30 transition flex items-center gap-1">
              <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
              </svg>
              <span>Dosen Pembimbing</span>
            </button>
            <button
              @click="giftRecipientMode = 'custom'; customGiftRecipientInput = 'Hampers wisuda batik & kopi untuk sahabat'; generateGiftBundle()"
              class="px-2.5 py-1 rounded-lg bg-purple-900/60 hover:bg-purple-800 text-purple-200 border border-purple-500/30 transition flex items-center gap-1">
              <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
              <span>Hampers Wisuda</span>
            </button>
            <button
              @click="giftRecipientMode = 'custom'; customGiftRecipientInput = 'Kado syukuran rumah baru produk eco-friendly'; generateGiftBundle()"
              class="px-2.5 py-1 rounded-lg bg-purple-900/60 hover:bg-purple-800 text-purple-200 border border-purple-500/30 transition flex items-center gap-1">
              <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              <span>Syukuran Rumah</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="md:col-span-7">
      <div x-show="generatedBundle"
        class="p-6 rounded-2xl bg-purple-950/90 border border-purple-400/40 space-y-4 shadow-xl">
        <div class="flex items-center justify-between border-b border-purple-500/20 pb-3">
          <div class="space-y-0.5">
            <span class="text-[10px] font-bold text-purple-400 uppercase tracking-widest">Racikan AI Terkomposisi</span>
            <h4 class="text-sm font-bold text-white" x-text="'Kriteria: ' + generatedBundle.recipientLabel"></h4>
          </div>
          <div class="text-right">
            <span class="text-xs text-purple-300/70">Total Estimasi:</span>
            <div class="text-lg font-extrabold text-purple-300" x-text="formatRupiah(generatedBundle.totalPrice)">
            </div>
          </div>
        </div>

        <div class="p-3 rounded-xl bg-purple-900/50 border border-purple-500/30 text-xs text-purple-200 space-y-1">
          <div class="flex items-center gap-1.5 font-bold text-purple-300">
            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <span>Analisis Rekomendasi AI:</span>
          </div>
          <p class="text-[11px] text-purple-200/90" x-text="generatedBundle.aiReasoning"></p>
        </div>

        <div class="space-y-2">
          <span class="text-xs font-semibold text-purple-300">Produk yang Termasuk dalam Paket ini:</span>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <template x-for="item in generatedBundle.items" :key="'gbund2-'+item.id">
              <div class="p-3 rounded-xl bg-purple-900/40 border border-purple-500/20 flex items-center gap-3">
                <img :src="item.image" loading="lazy"
                  class="w-12 h-12 rounded-lg object-cover border border-purple-400/30 shrink-0">
                <div class="min-w-0">
                  <h5 class="text-xs font-bold text-white truncate" x-text="item.name"></h5>
                  <p class="text-[10px] text-purple-300/70" x-text="item.umkm"></p>
                  <span class="text-xs font-bold text-purple-200" x-text="formatRupiah(item.price)"></span>
                </div>
              </div>
            </template>
          </div>
        </div>

        <div
          class="p-3 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-xs flex items-center gap-2">
          <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
          </svg>
          <span>Social Impact: <strong x-text="generatedBundle.impact"></strong></span>
        </div>

        <button @click="addBundleToCart()"
          class="w-full py-3.5 rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-600 hover:from-fuchsia-500 hover:to-purple-500 text-white font-bold text-xs transition shadow-lg flex items-center justify-center gap-2">
          <span>+ Tambah Semua Paket Ini ke Keranjang & Checkout</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</div>