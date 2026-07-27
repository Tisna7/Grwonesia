@extends('layouts.business')

@section('title', 'AI Business Coach')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6" x-data="coachChat()">
    {{-- Riwayat percakapan --}}
    <div class="glass-card p-4 lg:col-span-1 max-h-[75vh] overflow-y-auto">
        <a href="{{ route('business.coach') }}" class="btn-primary w-full text-xs mb-3">+ Percakapan Baru</a>
        <div class="space-y-1">
            @forelse ($chats as $chat)
                <a href="{{ route('business.coach', ['chat' => $chat->id]) }}"
                   class="block px-3 py-2 rounded-lg text-xs truncate transition-colors {{ ($activeChat?->id === $chat->id) ? 'bg-primary-600/25 text-white border border-primary-400/30' : 'text-slate-400 hover:bg-primary-600/10 hover:text-white' }}">
                    {{ $chat->title }}
                </a>
            @empty
                <p class="text-xs text-slate-500 px-2">Belum ada percakapan.</p>
            @endforelse
        </div>
    </div>

    {{-- Area chat --}}
    <div class="glass-card lg:col-span-3 flex flex-col h-[75vh]">
        <div class="px-5 py-4 border-b border-primary-400/10 flex items-center gap-3">
            <span class="w-9 h-9 rounded-xl ai-gradient flex items-center justify-center text-white ai-active-pulse">✦</span>
            <div>
                <p class="text-sm font-bold text-white">AI Business Coach</p>
                <p class="text-[11px] text-slate-400">Konsultan bisnis 24/7 — strategi, harga, operasional, modal</p>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4" x-ref="messages">
            @if ($activeChat)
                @foreach ($activeChat->messages as $message)
                    <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] rounded-2xl px-4 py-3 text-sm leading-relaxed {{ $message->role === 'user' ? 'bg-primary-800 text-white' : 'glass-card !rounded-2xl text-slate-200' }}">
                            <div class="whitespace-pre-line">{{ $message->content }}</div>
                        </div>
                    </div>
                @endforeach
            @endif

            <template x-for="msg in liveMessages" :key="msg.id">
                <div class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                    <div class="max-w-[80%] rounded-2xl px-4 py-3 text-sm leading-relaxed"
                         :class="msg.role === 'user' ? 'bg-primary-800 text-white' : 'glass-card !rounded-2xl text-slate-200'">
                        <div class="whitespace-pre-line" x-text="msg.content"></div>
                    </div>
                </div>
            </template>

            <div x-show="thinking" class="flex justify-start">
                <div class="glass-card !rounded-2xl px-4 py-3 text-sm text-slate-400">
                    <span class="ai-gradient-text font-semibold">AI sedang berpikir</span> <span class="animate-pulse">…</span>
                </div>
            </div>

            @if (! $activeChat)
                <div x-show="liveMessages.length === 0 && !thinking" class="h-full flex flex-col items-center justify-center text-center">
                    <span class="text-4xl">🤖</span>
                    <p class="mt-3 text-sm font-semibold text-white">Tanya apa saja soal bisnismu</p>
                    <p class="mt-1 text-xs text-slate-400 max-w-sm">Coach memahami data omzet, produk, dan kesehatan bisnismu secara real-time.</p>
                    <div class="mt-4 flex flex-wrap gap-2 justify-center">
                        <button class="badge-pill bg-primary-600/15 text-primary-300 border-primary-500/30 hover:bg-primary-600/30" @click="input = 'Bagaimana cara menaikkan omzet bulan ini?'; send()">📈 Cara naikkan omzet?</button>
                        <button class="badge-pill bg-primary-600/15 text-primary-300 border-primary-500/30 hover:bg-primary-600/30" @click="input = 'Apakah harga produk saya sudah tepat?'; send()">💰 Cek strategi harga</button>
                        <button class="badge-pill bg-primary-600/15 text-primary-300 border-primary-500/30 hover:bg-primary-600/30" @click="input = 'Buatkan ide promosi untuk akhir pekan'; send()">✨ Ide promosi weekend</button>
                    </div>
                </div>
            @endif
        </div>

        <div class="px-5 py-4 border-t border-primary-400/10">
            <template x-if="error">
                <div class="mb-2 rounded-lg border border-amber-500/40 bg-amber-500/10 px-3 py-2 text-xs text-amber-400" x-text="error"></div>
            </template>
            <form @submit.prevent="send()" class="flex gap-3">
                <input type="text" x-model="input" class="form-input flex-1" placeholder="Tulis pertanyaanmu…" :disabled="thinking">
                <button type="submit" class="btn-primary shrink-0" :disabled="thinking || !input.trim()">Kirim</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function coachChat() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    return {
        chatId: {{ $activeChat?->id ?? 'null' }},
        input: '',
        liveMessages: [],
        thinking: false,
        error: null,
        nextId: 1,

        async send() {
            const message = this.input.trim();
            if (!message || this.thinking) return;

            this.liveMessages.push({ id: this.nextId++, role: 'user', content: message });
            this.input = '';
            this.thinking = true;
            this.error = null;
            this.scrollDown();

            try {
                const url = this.chatId
                    ? `{{ url('business/coach') }}/${this.chatId}`
                    : `{{ url('business/coach') }}`;
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message }),
                });
                const json = await res.json();
                if (!res.ok) {
                    this.error = json.error ?? 'Terjadi kesalahan. Coba lagi.';
                    return;
                }
                this.chatId = json.data.chat_id;
                this.liveMessages.push({ id: this.nextId++, role: 'model', content: json.data.answer });
                this.scrollDown();
            } catch (e) {
                this.error = 'Gagal terhubung ke server.';
            } finally {
                this.thinking = false;
            }
        },

        scrollDown() {
            this.$nextTick(() => {
                this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight;
            });
        },
    };
}
</script>
@endpush
