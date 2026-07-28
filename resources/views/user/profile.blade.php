<!-- DEDICATED PAGE 9: PENGATURAN PROFIL -->
<div x-show="activeTab === 'profile'" class="space-y-6">
  <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
    <div>
      <h3 class="text-xl font-bold text-white font-heading">Pengaturan Profil & Alamat Pengiriman</h3>
      <p class="text-xs text-purple-300/70">Kelola informasi data diri Anda di ekosistem Grownesia.</p>
    </div>
  </div>

  <div class="max-w-xl p-6 rounded-3xl bg-purple-card border border-purple-500/30 space-y-4 shadow-xl">
    <form @submit.prevent="saveProfile()" class="space-y-4 text-xs">
      <div>
        <label class="block font-semibold text-purple-200 mb-1">Nama Lengkap</label>
        <input type="text" x-model="userProfile.name"
          class="w-full p-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white">
      </div>

      <div>
        <label class="block font-semibold text-purple-200 mb-1">Alamat Email</label>
        <input type="email" x-model="userProfile.email"
          class="w-full p-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white">
      </div>

      <div>
        <label class="block font-semibold text-purple-200 mb-1">Nomor WhatsApp</label>
        <input type="text" x-model="userProfile.phone"
          class="w-full p-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white">
      </div>

      <div>
        <label class="block font-semibold text-purple-200 mb-1">Alamat Pengiriman Utama</label>
        <textarea x-model="userProfile.address"
          class="w-full p-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white" rows="3"></textarea>
      </div>

      <button type="submit"
        class="w-full py-3.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition shadow-lg">
        Simpan Perubahan Profil
      </button>
    </form>
  </div>
</div>