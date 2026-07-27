@extends('layouts.guest')

@section('title', 'Daftar Akun Bisnis')

@section('content')
<div class="glass-card w-full max-w-lg p-8">
    <h1 class="text-2xl font-extrabold text-white">Daftar Akun Bisnis</h1>
    <p class="mt-1 text-sm text-slate-400">Gabung sebagai pelaku usaha — UMKM, Supplier, Koperasi, BUMDes, atau Reseller.</p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf

        <p class="text-xs font-bold uppercase tracking-wider text-primary-300">Data Diri</p>
        <x-input label="Nama Lengkap" name="name" required placeholder="Nama pemilik usaha"/>
        <div class="grid sm:grid-cols-2 gap-4">
            <x-input label="Email" name="email" type="email" required placeholder="nama@usaha.com"/>
            <x-input label="No. HP" name="phone" placeholder="08xxxxxxxxxx"/>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <x-input label="Kata Sandi" name="password" type="password" required/>
            <x-input label="Ulangi Kata Sandi" name="password_confirmation" type="password" required/>
        </div>

        <p class="pt-2 text-xs font-bold uppercase tracking-wider text-primary-300">Data Usaha</p>
        <x-input label="Nama Usaha" name="business_name" required placeholder="cth: Kopi Nusantara"/>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label for="business_category" class="block text-xs font-semibold text-slate-300 mb-1.5">
                    Kategori Usaha <span class="text-magenta">*</span>
                </label>
                <select id="business_category" name="business_category" required class="form-input">
                    <option value="">Pilih kategori…</option>
                    @foreach (['Kuliner', 'Fashion', 'Kerajinan', 'Pertanian', 'Jasa', 'Lainnya'] as $cat)
                        <option value="{{ $cat }}" @selected(old('business_category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('business_category')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
            <x-input label="Kota" name="business_city" placeholder="cth: Sukabumi"/>
        </div>
        <x-input label="Nomor WhatsApp Bisnis" name="wa_number" placeholder="628xxxxxxxxxx"/>

        <button type="submit" class="btn-primary w-full">Buat Akun Bisnis</button>
    </form>

    <p class="mt-6 text-center text-xs text-slate-400">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-primary-300 hover:text-primary-200">Masuk</a>
    </p>
</div>
@endsection
