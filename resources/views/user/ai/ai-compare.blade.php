<!-- DEDICATED PAGE 11: AI PRODUCT COMPARISON -->
<div x-show="activeTab === 'ai-compare'"
  class="p-6 sm:p-8 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-6">
  <div class="space-y-1">
    <span class="text-xs font-extrabold uppercase tracking-widest text-purple-400">Komparator Produk AI</span>
    <h3 class="text-xl font-bold text-white font-heading">AI Product Comparison</h3>
    <p class="text-xs text-purple-300/70">Bandingkan dua produk UMKM secara berdampingan untuk keputusan belanja
      tepat.</p>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
      <label class="block text-xs font-bold text-purple-200 mb-1">Produk A:</label>
      <select x-model="compareProduct1Id" @change="generateAiComparison()"
        class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-xs text-white">
        <template x-for="p in products" :key="'cmp1-'+p.id">
          <option :value="p.id" x-text="p.name + ' (' + formatRupiah(p.price) + ')'"></option>
        </template>
      </select>
    </div>

    <div>
      <label class="block text-xs font-bold text-purple-200 mb-1">Produk B:</label>
      <select x-model="compareProduct2Id" @change="generateAiComparison()"
        class="w-full p-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-xs text-white">
        <template x-for="p in products" :key="'cmp2-'+p.id">
          <option :value="p.id" x-text="p.name + ' (' + formatRupiah(p.price) + ')'"></option>
        </template>
      </select>
    </div>
  </div>

  <template x-if="compareProduct1 && compareProduct2">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
      <div class="p-4 rounded-2xl bg-purple-950/80 border border-purple-500/30 space-y-2 text-xs">
        <span class="font-bold text-purple-300">Produk A: <span x-text="compareProduct1.name"></span></span>
        <div class="text-emerald-400 font-semibold" x-text="compareProduct1.impact_text"></div>
        <div class="text-white font-bold" x-text="formatRupiah(compareProduct1.price)"></div>
      </div>

      <div class="p-4 rounded-2xl bg-purple-950/80 border border-purple-500/30 space-y-2 text-xs">
        <span class="font-bold text-fuchsia-300">Produk B: <span x-text="compareProduct2.name"></span></span>
        <div class="text-emerald-400 font-semibold" x-text="compareProduct2.impact_text"></div>
        <div class="text-white font-bold" x-text="formatRupiah(compareProduct2.price)"></div>
      </div>
    </div>
  </template>

  <template x-if="aiCompareAnalysis">
    <div class="p-4 rounded-2xl bg-purple-900/60 border border-purple-400/40 text-xs space-y-2">
      <div class="font-bold text-purple-200 flex items-center gap-1.5">
        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        <span>Analisis AI:</span>
      </div>
      <p class="text-purple-200/90 leading-relaxed" x-text="aiCompareAnalysis.verdict"></p>
      <template x-if="aiCompareAnalysis.recommendation">
        <div class="pt-2 border-t border-purple-500/20 text-fuchsia-300 font-semibold flex items-center gap-1.5">
          <svg class="w-3.5 h-3.5 text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Rekomendasi: <span class="text-white font-normal"
              x-text="aiCompareAnalysis.recommendation"></span></span>
        </div>
      </template>
    </div>
  </template>
</div>