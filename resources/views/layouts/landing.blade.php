<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Grownesia - Ekosistem Pemberdayaan UMKM Indonesia Berbasis Kecerdasan Buatan (AI)">
    <title>Grownesia - Platform Ekosistem UMKM Indonesia Berbasis AI</title>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-purple-dark text-slate-100 min-h-screen font-sans selection:bg-purple-500 selection:text-white"
      x-data="{ showLoginModal: false, showRegisterModal: false, activeAuthTab: 'login' }">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-purple-900 via-indigo-900 to-purple-900 border-b border-purple-500/20 py-2 px-4 text-xs font-medium text-center">
        <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-purple-500/20 text-purple-300 border border-purple-400/30">NEW</span>
            <span class="text-purple-200">Grownesia AI Shopping Assistant & Impact Score 2.0 Kini Telah Dirilis!</span>
        </div>
    </div>

    <!-- PUBLIC NAVIGATION BAR (NAVBAR LAYOUT FOR LANDING PAGE) -->
    <header class="sticky top-0 z-40 bg-purple-dark/85 backdrop-blur-xl border-b border-purple-500/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-purple-700 via-purple-600 to-fuchsia-500 p-0.5 shadow-lg shadow-purple-500/30">
                        <div class="w-full h-full bg-purple-950 rounded-[10px] flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <a href="/" class="text-2xl font-extrabold tracking-tight font-heading text-white flex items-center gap-1.5">
                            Grownesia
                            <span class="text-xs px-2 py-0.5 rounded-full bg-purple-500/20 text-purple-300 border border-purple-400/30 font-sans font-normal">AI & Impact</span>
                        </a>
                        <p class="text-[11px] text-purple-300/70 -mt-1 font-medium">Pemberdayaan UMKM Indonesia</p>
                    </div>
                </div>

                <!-- Navbar Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-purple-200">
                    <a href="#beranda" class="hover:text-white transition">Beranda</a>
                    <a href="#fitur" class="hover:text-white transition">Fitur AI</a>
                    <a href="#dampak" class="hover:text-white transition">Dampak Sosial</a>
                    <a href="#ekosistem" class="hover:text-white transition">Ekosistem Role</a>
                </nav>

                <!-- Auth CTA Buttons (Masuk & Daftar) -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-purple-950/80 hover:bg-purple-900 border border-purple-500/30 text-purple-200 font-bold text-xs transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-xs transition shadow-lg shadow-purple-900/50">
                        Daftar Akun
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-purple-500/20 bg-purple-950/40 text-purple-300/70 py-12 text-sm mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-3 md:col-span-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-white font-bold text-lg font-heading">G</div>
                    <span class="text-xl font-extrabold text-white font-heading">Grownesia</span>
                </div>
                <p class="text-xs text-purple-300/80 max-w-md leading-relaxed">
                    Platform ekosistem pemberdayaan UMKM Indonesia berbasis AI. Menghubungkan pembeli, pelaku bisnis UMKM, pemerintah daerah, dan pengelola platform dalam satu jaringan ekonomi digital berdampak tinggi.
                </p>
                <p class="text-[11px] text-purple-400/60 pt-2">© 2026 Grownesia Platform. All Rights Reserved.</p>
            </div>
            
            <div>
                <h4 class="text-white font-semibold mb-3 text-xs uppercase tracking-wider">Akses Masuk Platform</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" @click.prevent="showLoginModal = true; activeAuthTab = 'login'" class="hover:text-purple-300">Login Role User (Pembeli)</a></li>
                    <li><a href="#" @click.prevent="showLoginModal = true; activeAuthTab = 'login'" class="hover:text-purple-300">Login Business Account (UMKM)</a></li>
                    <li><a href="#" @click.prevent="showLoginModal = true; activeAuthTab = 'login'" class="hover:text-purple-300">Login Government Dashboard</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-3 text-xs uppercase tracking-wider">Informasi</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#beranda" class="hover:text-purple-300">Tentang Grownesia</a></li>
                    <li><a href="#dampak" class="hover:text-purple-300">Metodologi Impact Score</a></li>
                    <li><a href="#fitur" class="hover:text-purple-300">Kecerdasan Buatan (AI)</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- LOGIN MODAL -->
    <div x-show="showLoginModal"
         class="fixed inset-0 z-50 overflow-y-auto bg-purple-950/80 backdrop-blur-md flex items-center justify-center p-4"
         style="display: none;">
        <div @click.away="showLoginModal = false" class="w-full max-w-md bg-purple-card border border-purple-500/40 rounded-3xl p-6 sm:p-8 space-y-5 shadow-2xl relative overflow-hidden">
            <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-white font-bold font-heading">G</div>
                    <h3 class="text-lg font-bold text-white font-heading">Masuk ke Grownesia</h3>
                </div>
                <button @click="showLoginModal = false" class="text-purple-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-purple-200 mb-1">Alamat Email</label>
                    <input type="email" name="email" value="budi@grownesia.id" required placeholder="nama@email.com" class="w-full px-3.5 py-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">
                </div>

                <div>
                    <label class="block font-semibold text-purple-200 mb-1">Kata Sandi</label>
                    <input type="password" name="password" value="password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">
                </div>

                <div class="p-3 rounded-xl bg-purple-900/40 border border-purple-500/20 text-[11px] text-purple-300/80 flex items-center justify-between">
                    <span>💡 <strong>Demo User:</strong> Langsung klik masuk untuk mencoba!</span>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-sm transition shadow-lg">
                    Masuk Sekarang →
                </button>
            </form>

            <div class="text-center text-xs text-purple-300/70 pt-2 border-t border-purple-500/20">
                Belum punya akun? <button @click="showLoginModal = false; showRegisterModal = true" class="text-purple-300 font-bold hover:underline">Daftar Akun Baru</button>
            </div>
        </div>
    </div>

    <!-- REGISTER MODAL -->
    <div x-show="showRegisterModal"
         class="fixed inset-0 z-50 overflow-y-auto bg-purple-950/80 backdrop-blur-md flex items-center justify-center p-4"
         style="display: none;">
        <div @click.away="showRegisterModal = false" class="w-full max-w-md bg-purple-card border border-purple-500/40 rounded-3xl p-6 sm:p-8 space-y-5 shadow-2xl relative overflow-hidden">
            <div class="flex items-center justify-between border-b border-purple-500/20 pb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-white font-bold font-heading">G</div>
                    <h3 class="text-lg font-bold text-white font-heading">Daftar Akun Grownesia</h3>
                </div>
                <button @click="showRegisterModal = false" class="text-purple-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-purple-200 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Budi Santoso" class="w-full px-3.5 py-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">
                </div>

                <div>
                    <label class="block font-semibold text-purple-200 mb-1">Alamat Email</label>
                    <input type="email" name="email" required placeholder="budi@grownesia.id" class="w-full px-3.5 py-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">
                </div>

                <div>
                    <label class="block font-semibold text-purple-200 mb-1">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white placeholder-purple-400/60 focus:outline-none focus:border-purple-400">
                </div>

                <div>
                    <label class="block font-semibold text-purple-200 mb-1">Daftar Sebagai:</label>
                    <select name="role" class="w-full px-3.5 py-2.5 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white">
                        <option value="user">Pembeli / User Impact (Fitur Aktif)</option>
                        <option value="business">Business Account (UMKM Mitra)</option>
                        <option value="government">Government Account</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-sm transition shadow-lg">
                    Buat Akun Sekarang →
                </button>
            </form>

            <div class="text-center text-xs text-purple-300/70 pt-2 border-t border-purple-500/20">
                Sudah punya akun? <button @click="showRegisterModal = false; showLoginModal = true" class="text-purple-300 font-bold hover:underline">Masuk</button>
            </div>
        </div>
    </div>

</body>
</html>
