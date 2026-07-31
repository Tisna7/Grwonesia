@extends('layouts.business')

@section('title', 'Omnichannel WhatsApp')

@section('content')
  <div class="grid gap-6">
    @unless ($isLive)
      <div class="glass-card p-4 border-amber-500/30 flex items-start gap-3">
        <span class="text-lg">🔌</span>
        <div>
          <p class="text-sm font-semibold text-amber-400">Mode Simulasi</p>
          <p class="mt-1 text-xs text-slate-400">
            Gateway WhatsApp belum terhubung — pesan dicatat tapi tidak benar-benar terkirim.
            Hubungkan API Baileys milikmu dengan mengisi <code class="font-mono text-primary-300">WA_DRIVER=baileys</code>,
            <code class="font-mono text-primary-300">WA_GATEWAY_URL</code>, dan <code
              class="font-mono text-primary-300">WA_GATEWAY_TOKEN</code> di .env.
          </p>
        </div>
      </div>
    @endunless

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <div class="space-y-6">
        {{-- WhatsApp Toko Configuration Card --}}
        <div class="glass-card p-6">
          <h3 class="text-sm font-bold text-white font-heading">🔗 Pengaturan WhatsApp Toko</h3>
          <p class="mt-1 text-[11px] text-slate-500 font-medium">Sambungkan nomor WhatsApp Anda dengan toko Grownesia.</p>

          <div class="mt-4 p-3.5 rounded-xl border border-primary-500/25 bg-slate-900/60 text-xs">
            @if (auth()->user()->raw_phone || auth()->user()->phone)
              <p class="font-bold text-emerald-400 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                WhatsApp Terkoneksi:
              </p>
              <p class="mt-1 font-mono text-white text-xs bg-slate-950/80 p-2 rounded-lg border border-primary-500/10">
                {{ auth()->user()->raw_phone ?? auth()->user()->phone }}
              </p>
              <p class="mt-1 text-[10px] text-slate-500 leading-normal">
                (ID/LID berhasil dideteksi otomatis. Ketik <b>tambah produk</b> di chat bot untuk mengelola produk Anda via
                WA).
              </p>
            @else
              <p class="font-bold text-rose-400 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                WhatsApp Belum Terhubung
              </p>
              <p class="mt-1 text-[10px] text-slate-500 leading-normal">Nomor ini diperlukan agar Bot mengenali perintah
                penjualan Anda.</p>
            @endif
          </div>

          <form method="POST" action="{{ route('business.whatsapp.update') }}" class="mt-4 space-y-3">
            @csrf
            <div>
              <label class="block text-xs font-semibold text-slate-300 mb-1">Nomor Telepon Toko:</label>
              <input type="text" name="phone"
                value="{{ old('phone', auth()->user()->raw_phone ?? auth()->user()->phone) }}" class="form-input text-xs"
                placeholder="Contoh: 085183700720" required>
              @error('phone')<p class="text-xs text-rose-400 mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn-primary w-full text-xs py-2.5">
              Tautkan WhatsApp Toko
            </button>
          </form>
        </div>

        {{-- Broadcast composer --}}
        <div class="glass-card p-6">
          <h3 class="text-sm font-bold text-white">📣 Broadcast ke Pelanggan</h3>
          <p class="mt-1 text-[11px] text-slate-500">{{ $customerCount }} pelanggan punya nomor WhatsApp.</p>

          <form method="POST" action="{{ route('business.whatsapp.broadcast') }}" class="mt-4 space-y-3">
            @csrf
            <textarea name="body" rows="6" class="form-input"
              placeholder="Tulis pesan broadcast… Tip: buat draf otomatis lewat Marketing Center (tipe: Pesan Broadcast WA).">{{ old('body') }}</textarea>
            @error('body')<p class="text-xs text-rose-400">{{ $message }}</p>@enderror
            <button type="submit" class="btn-primary w-full"
              onclick="return confirm('Kirim broadcast ke {{ $customerCount }} pelanggan?')">
              Kirim Broadcast
            </button>
          </form>

          @if ($recentBroadcasts->isNotEmpty())
            <div class="mt-5 pt-4 border-t border-primary-400/10">
              <p class="text-xs font-semibold text-slate-300">Draf dari Marketing Center:</p>
              <div class="mt-2 space-y-2">
                @foreach ($recentBroadcasts as $draft)
                  <details class="glass-card bg-primary-950/30! p-3">
                    <summary class="text-[11px] text-slate-400 cursor-pointer truncate">{{ $draft->brief }}</summary>
                    <p class="mt-2 text-xs text-slate-300 whitespace-pre-line">{{ $draft->content }}</p>
                  </details>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      </div>

      {{-- Log pesan --}}
      <div class="glass-card overflow-hidden xl:col-span-2">
        <div class="px-5 py-4 border-b border-primary-400/10">
          <h3 class="text-sm font-bold text-white">Riwayat Pesan</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="text-left text-xs text-slate-400 border-b border-primary-400/10">
                <th class="px-5 py-3 font-semibold">Tujuan</th>
                <th class="px-5 py-3 font-semibold">Tipe</th>
                <th class="px-5 py-3 font-semibold">Pesan</th>
                <th class="px-5 py-3 font-semibold">Status</th>
                <th class="px-5 py-3 font-semibold">Waktu</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-primary-400/5">
              @forelse ($messages as $message)
                <tr class="hover:bg-primary-600/5">
                  <td class="px-5 py-3">
                    <p class="text-slate-200 text-xs">{{ $message->customer?->name ?? '—' }}</p>
                    <p class="font-mono text-[11px] text-slate-500">{{ $message->to_number }}</p>
                  </td>
                  <td class="px-5 py-3 text-xs text-slate-400">
                    {{ ['order_confirmation' => 'Konfirmasi Order', 'broadcast' => 'Broadcast', 'auto_reply' => 'Auto Reply'][$message->type] ?? $message->type }}
                  </td>
                  <td class="px-5 py-3 text-xs text-slate-300 max-w-xs truncate">{{ $message->body }}</td>
                  <td class="px-5 py-3">
                    @php
                      $badge = match ($message->status) {
                        'sent' => ['bg-emerald-500/15 text-emerald-400 border-emerald-500/40', 'Terkirim'],
                        'mocked' => ['bg-primary-500/15 text-primary-300 border-primary-500/40', 'Simulasi'],
                        'failed' => ['bg-rose-500/15 text-rose-400 border-rose-500/40', 'Gagal'],
                        default => ['bg-amber-500/15 text-amber-400 border-amber-500/40', 'Menunggu'],
                      };
                    @endphp
                    <span class="badge-pill {{ $badge[0] }}">{{ $badge[1] }}</span>
                  </td>
                  <td class="px-5 py-3 text-[11px] text-slate-500">{{ $message->created_at->format('d M H:i') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="px-5 py-8 text-center text-xs text-slate-500">Belum ada pesan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="px-5 py-3">{{ $messages->links() }}</div>
      </div>
    </div>
  </div>
@endsection