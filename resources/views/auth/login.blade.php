@extends('layouts.auth')

@section('title', 'Masuk ke Akun - Grownesia Platform')

@section('visual_heading', 'Selamat Datang Kembali di Ekosistem Digital Grownesia')
@section('visual_subtext', 'Masuk untuk mengakses AI Shopping Assistant, rekomendasi kado otomatis, dan riwayat sertifikat dampak sosial Anda.')

@section('auth_form')
<div class="space-y-6 max-w-md mx-auto w-full">
    
    <!-- Top Back Link -->
    <div>
        <a href="{{ route('landing') }}" class="inline-flex items-center gap-1.5 text-xs text-purple-300/80 hover:text-white transition group">
            <svg class="w-4 h-4 text-purple-400 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Landing Page masuk</span>
        </a>
    </div>

    <!-- Form Header -->
    <div class="space-y-1">
        <h2 class="text-2xl font-extrabold text-white font-heading">Masuk ke Akun Anda</h2>
        <p class="text-xs text-purple-300/70">Silakan masukkan email dan kata sandi yang telah terdaftar di database.</p>
    </div>

    <!-- Success / Info Alert -->
    @if (session('success'))
        <div class="p-3.5 rounded-2xl bg-emerald-950/70 border border-emerald-500/40 text-emerald-200 text-xs font-semibold flex items-center gap-2 shadow-lg">
            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Error Alerts -->
    @if ($errors->any())
        <div class="p-3.5 rounded-2xl bg-red-950/70 border border-red-500/40 text-red-200 text-xs space-y-1 shadow-lg">
            <div class="font-bold flex items-center gap-1.5">
                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Gagal Masuk:</span>
            </div>
            <ul class="list-disc list-inside text-[11px] text-red-300/90 pl-1 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST" class="space-y-4 text-xs">
        @csrf

        <!-- Email Field -->
        <div class="space-y-1">
            <label class="block font-semibold text-purple-200">Alamat Email</label>
            <div class="relative">
                <input type="email"
                       name="email"
                       id="emailInput"
                       value="{{ old('email') }}"
                       required
                       placeholder="nama@domain.com"
                       class="w-full pl-10 pr-4 py-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white placeholder-purple-400/50 focus:outline-none focus:border-purple-400 transition">
                <svg class="w-4 h-4 text-purple-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                </svg>
            </div>
        </div>

        <!-- Password Field -->
        <div class="space-y-1">
            <div class="flex items-center justify-between">
                <label class="block font-semibold text-purple-200">Kata Sandi</label>
                <a href="#" @click.prevent="alert('Silakan hubungi administrator untuk reset password.')" class="text-[11px] text-purple-400 hover:text-purple-300">Lupa kata sandi?</a>
            </div>
            <div class="relative">
                <input type="password"
                       name="password"
                       id="passwordInput"
                       value=""
                       required
                       placeholder="••••••••"
                       class="w-full pl-10 pr-4 py-3 rounded-xl bg-purple-950/80 border border-purple-500/30 text-white placeholder-purple-400/50 focus:outline-none focus:border-purple-400 transition">
                <svg class="w-4 h-4 text-purple-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between pt-1">
            <label class="flex items-center gap-2 cursor-pointer text-purple-300/80 hover:text-white">
                <input type="checkbox" name="remember" class="rounded bg-purple-950 border-purple-500/40 text-purple-600 focus:ring-0">
                <span>Ingat Saya Di Perangkat Ini</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-sm transition shadow-lg shadow-purple-900/50 flex items-center justify-center gap-2">
            <span>Masuk ke Dashboard User</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>
    </form>

    <!-- Divider -->
    <div class="flex items-center gap-3">
        <div class="flex-1 h-px bg-purple-500/20"></div>
        <span class="text-[11px] text-purple-400/60 font-medium uppercase tracking-wider">atau</span>
        <div class="flex-1 h-px bg-purple-500/20"></div>
    </div>

    <!-- Google Login Button -->
    <a href="{{ route('auth.google') }}"
       class="group w-full flex items-center justify-center gap-3 py-3 px-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 text-white text-sm font-semibold transition-all duration-200 shadow hover:shadow-lg hover:shadow-black/20 active:scale-[0.98]">
        {{-- Google "G" SVG Icon --}}
        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        <span>Masuk dengan Google</span>
        <svg class="w-4 h-4 ml-auto opacity-40 group-hover:opacity-70 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
        </svg>
    </a>

    <!-- Footer Toggle Link -->
    <div class="text-center text-xs text-purple-300/70 pt-4 border-t border-purple-500/20">
        Belum memiliki akun?
        <a href="{{ route('register') }}" class="font-bold text-purple-300 hover:text-white hover:underline transition ml-1">
            Daftar Akun Baru →
        </a>
    </div>

</div>
@endsection
