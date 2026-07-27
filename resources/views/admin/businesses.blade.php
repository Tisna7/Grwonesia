@extends('layouts.admin')

@section('title', 'Verifikasi Bisnis & Risk Management')

@section('content')
<div class="grid gap-4">
    @foreach ($businesses as $business)
        <div class="glass-card glass-card-hover p-5" x-data="{ open: false }">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold text-white">{{ $business->name }}</h3>
                        @if ($business->verification_status === 'verified')
                            <span class="badge-pill bg-emerald-500/15 text-emerald-400 border-emerald-500/40">✓ Terverifikasi</span>
                        @elseif ($business->verification_status === 'rejected')
                            <span class="badge-pill bg-rose-500/15 text-rose-400 border-rose-500/40">✕ Ditolak</span>
                        @else
                            <span class="badge-pill bg-amber-500/15 text-amber-400 border-amber-500/40">⏳ Menunggu Verifikasi</span>
                        @endif
                        @php
                            $riskBadge = match ($business->risk_level) {
                                'tinggi' => 'bg-rose-500/15 text-rose-400 border-rose-500/40',
                                'sedang' => 'bg-amber-500/15 text-amber-400 border-amber-500/40',
                                default => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/40',
                            };
                        @endphp
                        <span class="badge-pill {{ $riskBadge }}">Risiko: {{ ucfirst($business->risk_level) }}</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $business->category }} · 📍 {{ $business->city ?? '—' }} ·
                        Pemilik: {{ $business->user->name }} ({{ $business->user->email }})
                    </p>
                    <p class="mt-1 text-[11px] text-slate-500">
                        {{ $business->products_count }} produk · {{ $business->orders_count }} order ·
                        pembatalan {{ $business->cancel_rate }}%
                        {{ $business->verified_at ? ' · diverifikasi '.$business->verified_at->format('d M Y') : '' }}
                    </p>
                    @if ($business->verification_note)
                        <p class="mt-1 text-[11px] italic text-slate-400">📝 {{ $business->verification_note }}</p>
                    @endif
                </div>
                <button class="btn-secondary !py-1.5 !px-3 text-xs shrink-0" @click="open = !open" x-text="open ? 'Tutup' : 'Proses'"></button>
            </div>

            <div x-show="open" x-cloak class="mt-4 pt-4 border-t border-primary-400/10">
                <form method="POST" action="{{ route('admin.businesses.update', $business) }}" class="flex flex-wrap items-end gap-3">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Status</label>
                        <select name="verification_status" class="form-input !w-auto">
                            <option value="verified" @selected($business->verification_status === 'verified')>✓ Verifikasi</option>
                            <option value="pending" @selected($business->verification_status === 'pending')>⏳ Pending</option>
                            <option value="rejected" @selected($business->verification_status === 'rejected')>✕ Tolak</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-48">
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Catatan (opsional)</label>
                        <input type="text" name="verification_note" value="{{ $business->verification_note }}"
                               class="form-input" placeholder="cth: Dokumen NIB valid / KTP tidak terbaca">
                    </div>
                    <button type="submit" class="btn-primary text-xs">Simpan</button>
                </form>
            </div>
        </div>
    @endforeach

    <div>{{ $businesses->links() }}</div>
</div>
@endsection
