<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Grownesia - Ekosistem Pemberdayaan UMKM Indonesia">
    <title>Grownesia - Platform Ekosistem UMKM Indonesia</title>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0f172a] text-slate-100 min-h-screen font-sans selection:bg-indigo-500 selection:text-white"
      x-data="{ showLoginModal: false, showRegisterModal: false, activeAuthTab: 'login' }">

    <!-- Top Announcement Bar -->
    <div class="bg-indigo-950 border-b border-indigo-500/20 py-2.5 px-4 text-xs font-medium text-center">
        <div class="max-w-7xl mx-auto flex items-center justify-center gap-3">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Pembaruan</span>
            <span class="text-indigo-100">Grownesia AI & Impact Score 2.0 Telah Tersedia</span>
        </div>
    </div>

    <!-- PUBLIC NAVIGATION BAR -->
    <header class="sticky top-0 z-40 bg-slate-900/90 backdrop-blur-md border-b border-slate-700/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <a href="/" class="text-xl font-bold tracking-tight text-white flex items-center gap-2">
                            Grownesia
                        </a>
                        <p class="text-[10px] text-slate-400 font-medium">Pemberdayaan UMKM</p>
                    </div>
                </div>

                <!-- Navbar Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
                    <a href="#beranda" class="hover:text-white transition">Beranda</a>
                    <a href="#fitur" class="hover:text-white transition">Fitur Platform</a>
                    <a href="#dampak" class="hover:text-white transition">Dampak Sosial</a>
                    <a href="#ekosistem" class="hover:text-white transition">Ekosistem</a>
                </nav>

                <!-- Auth CTA Buttons -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200 font-medium text-sm transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm transition shadow-sm">
                        Daftar
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
    <footer class="border-t border-slate-800 bg-slate-900 text-slate-400 py-12 text-sm mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-4 md:col-span-2">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-md bg-slate-700 flex items-center justify-center text-white font-bold text-sm">G</div>
                    <span class="text-lg font-bold text-white">Grownesia</span>
                </div>
                <p class="text-sm text-slate-400 max-w-md leading-relaxed">
                    Platform ekosistem pemberdayaan UMKM Indonesia. Menghubungkan pembeli, pelaku bisnis, dan pemerintah daerah dalam satu jaringan ekonomi digital.
                </p>
                <p class="text-xs text-slate-500 pt-2">© {{ date('Y') }} Grownesia. Hak Cipta Dilindungi.</p>
            </div>
            
            <div>
                <h4 class="text-slate-200 font-medium mb-4 text-sm">Akses Platform</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" @click.prevent="showLoginModal = true; activeAuthTab = 'login'" class="hover:text-indigo-400 transition">Pembeli</a></li>
                    <li><a href="#" @click.prevent="showLoginModal = true; activeAuthTab = 'login'" class="hover:text-indigo-400 transition">UMKM Mitra</a></li>
                    <li><a href="#" @click.prevent="showLoginModal = true; activeAuthTab = 'login'" class="hover:text-indigo-400 transition">Pemerintah Daerah</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-slate-200 font-medium mb-4 text-sm">Informasi</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#beranda" class="hover:text-indigo-400 transition">Tentang Kami</a></li>
                    <li><a href="#dampak" class="hover:text-indigo-400 transition">Metodologi</a></li>
                    <li><a href="#fitur" class="hover:text-indigo-400 transition">Bantuan</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- LOGIN MODAL -->
    <div x-show="showLoginModal" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="showLoginModal = false" class="w-full max-w-md bg-slate-900 border border-slate-700 rounded-2xl p-6 sm:p-8 space-y-6 shadow-xl relative">
            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <h3 class="text-xl font-semibold text-white">Masuk ke Akun Anda</h3>
                <button @click="showLoginModal = false" class="text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-medium text-slate-300 mb-1.5 text-sm">Alamat Email</label>
                    <input type="email" name="email" value="budi@grownesia.id" required class="w-full px-4 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-sm">
                </div>

                <div>
                    <label class="block font-medium text-slate-300 mb-1.5 text-sm">Kata Sandi</label>
                    <input type="password" name="password" value="password" required class="w-full px-4 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-sm">
                </div>

                <div class="p-3 rounded-lg bg-slate-800/50 border border-slate-700 text-xs text-slate-400">
                    Gunakan kredensial default untuk mencoba fitur platform.
                </div>

                <button type="submit" class="w-full py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm transition">
                    Masuk
                </button>
            </form>

            <div class="text-center text-sm text-slate-400 pt-4 border-t border-slate-800">
                Belum punya akun? <button @click="showLoginModal = false; showRegisterModal = true" class="text-indigo-400 font-medium hover:text-indigo-300 transition">Daftar sekarang</button>
            </div>
        </div>
    </div>

    <!-- REGISTER MODAL -->
    <div x-show="showRegisterModal" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="showRegisterModal = false" class="w-full max-w-md bg-slate-900 border border-slate-700 rounded-2xl p-6 sm:p-8 space-y-6 shadow-xl relative">
            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <h3 class="text-xl font-semibold text-white">Buat Akun Baru</h3>
                <button @click="showRegisterModal = false" class="text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4 text-sm">
                @csrf
                <div>
                    <label class="block font-medium text-slate-300 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div>
                    <label class="block font-medium text-slate-300 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div>
                    <label class="block font-medium text-slate-300 mb-1.5">Kata Sandi</label>
                    <input type="password" name="password" required class="w-full px-4 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div>
                    <label class="block font-medium text-slate-300 mb-1.5">Tipe Akun</label>
                    <select name="role" class="w-full px-4 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition appearance-none">
                        <option value="user">Pembeli</option>
                        <option value="business">UMKM Mitra</option>
                        <option value="government">Pemerintah Daerah</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-medium mt-2 transition">
                    Daftar
                </button>
            </form>

            <div class="text-center text-sm text-slate-400 pt-4 border-t border-slate-800">
                Sudah punya akun? <button @click="showRegisterModal = false; showLoginModal = true" class="text-indigo-400 font-medium hover:text-indigo-300 transition">Masuk di sini</button>
            </div>
        </div>
    </div>

</body>
</html>
