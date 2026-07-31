<!-- DEDICATED PAGE 12: ORDER TRACKING (CUSTOMER DASHBOARD) -->
<div x-show="activeTab === 'tracking'" class="space-y-6">

  <!-- HEADER SECTION -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-purple-500/20 pb-4">
    <div>
      <span class="text-[10px] font-bold text-purple-400 uppercase tracking-widest">Customer Portal — Live
        Logistics</span>
      <h3 class="text-xl font-bold text-white font-heading flex items-center gap-2">
        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Order Tracking (Pelacakan Pengiriman)</span>
      </h3>
      <p class="text-xs text-purple-300/70">Monitor progress pengiriman paket pesanan Anda secara real-time dengan
        dukungan prediksi AI.</p>
    </div>

    <!-- Order Picker Dropdown -->
    <template x-if="ordersHistory.length > 0">
      <div class="flex items-center gap-2 bg-purple-950/80 p-1.5 rounded-2xl border border-purple-500/30">
        <span class="text-[11px] font-bold text-purple-300 px-2">Pilih Pesanan:</span>
        <select @change="trackingOrder = ordersHistory[$event.target.value]"
          class="bg-purple-900 text-white text-xs font-bold rounded-xl px-3 py-1.5 border border-purple-400/30 focus:outline-none">
          <template x-for="(ord, idx) in ordersHistory" :key="'tr-sel-'+ord.id">
            <option :value="idx" :selected="trackingOrder && trackingOrder.id === ord.id" x-text="ord.id + ' — ' + ord.productName"></option>
          </template>
        </select>
      </div>
    </template>
  </div>

  <template x-if="trackingOrder">
    <div class="space-y-6">

      <!-- SUCCESS BANNER (IF DELIVERED) -->
      <template x-if="trackingOrder.shippingStatus === 'Delivered'">
        <div
          class="p-5 rounded-2xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-lg shadow-emerald-950/50">
          <div class="flex items-center gap-3">
            <div
              class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center shrink-0">
              <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div>
              <h4 class="text-sm font-bold text-white">Paket Telah Tiba dengan Selamat! 🎉</h4>
              <p class="text-xs text-emerald-300/80">Paket telah diterima pada <span
                  x-text="trackingOrder.lastUpdated"></span>. Terima kasih telah mendukung ekonomi lokal!</p>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <button @click="openWriteReview(trackingOrder)"
              class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition shadow-md">
              Tulis Ulasan
            </button>
            <button @click="addToCart(products[0])"
              class="px-4 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-purple-200 font-bold text-xs transition border border-purple-400/30">
              Beli Lagi
            </button>
          </div>
        </div>
      </template>

      <!-- LARGE PREMIUM TRACKING CARD -->
      <div
        class="p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl glow-purple relative overflow-hidden space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-purple-500/20 pb-5">
          <div class="flex items-center gap-4">
            <!-- Courier Badge / Logo -->
            <div
              class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-purple-900 to-fuchsia-800 border border-purple-400/40 flex items-center justify-center text-white font-black text-sm tracking-wider shadow-lg">
              <span x-text="trackingOrder.courierLogo || 'JNE'"></span>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <h4 class="text-lg font-bold text-white font-heading" x-text="trackingOrder.courier"></h4>
                <span
                  class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30"
                  x-text="trackingOrder.shippingStatus"></span>
              </div>
              <div class="flex items-center gap-2 mt-0.5">
                <span class="text-xs text-purple-300 font-mono" x-text="'Resi: ' + trackingOrder.trackingNumber"></span>
                <span class="text-[10px] text-purple-400/60">• Updated <span
                    x-text="trackingOrder.lastUpdated"></span></span>
              </div>
            </div>
          </div>

          <!-- Action Buttons: Track Shipment & Copy Tracking Number -->
          <div class="flex items-center gap-3">
            <button @click="refreshTracking()"
              class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold transition shadow-lg flex items-center gap-2">
              <svg class="w-4 h-4" :class="isRefreshingTracking ? 'animate-spin' : ''" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              <span>Lacak Ulang</span>
            </button>
            <button @click="copyTrackingNumber(trackingOrder.trackingNumber)"
              class="px-4 py-2.5 rounded-xl bg-purple-950 hover:bg-purple-900 border border-purple-500/40 text-purple-200 text-xs font-bold transition flex items-center gap-2">
              <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
              <span>Salin Resi</span>
            </button>
          </div>
        </div>

        <!-- Current Status Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
          <div class="p-4 rounded-2xl bg-purple-950/80 border border-purple-500/20 space-y-1">
            <span class="text-[10px] text-purple-400 uppercase font-bold">Status & Lokasi Saat Ini</span>
            <p class="font-bold text-white text-sm" x-text="trackingOrder.currentLocation"></p>
          </div>
          <div class="p-4 rounded-2xl bg-purple-950/80 border border-purple-500/20 space-y-1">
            <span class="text-[10px] text-purple-400 uppercase font-bold">Estimasi Tiba</span>
            <p class="font-bold text-emerald-400 text-sm" x-text="trackingOrder.estimatedArrival"></p>
          </div>
          <div class="p-4 rounded-2xl bg-purple-950/80 border border-purple-500/20 space-y-1">
            <span class="text-[10px] text-purple-400 uppercase font-bold">Alamat Tujuan</span>
            <p class="text-purple-200/90 truncate" x-text="trackingOrder.shippingAddress"></p>
          </div>
        </div>
      </div>

      <!-- AI DELIVERY INSIGHT CARD (PURPLE GLASSMORPHISM) -->
      <div
        class="p-6 rounded-3xl bg-gradient-to-r from-purple-900/80 via-fuchsia-900/50 to-purple-950/90 border border-fuchsia-400/40 shadow-2xl glow-purple relative overflow-hidden space-y-3">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div
              class="w-8 h-8 rounded-xl bg-fuchsia-500/20 border border-fuchsia-400/30 flex items-center justify-center text-fuchsia-300">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                  d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h4 class="text-sm font-bold text-white font-heading">AI Delivery Insight</h4>
          </div>
          <span
            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30"
            x-text="(trackingOrder.aiConfidence || '96%') + ' Confidence'"></span>
        </div>

        <p class="text-xs text-purple-100 leading-relaxed"
          x-text="trackingOrder.aiInsight || 'Pengiriman berjalan lancar sesuai estimasi.'"></p>

        <div class="pt-2 flex items-center gap-4 text-[11px] text-purple-300/80 border-t border-fuchsia-500/20">
          <span>💡 Rekomendasi: Pastikan seseorang berada di alamat tujuan untuk penerimaan paket.</span>
        </div>
      </div>

      <!-- VERTICAL DELIVERY TIMELINE COMPONENT -->
      <div class="p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-6">
        <h4 class="text-base font-bold text-white font-heading flex items-center gap-2">
          <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <span>Timeline Logistik Pengiriman (Vertical Timeline)</span>
        </h4>

        <!-- Vertical Timeline Steps -->
        <div class="relative pl-8 space-y-6 border-l-2 border-purple-500/30 ml-4">
          <template x-for="(step, idx) in trackingOrder.timeline" :key="'tl-step-'+idx">
            <div class="relative group">
              <!-- Status Node Icon -->
              <div
                class="absolute -left-[45px] top-0 w-8 h-8 rounded-full border-2 flex items-center justify-center transition"
                :class="step.done ? 'bg-gradient-to-r from-purple-600 to-fuchsia-600 border-fuchsia-300 text-white shadow-lg shadow-purple-900/50 scale-105' : 'bg-purple-950 border-purple-500/30 text-purple-500'">
                <template x-if="step.done">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                  </svg>
                </template>
                <template x-if="!step.done">
                  <span class="w-2.5 h-2.5 rounded-full bg-purple-500/50"></span>
                </template>
              </div>

              <div class="space-y-1">
                <div class="flex flex-wrap items-center gap-2">
                  <h5 class="text-xs font-bold" :class="step.done ? 'text-white' : 'text-purple-400/60'"
                    x-text="step.desc"></h5>
                  <span class="text-[10px] text-purple-400/70" x-text="step.time"></span>
                </div>
                <p class="text-[11px] text-purple-300/80" x-text="step.location"></p>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- ORDER DETAIL SUMMARY -->
      <div class="p-6 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-4">
        <h4 class="text-base font-bold text-white font-heading">Rincian Produk & Pembayaran</h4>

        <div class="flex items-center gap-4 p-3 rounded-2xl bg-purple-950/80 border border-purple-500/20">
          <img :src="trackingOrder.productImage" loading="lazy"
            class="w-16 h-16 rounded-xl object-cover border border-purple-400/30">
          <div class="space-y-1">
            <span class="text-[10px] font-bold text-purple-400 uppercase" x-text="trackingOrder.businessName"></span>
            <h5 class="text-xs font-bold text-white" x-text="trackingOrder.productName"></h5>
            <span class="text-[11px] text-purple-300 font-semibold" x-text="trackingOrder.items"></span>
          </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs pt-2 border-t border-purple-500/20">
          <div>
            <span class="text-[10px] text-purple-400 block">Metode Bayar:</span>
            <span class="font-bold text-white" x-text="trackingOrder.paymentMethod || 'Midtrans Snap Gateway'"></span>
          </div>
          <div>
            <span class="text-[10px] text-purple-400 block">Ongkos Kirim (Biteship):</span>
            <span class="font-bold text-purple-300" x-text="trackingOrder.shippingCost ? formatRupiah(trackingOrder.shippingCost) : 'Rp 12.000'"></span>
          </div>
          <div>
            <span class="text-[10px] text-purple-400 block">Total Transaksi:</span>
            <span class="font-bold text-emerald-400" x-text="formatRupiah(trackingOrder.total)"></span>
          </div>
          <div>
            <span class="text-[10px] text-purple-400 block">No. Transaksi:</span>
            <span class="font-mono text-purple-300 font-bold" x-text="trackingOrder.id"></span>
          </div>
        </div>
      </div>

    </div>
  </template>

  <!-- EMPTY STATE (IF NO SHIPMENT IS SELECTED OR FOUND) -->
  <template x-if="!trackingOrder || ordersHistory.length === 0">
    <div class="text-center py-20 p-8 rounded-3xl bg-purple-card border border-purple-500/20 space-y-4">
      <div
        class="w-20 h-20 rounded-3xl bg-purple-950 border border-purple-500/30 flex items-center justify-center mx-auto text-purple-400 shadow-xl">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
      </div>
      <div class="space-y-1">
        <h4 class="text-lg font-bold text-white font-heading">No shipment available.</h4>
        <p class="text-xs text-purple-300/70 max-w-md mx-auto">Anda belum memiliki transaksi pengiriman yang aktif.
          Silakan pilih produk dari katalog UMKM dan selesaikan pesanan Anda.</p>
      </div>
      <button @click="activeTab = 'katalog'"
        class="px-6 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-xs transition shadow-lg">
        Continue Shopping (Lanjutkan Belanja)
      </button>
    </div>
  </template>

</div>