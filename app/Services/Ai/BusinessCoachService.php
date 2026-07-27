<?php

namespace App\Services\Ai;

use App\Models\AiChat;
use App\Models\Business;
use App\Services\BusinessMetricsService;
use Illuminate\Support\Str;

class BusinessCoachService
{
    public function __construct(
        private readonly GeminiClient $gemini,
        private readonly BusinessMetricsService $metrics,
    ) {}

    /**
     * Kirim pesan user ke AI Coach, simpan kedua sisi percakapan.
     */
    public function reply(AiChat $chat, string $userMessage): ?string
    {
        $chat->messages()->create(['role' => 'user', 'content' => $userMessage]);

        if ($chat->messages()->count() === 1) {
            $chat->update(['title' => Str::limit($userMessage, 50)]);
        }

        $history = $chat->messages()
            ->latest('id')
            ->take(20)
            ->get()
            ->reverse()
            ->map(fn ($m) => ['role' => $m->role, 'content' => $m->content])
            ->values()
            ->all();

        $answer = $this->gemini->chat($history, $this->systemPrompt($chat->business));

        if ($answer === null) {
            return null;
        }

        $chat->messages()->create(['role' => 'model', 'content' => $answer]);

        return $answer;
    }

    private function systemPrompt(Business $business): string
    {
        $stats = $this->metrics->dashboardStats($business);
        $health = $this->metrics->healthScore($business);

        return 'Kamu adalah AI Business Coach Grownesia — konsultan bisnis 24/7 untuk pelaku UMKM Indonesia. '.
            'Jawab dalam Bahasa Indonesia yang ramah, praktis, dan mudah dipahami pemilik usaha kecil. '.
            'Beri saran konkret tentang strategi pemasaran, operasional, penetapan harga, dan manajemen modal. '.
            "Gunakan format singkat dengan poin-poin bila membantu.\n\n".
            "Konteks bisnis pengguna:\n".
            "- Nama usaha: {$business->name} (kategori {$business->category}, kota ".($business->city ?? '-').")\n".
            '- Omzet bulan ini: '.rupiah($stats['omzet_month']).
            ($stats['omzet_month_delta'] !== null ? " ({$stats['omzet_month_delta']}% vs bulan lalu)" : '')."\n".
            "- Pesanan bulan ini: {$stats['orders_month']}, pelanggan unik: {$stats['customers_month']}\n".
            "- Business Health Score: {$health['score']}/100 ({$health['label']})\n".
            '- Produk terlaris minggu ini: '.(collect($stats['top_products'])->pluck('name')->take(3)->implode(', ') ?: '-')."\n".
            '- Produk menurun: '.(collect($stats['declining_products'])->pluck('name')->take(3)->implode(', ') ?: '-');
    }
}
