<!-- DEDICATED PAGE 8: RIWAYAT PESANAN & ULASAN PRODUK -->
<div x-show="activeTab === 'orders'" class="space-y-6">
  <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
    <div>
      <h3 class="text-xl font-bold text-white font-heading">Riwayat Pesanan & Beri Ulasan</h3>
      <p class="text-xs text-purple-300/70">Daftar transaksi dan kesempatan memberikan ulasan untuk produk yang telah
        selesai dibeli.</p>
    </div>
    <span
      class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30"
      x-text="ordersHistory.length + ' Pesanan'"></span>
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

            <div class="flex flex-wrap items-center gap-2">
              <button @click="openTracking(order)"
                class="px-3.5 py-2 rounded-xl bg-purple-900/60 hover:bg-purple-800 border border-purple-400/30 text-purple-200 font-bold text-xs transition flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Lacak Pengiriman</span>
              </button>

              <!-- Ulasan Button Trigger -->
              <template x-if="order.status === 'Selesai'">
                <div>
                  <template x-if="!order.reviewed">
                    <button @click="openWriteReview(order)"
                      class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-xs transition shadow-md flex items-center gap-1.5">
                      <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <span>Tulis Ulasan Produk</span>
                    </button>
                  </template>
                  <template x-if="order.reviewed">
                    <span
                      class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-950 text-purple-300 border border-purple-500/30 text-[11px] font-semibold">
                      <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                      </svg>
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