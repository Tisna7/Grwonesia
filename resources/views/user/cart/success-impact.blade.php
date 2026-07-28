<!-- DEDICATED PAGE 4: PASCA TRANSAKSI -->
<div x-show="activeTab === 'success-impact'" class="space-y-6">
  <div
    class="max-w-2xl mx-auto p-8 rounded-3xl bg-purple-card border border-purple-400/40 text-center space-y-5 shadow-2xl relative overflow-hidden">
    <div
      class="w-20 h-20 rounded-full bg-emerald-500/20 border-2 border-emerald-400 text-emerald-400 flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
      <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
      </svg>
    </div>

    <h2 class="text-2xl font-extrabold text-white font-heading">Pembayaran Berhasil!</h2>
    <p class="text-xs text-purple-200/90 max-w-md mx-auto">Terima kasih atas pesanan Anda. Transaksi Anda membantu
      menggerakkan roda ekonomi UMKM lokal Indonesia secara langsung!</p>

    <div
      class="p-6 rounded-2xl bg-gradient-to-br from-purple-900/90 via-purple-950 to-indigo-950 border border-purple-400/40 text-left space-y-3 shadow-xl">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold text-purple-400 uppercase tracking-widest">Sertifikat Dampak Sosial (Verified
          Impact)</span>
        <span
          class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-semibold border border-emerald-400/30">Verified</span>
      </div>
      <div class="text-lg font-bold text-white" x-text="lastOrderImpactSummary"></div>
      <p class="text-xs text-purple-300/80 leading-relaxed">Pendapatan dari transaksi ini diteruskan ke UMKM mitra
        Grownesia di Solo, Palembang, dan Kebumen untuk mendukung upah layak bagi para pengrajin wanita dan petani
        lokal.</p>
    </div>

    <div class="flex justify-center gap-4 pt-2">
      <button @click="activeTab = 'orders'"
        class="px-6 py-3 rounded-xl bg-purple-950 border border-purple-500/30 hover:bg-purple-900 text-purple-200 font-bold text-xs">
        Lihat Riwayat & Beri Ulasan
      </button>
      <button @click="activeTab = 'katalog'"
        class="px-6 py-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs">
        Kembali ke Katalog
      </button>
    </div>
  </div>
</div>