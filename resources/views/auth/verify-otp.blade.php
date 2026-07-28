@extends('layouts.auth')

@section('title', 'Verifikasi Kode OTP - Grownesia Platform')

@section('visual_heading', 'Verifikasi Keamanan Akun Grownesia Anda')
@section('visual_subtext', 'Kode verifikasi 6 digit telah dikirimkan ke email Anda untuk memastikan keamanan akun dan mencegah pendaftaran bodong.')

@section('auth_form')
<div class="space-y-6 max-w-md mx-auto w-full" x-data="{ code: '' }">
    
    <!-- Top Back Link -->
    <div>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 text-xs text-purple-300/80 hover:text-white transition group">
            <svg class="w-4 h-4 text-purple-400 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>← Kembali ke Pendaftaran</span>
        </a>
    </div>

    <!-- Form Header -->
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-2xl bg-purple-900/60 border border-purple-500/30 flex items-center justify-center text-purple-300 shadow-inner">
                <svg class="w-6 h-6 text-purple-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <h2 class="text-2xl font-extrabold text-white font-heading">Verifikasi Email Anda</h2>
        <p class="text-xs text-purple-300/70 leading-relaxed">
            Kami telah mengirimkan <strong>6-digit Kode OTP</strong> ke alamat email:
            <span class="text-purple-200 font-mono font-bold block mt-1 text-sm bg-purple-950/80 px-3 py-1.5 rounded-xl border border-purple-500/20 w-fit">
                {{ session('pending_verification_email', $user->email ?? 'email@anda.com') }}
            </span>
        </p>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="p-3.5 rounded-2xl bg-emerald-950/70 border border-emerald-500/40 text-emerald-200 text-xs font-semibold flex items-center gap-2 shadow-lg">
            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Alert Info / Notice -->
    @if (session('info'))
        <div class="p-3.5 rounded-2xl bg-purple-950/80 border border-purple-500/40 text-purple-200 text-xs space-y-1 shadow-lg">
            <div class="font-bold flex items-center gap-1.5 text-purple-300">
                <svg class="w-4 h-4 text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Verifikasi Diperlukan:</span>
            </div>
            <p class="text-[11px] text-purple-300/90">
                {{ session('info') }}
            </p>
        </div>
    @endif

    <!-- Alert Error -->
    @if (session('error') || $errors->any())
        <div class="p-3.5 rounded-2xl bg-red-950/70 border border-red-500/40 text-red-200 text-xs space-y-1 shadow-lg">
            <div class="font-bold flex items-center gap-1.5">
                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Kode Tidak Sesuai:</span>
            </div>
            <p class="text-[11px] text-red-300/90">
                {{ session('error') ?? $errors->first() }}
            </p>
        </div>
    @endif


    <!-- OTP Verification Form -->
    <form action="{{ route('verification.verify') }}" method="POST" class="space-y-5 text-xs">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}">
        <input type="hidden" name="email" value="{{ $user->email ?? '' }}">

        <!-- 6-Digit Interactive Input Component -->
        <div class="space-y-3">
            <label class="block font-semibold text-purple-200 text-center">Masukkan 6-Digit Kode Verifikasi</label>
            
            <div class="relative max-w-xs mx-auto">
                <input type="text"
                       name="verification_code"
                       id="verification_code"
                       x-model="code"
                       maxlength="6"
                       inputmode="numeric"
                       pattern="[0-9]*"
                       autocomplete="one-time-code"
                       autofocus
                       class="absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer"
                       @input="code = code.replace(/[^0-9]/g, '').slice(0, 6)">
                
                <div class="flex items-center justify-center gap-2">
                    <template x-for="i in 6" :key="i">
                        <div class="w-11 h-14 rounded-2xl bg-purple-950/90 border flex items-center justify-center font-mono text-2xl font-black transition shadow-inner"
                             :class="code.length >= i ? 'border-purple-400 text-purple-200 bg-purple-900/80 shadow-purple-500/20' : (code.length === i - 1 ? 'border-purple-300 ring-2 ring-purple-400/40 text-white animate-pulse' : 'border-purple-500/30 text-purple-400/40')">
                            <span x-text="code[i - 1] || ''"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                :disabled="code.length !== 6"
                :class="code.length === 6 ? 'opacity-100 cursor-pointer hover:from-purple-500 hover:to-fuchsia-500 shadow-purple-900/50' : 'opacity-50 cursor-not-allowed'"
                class="w-full py-3.5 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 text-white font-bold text-sm transition shadow-lg flex items-center justify-center gap-2">
            <span>Verifikasi & Aktifkan Akun</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </button>
    </form>

    <!-- Resend Code Section -->
    <div class="flex items-center justify-between text-xs text-purple-300/70 pt-4 border-t border-purple-500/20">
        <span>Tidak menerima kode?</span>
        <form action="{{ route('verification.resend') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}">
            <input type="hidden" name="email" value="{{ $user->email ?? '' }}">
            <button type="submit" class="font-bold text-purple-300 hover:text-white hover:underline transition">
                Kirim Ulang Kode OTP →
            </button>
        </form>
    </div>

</div>
@endsection
