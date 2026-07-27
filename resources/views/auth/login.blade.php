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
            <span>← Kembali ke Landing Page</span>
        </a>
    </div>

    <!-- Form Header -->
    <div class="space-y-1">
        <h2 class="text-2xl font-extrabold text-white font-heading">Masuk ke Akun Anda</h2>
        <p class="text-xs text-purple-300/70">Silakan masukkan email dan kata sandi yang telah terdaftar di database.</p>
    </div>

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
                       value="{{ old('email', 'budi@grownesia.id') }}"
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
                <a href="#" @click.prevent="alert('Silakan gunakan password demo: password')" class="text-[11px] text-purple-400 hover:text-purple-300">Lupa kata sandi?</a>
            </div>
            <div class="relative">
                <input type="password"
                       name="password"
                       id="passwordInput"
                       value="password"
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

        <!-- Quick Fill Demo Banner -->
        <div class="p-3 rounded-xl bg-purple-900/40 border border-purple-500/20 flex items-center justify-between text-[11px]">
            <span class="text-purple-300">⚡ <strong>Database Ready:</strong> `budi@grownesia.id`</span>
            <button type="button" @click="document.getElementById('emailInput').value='budi@grownesia.id'; document.getElementById('passwordInput').value='password';" class="px-2 py-1 rounded bg-purple-800 hover:bg-purple-700 text-white font-bold transition">
                Isi Otomatis
            </button>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-500 hover:to-fuchsia-500 text-white font-bold text-sm transition shadow-lg shadow-purple-900/50 flex items-center justify-center gap-2">
            <span>Masuk ke Dashboard User</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>
    </form>

    <!-- Footer Toggle Link -->
    <div class="text-center text-xs text-purple-300/70 pt-4 border-t border-purple-500/20">
        Belum memiliki akun?
        <a href="{{ route('register') }}" class="font-bold text-purple-300 hover:text-white hover:underline transition ml-1">
            Daftar Akun Baru →
        </a>
    </div>

</div>
@endsection
