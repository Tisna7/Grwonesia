<!-- DEDICATED PAGE 6: AI SHOPPING ASSISTANT -->
<div x-show="activeTab === 'ai-assistant'" class="space-y-6">
  <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
    <div>
      <h3 class="text-xl font-bold text-white font-heading">AI Shopping Assistant Interactive</h3>
      <p class="text-xs text-purple-300/70">Asisten kecerdasan buatan untuk rekomendasi produk dan kado personal.</p>
    </div>
  </div>

  <div
    class="p-6 rounded-3xl bg-purple-card border border-purple-500/30 shadow-2xl space-y-4 min-h-[480px] flex flex-col justify-between">
    <div class="space-y-4 overflow-y-auto max-h-[400px] text-xs pr-2" id="fullChatContainer">
      <div class="p-4 rounded-2xl bg-purple-900/50 border border-purple-500/30 text-purple-200 space-y-2">
        <div class="flex items-center gap-2 font-semibold text-purple-300">
          <span class="w-2.5 h-2.5 rounded-full bg-purple-400 animate-pulse"></span> Grownesia AI Bot
        </div>
        <p>Halo {{ explode(' ', Auth::user()->name ?? 'Kamu')[0] }}! Saya AI Personal Shopper Anda. Tanyakan kebutuhan Anda atau pilih
          kueri favorit berikut:</p>
        <div class="pt-2 flex flex-wrap gap-2">
          <button @click="sendAiQuery('Saya ingin hadiah untuk ibu umur 50 tahun.')"
            class="px-3 py-1.5 rounded-xl bg-purple-950/80 hover:bg-purple-800 border border-purple-400/30 text-purple-200 text-xs transition flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <span>Hadiah Ibu 50th</span>
          </button>
          <button @click="sendAiQuery('Saya punya budget Rp300.000 untuk hampers.')"
            class="px-3 py-1.5 rounded-xl bg-purple-950/80 hover:bg-purple-800 border border-purple-400/30 text-purple-200 text-xs transition flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <span>Hampers Budget 300rb</span>
          </button>
          <button @click="sendAiQuery('Kopi gula aren atau kopi klepon mana yang lebih disukai?')"
            class="px-3 py-1.5 rounded-xl bg-purple-950/80 hover:bg-purple-800 border border-purple-400/30 text-purple-200 text-xs transition flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0" />
            </svg>
            <span>Komparasi Kopi</span>
          </button>
        </div>
      </div>

      <template x-for="(msg, index) in chatMessages" :key="'fmsg-'+index">
        <div :class="msg.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
          <div
            :class="msg.sender === 'user' ? 'bg-purple-600 text-white rounded-2xl rounded-tr-none p-4 max-w-[80%]' : 'bg-purple-950/90 border border-purple-500/30 text-purple-100 rounded-2xl rounded-tl-none p-4 max-w-[85%] space-y-3'">
            <div x-html="formatAiText(msg.text)" class="leading-relaxed"></div>

            <template x-if="msg.products && msg.products.length">
              <div class="mt-2 space-y-2 pt-2 border-t border-purple-500/20">
                <template x-for="prod in msg.products" :key="'fprod-'+prod.id">
                  <div class="p-3 rounded-xl bg-purple-900/40 border border-purple-500/20 flex items-center gap-3">
                    <img :src="prod.image" loading="lazy"
                      class="w-14 h-14 rounded-lg object-cover border border-purple-400/30">
                    <div class="flex-1 min-w-0">
                      <h5 class="text-xs font-bold text-white truncate" x-text="prod.name"></h5>
                      <p class="text-[11px] text-emerald-400 font-medium" x-text="prod.impact_text"></p>
                      <p class="text-xs font-semibold text-purple-300" x-text="formatRupiah(prod.price)"></p>
                    </div>
                    <button @click="addToCart(prod)"
                      class="px-3 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-semibold shrink-0">
                      + Beli
                    </button>
                  </div>
                </template>
              </div>
            </template>
          </div>
        </div>
      </template>

      <!-- Animated Typing Indicator when AI is processing -->
      <template x-if="aiIsTyping">
        <div class="flex justify-start">
          <div
            class="bg-purple-950/90 border border-purple-500/30 text-purple-200 rounded-2xl rounded-tl-none p-4 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-purple-400 animate-bounce"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-fuchsia-400 animate-bounce" style="animation-delay: 0.15s"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-pink-400 animate-bounce" style="animation-delay: 0.3s"></span>
            <span class="text-xs text-purple-300 font-semibold ml-2">Grownesia AI sedang berpikir...</span>
          </div>
        </div>
      </template>
    </div>

    <form @submit.prevent="sendAiQuery(customChatInput)" class="flex gap-3 pt-2">
      <input type="text" x-model="customChatInput" placeholder="Tanyakan rekomendasi kado, kopi, atau fashion UMKM..."
        class="flex-1 px-4 py-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-xs text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">
      <button type="submit"
        class="px-6 py-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition">Kirim</button>
    </form>
  </div>
</div>