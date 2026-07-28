<!-- DEDICATED PAGE 8B: FORM TULIS ULASAN PRODUK -->
<div x-show="activeTab === 'write-review' && reviewingOrder" class="space-y-6">
  <button @click="activeTab = 'orders'"
    class="inline-flex items-center gap-2 text-xs text-purple-300 hover:text-white transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <span>Kembali ke Riwayat Pesanan</span>
  </button>

  <template x-if="reviewingOrder">
    <div class="max-w-xl p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-6">
      <div class="space-y-1 border-b border-purple-500/20 pb-4">
        <span class="text-xs font-bold text-purple-400 uppercase tracking-widest"
          x-text="'Pesanan: ' + reviewingOrder.id"></span>
        <h3 class="text-xl font-bold text-white font-heading">Berikan Ulasan Produk & Pengalaman Belanja</h3>
        <p class="text-xs text-purple-300/70" x-text="reviewingOrder.items"></p>
      </div>

      <form @submit.prevent="submitProductReview()" class="space-y-5">
        <!-- Rating Star Selection -->
        <div class="space-y-2">
          <label class="block text-xs font-bold text-purple-200">Pilih Rating Bintang:</label>
          <div class="flex items-center gap-2">
            <template x-for="star in [1, 2, 3, 4, 5]" :key="'wstar-'+star">
              <button type="button" @click="newReviewForm.rating = star"
                class="transition hover:scale-125 focus:outline-none"
                :class="star <= newReviewForm.rating ? 'text-amber-400' : 'text-purple-950 hover:text-amber-300'">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </button>
            </template>
            <span class="text-xs font-bold text-purple-300 ml-2"
              x-text="newReviewForm.rating + ' dari 5 Bintang'"></span>
          </div>
        </div>

        <!-- Review Text Field -->
        <div class="space-y-2">
          <label class="block text-xs font-bold text-purple-200">Tulis Ulasan Anda:</label>
          <textarea x-model="newReviewForm.comment" rows="4"
            placeholder="Ceritakan kualitas produk, rasa, aroma, kain, atau dampak sosial yang Anda rasakan..."
            class="w-full p-4 rounded-2xl bg-purple-950/80 border border-purple-500/30 text-xs text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400 leading-relaxed"></textarea>
        </div>

        <button type="submit"
          class="w-full py-3.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs transition shadow-lg flex items-center justify-center gap-2">
          <svg class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 20 20">
            <path
              d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <span>Publikasikan Ulasan Saya</span>
        </button>
      </form>
    </div>
  </template>
</div>