@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<div class="glass-card w-full max-w-md p-8">
    <h1 class="text-2xl font-extrabold text-white">Masuk</h1>
    <p class="mt-1 text-sm text-slate-400">Kelola bisnismu dengan bantuan AI.</p>

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf
        <x-input label="Email" name="email" type="email" required placeholder="nama@usaha.com"/>
        <x-input label="Kata Sandi" name="password" type="password" required placeholder="••••••••"/>

        <label class="flex items-center gap-2 text-xs text-slate-400">
            <input type="checkbox" name="remember" class="rounded border-primary-400/30 bg-transparent">
            Ingat saya
        </label>

        <button type="submit" class="btn-primary w-full">Masuk</button>
    </form>

    <p class="mt-6 text-center text-xs text-slate-400">
        Belum punya akun bisnis?
        <a href="{{ route('register') }}" class="font-semibold text-primary-300 hover:text-primary-200">Daftar sekarang</a>
    </p>
</div>
@endsection
