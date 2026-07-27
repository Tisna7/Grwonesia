<?php

namespace App\Services\Ai;

use App\Enums\MarketingContentType;
use App\Models\Business;
use App\Models\MarketingContent;
use App\Models\Product;

class MarketingContentService
{
    public function __construct(private readonly GeminiClient $gemini) {}

    /**
     * Generate konten marketing sesuai tipe, lalu simpan riwayatnya.
     */
    public function generate(Business $business, MarketingContentType $type, string $brief, ?Product $product = null): ?MarketingContent
    {
        $content = $this->gemini->lite()->generateText(
            $this->buildPrompt($type, $brief, $business, $product),
            'Kamu adalah AI Marketing Center Grownesia — copywriter dan kreator konten spesialis UMKM Indonesia. '.
            'Tulis dalam Bahasa Indonesia yang menarik, sesuai platform target, dan siap pakai tanpa perlu diedit.',
        );

        if ($content === null) {
            return null;
        }

        return MarketingContent::create([
            'business_id' => $business->id,
            'product_id' => $product?->id,
            'type' => $type,
            'brief' => $brief,
            'content' => $content,
        ]);
    }

    private function buildPrompt(MarketingContentType $type, string $brief, Business $business, ?Product $product): string
    {
        $context = "Bisnis: {$business->name} ({$business->category}, ".($business->city ?? 'Indonesia').').';

        if ($product) {
            $context .= " Produk: {$product->name}, harga ".rupiah($product->price).
                ($product->description ? ", deskripsi: {$product->description}" : '').'.';
        }

        $instruction = match ($type) {
            MarketingContentType::PromoCopy => 'Buat copy promosi penjualan yang persuasif (80-120 kata). Sertakan headline menarik, manfaat utama, dan call-to-action jelas.',
            MarketingContentType::IgCaption => 'Buat caption Instagram yang engaging: hook di baris pertama, cerita singkat, emoji secukupnya, call-to-action, dan 8-12 hashtag relevan di akhir.',
            MarketingContentType::TiktokCaption => 'Buat caption TikTok singkat dan catchy (maksimal 150 karakter) plus 5-8 hashtag trending yang relevan. Sertakan juga 3 ide hook video 3 detik pertama.',
            MarketingContentType::WaBroadcast => 'Buat pesan broadcast WhatsApp yang personal dan tidak terkesan spam: sapaan hangat, info promo/produk, dan ajakan respon. Gunakan format WhatsApp (*tebal*, _miring_) dan emoji secukupnya. Maksimal 100 kata.',
            MarketingContentType::Hashtags => 'Buat 20 hashtag terbaik yang dikelompokkan: 5 hashtag besar (>500rb post), 10 hashtag menengah, 5 hashtag niche/lokal. Format daftar dengan kategori.',
            MarketingContentType::Poster => 'Buat teks untuk poster promo: headline besar (maksimal 6 kata), subheadline (1 kalimat), 3 poin keunggulan singkat, dan call-to-action. Format dengan label [HEADLINE], [SUBHEADLINE], [POIN], [CTA].',
        };

        return "{$context}\n\nBrief dari pemilik bisnis: {$brief}\n\n{$instruction}";
    }
}
