<?php

namespace App\Services\Ai;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductOptimizerService
{
    public function __construct(private readonly GeminiClient $gemini) {}

    /**
     * Analisis kualitas foto produk via Gemini Vision. Hasil disimpan ke produk.
     */
    public function analyzePhoto(Product $product): ?array
    {
        if (! $product->photo_path || ! Storage::disk('public')->exists($product->photo_path)) {
            return null;
        }

        $result = $this->gemini->analyzeImage(
            Storage::disk('public')->path($product->photo_path),
            "Analisis foto produk e-commerce ini untuk produk \"{$product->name}\" (kategori: {$product->category}). ".
            'Nilai kualitasnya sebagai foto listing marketplace: pencahayaan, ketajaman, latar belakang, komposisi, dan daya tarik.',
            [
                'type' => 'object',
                'properties' => [
                    'quality_score' => ['type' => 'integer', 'description' => 'Skor kualitas foto 0-100'],
                    'brightness_pct' => ['type' => 'integer', 'description' => 'Perkiraan tingkat kecerahan 0-100'],
                    'issues' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                        'description' => 'Masalah yang ditemukan pada foto, dalam Bahasa Indonesia',
                    ],
                    'suggestions' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                        'description' => 'Saran perbaikan konkret, dalam Bahasa Indonesia',
                    ],
                ],
                'required' => ['quality_score', 'brightness_pct', 'issues', 'suggestions'],
            ],
        );

        if (! is_array($result)) {
            return null;
        }

        $product->update(['ai_photo_analysis' => $result]);

        return $result;
    }

    /**
     * Saran judul SEO-friendly. Hasil disimpan ke produk.
     */
    public function suggestSeoTitles(Product $product): ?array
    {
        $result = $this->gemini->lite()->generateJson(
            "Produk UMKM: \"{$product->name}\" (kategori {$product->category}, harga ".rupiah($product->price).').'.
            ($product->description ? " Deskripsi: {$product->description}" : '').
            "\n\nBuat 5 alternatif judul produk yang SEO-friendly untuk marketplace Indonesia: mengandung kata kunci pencarian, spesifik (berat/varian/keunggulan), maksimal 70 karakter.",
            [
                'type' => 'object',
                'properties' => [
                    'titles' => ['type' => 'array', 'items' => ['type' => 'string']],
                    'keywords' => [
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                        'description' => 'Kata kunci pencarian utama yang relevan',
                    ],
                ],
                'required' => ['titles', 'keywords'],
            ],
            'Kamu adalah pakar SEO marketplace Indonesia (Tokopedia/Shopee) untuk produk UMKM.',
        );

        if (! is_array($result)) {
            return null;
        }

        $product->update(['ai_seo_suggestions' => $result]);

        return $result;
    }

    /**
     * Deskripsi produk otomatis (tidak langsung disimpan — pemilik bisa mengedit dulu).
     */
    public function generateDescription(Product $product): ?string
    {
        return $this->gemini->lite()->generateText(
            "Buat deskripsi produk yang menarik untuk listing marketplace.\n".
            "Nama produk: {$product->name}\nKategori: {$product->category}\nHarga: ".rupiah($product->price).
            ($product->description ? "\nDeskripsi saat ini (perbaiki): {$product->description}" : '').
            "\n\nStruktur: 1 paragraf pembuka yang menggugah, poin-poin keunggulan (gunakan emoji), info penyimpanan/pengiriman bila relevan, dan ajakan membeli. Panjang 100-180 kata, Bahasa Indonesia.",
            'Kamu adalah copywriter e-commerce spesialis produk UMKM Indonesia. Tulis dengan hangat, jelas, dan meyakinkan tanpa berlebihan.',
        );
    }
}
