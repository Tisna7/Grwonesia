<?php

namespace App\Services\Ai;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Throwable;

class ShoppingAssistantService
{
  public function __construct(private readonly GeminiClient $gemini)
  {
  }

  /**
   * Merekomendasikan paket bundel kado dari katalog produk UMKM nyata sesuai budget.
   */
  public function recommendGiftBundle(string $userQuery, ?float $maxBudget = null): array
  {
    $cacheKey = 'gift_bundle_' . md5(strtolower(trim($userQuery)) . '_' . ($maxBudget ?? '0'));

    return Cache::remember($cacheKey, now()->addDays(7), function () use ($userQuery, $maxBudget) {
      $productsQuery = Product::with('business')->active()->latest();

      if ($maxBudget && $maxBudget > 0) {
        $productsQuery->where('price', '<=', $maxBudget);
      }

      $products = $productsQuery->take(15)->get();

      if ($products->isEmpty()) {
        return [
          'success' => false,
          'message' => 'Maaf, belum ada produk yang sesuai dengan budget Anda saat ini.',
          'bundle' => [],
          'total_price' => 0,
        ];
      }

      $catalogSummary = $products->map(function ($p) {
        return [
          'id' => $p->id,
          'name' => $p->name,
          'category' => $p->category,
          'price' => (float) $p->price,
          'umkm' => $p->business?->name ?? 'UMKM Mitra',
          'city' => $p->business?->city ?? 'Indonesia',
          'description' => $p->description ?? '',
        ];
      })->toArray();

      $prompt = "Berikut adalah daftar katalog produk UMKM lokal Grownesia:\n" .
        json_encode($catalogSummary, JSON_PRETTY_PRINT) . "\n\n" .
        "Permintaan Pembeli: \"{$userQuery}\"\n" .
        ($maxBudget ? "Maksimal Total Budget: Rp" . number_format($maxBudget, 0, ',', '.') : "Tanpa batas budget spesifik.") . "\n\n" .
        "Tugas Anda:\n" .
        "1. Pilih 2 hingga 4 produk dari katalog di atas yang paling cocok dikombinasikan sebagai paket kado/bundel.\n" .
        "2. Total harga semua produk yang dipilih HARUS lebih kecil atau sama dengan maksimal budget (jika diberikan).\n" .
        "3. Buat nama paket yang menarik, narasi singkat alasan pemilihan paket kado ini, dan sebutkan dampak sosial bagi UMKM yang didukung.\n";

      $schema = [
        'type' => 'object',
        'properties' => [
          'bundle_name' => ['type' => 'string', 'description' => 'Nama paket kado yang menarik dan kreatif'],
          'narrative' => ['type' => 'string', 'description' => 'Alasan singkat kenapa paket ini cocok untuk kueri pembeli'],
          'impact_story' => ['type' => 'string', 'description' => 'Ringkasan dampak sosial bagi UMKM mitra dari pembelian paket ini'],
          'selected_product_ids' => [
            'type' => 'array',
            'items' => ['type' => 'integer'],
            'description' => 'Daftar ID produk dari katalog yang dipilih dalam paket',
          ],
        ],
        'required' => ['bundle_name', 'narrative', 'impact_story', 'selected_product_ids'],
      ];

      $system = 'Kamu adalah AI Personal Shopper & Gift Consultant spesialis produk UMKM lokal Indonesia Grownesia. Tugasmu meracik paket kado bernilai tinggi, ramah budget, dan berdampak sosial.';

      try {
        $aiResponse = $this->gemini->generateJson($prompt, $schema, $system);

        if (!is_array($aiResponse) || empty($aiResponse['selected_product_ids'])) {
          return $this->fallbackBundle($products, $userQuery, $maxBudget);
        }

        $selectedIds = $aiResponse['selected_product_ids'];
        $selectedProducts = $products->filter(fn($p) => in_array($p->id, $selectedIds))->values();

        if ($selectedProducts->isEmpty()) {
          return $this->fallbackBundle($products, $userQuery, $maxBudget);
        }

        $totalPrice = $selectedProducts->sum('price');

        return [
          'success' => true,
          'bundle_name' => $aiResponse['bundle_name'] ?? 'Paket Spesial UMKM',
          'narrative' => $aiResponse['narrative'] ?? 'Paket kado pilihan terbaik dari produk lokal terverifikasi.',
          'impact_story' => $aiResponse['impact_story'] ?? 'Pembelian ini membantu pemberdayaan UMKM & pekerja lokal.',
          'products' => $selectedProducts->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (float) $p->price,
            'category' => $p->category,
            'umkm' => $p->business?->name ?? 'UMKM Mitra',
            'image' => $p->image_url,
          ])->toArray(),
          'total_price' => (float) $totalPrice,
        ];
      } catch (Throwable $e) {
        Log::warning('ShoppingAssistantService: error generating bundle', ['msg' => $e->getMessage()]);

        return $this->fallbackBundle($products, $userQuery, $maxBudget);
      }
    });
  }

  /**
   * Chat percakapan alami asisten belanja.
   */
  public function chatShopper(string $userQuery, array $history = []): array
  {
    $products = Product::with('business')->active()->latest()->take(10)->get();

    $catalogContext = $products->map(fn($p) => "- {$p->name} (Rp" . number_format($p->price, 0, ',', '.') . ") dari {$p->business?->name} ({$p->business?->city})")->join("\n");

    $prompt = "Pertanyaan Pembeli: \"{$userQuery}\"\n\n" .
      "Katalog Ringkas Produk UMKM Terpopuler:\n{$catalogContext}\n\n" .
      "Jawab pertanyaan pembeli dengan ramah, komunikatif, dan rekomendasikan 1-2 produk relevan dari katalog di atas jika cocok. Berikan rekomendasi yang solutif dalam 2-3 kalimat hangat.";

    $system = 'Kamu adalah Grownesia AI Assistant — asisten belanja ramah yang membantu masyarakat menemukan produk UMKM berkualitas tinggi di Indonesia.';

    try {
      $reply = $this->gemini->generateText($prompt, $system);

      if (filled($reply)) {
        return [
          'success' => true,
          'reply' => $reply,
        ];
      }
    } catch (Throwable $e) {
      Log::warning('ShoppingAssistantService: chat error', ['msg' => $e->getMessage()]);
    }

    // Smart Offline Fallback: cari produk yang relevan dengan kueri pengguna dari database
    return [
      'success' => true,
      'reply' => $this->generateSmartOfflineReply($userQuery),
    ];
  }

  /**
   * Menghasilkan balasan kontekstual dari database produk ketika AI Gemini tidak merespon/habis kuota.
   */
  private function generateSmartOfflineReply(string $userQuery): string
  {
    $keywords = array_filter(explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $userQuery))), fn($w) => strlen($w) >= 3);

    $queryBuilder = Product::with('business')->active();

    if (!empty($keywords)) {
      $queryBuilder->where(function ($q) use ($keywords) {
        foreach ($keywords as $word) {
          $q->orWhere('name', 'LIKE', "%{$word}%")
            ->orWhere('category', 'LIKE', "%{$word}%")
            ->orWhere('description', 'LIKE', "%{$word}%");
        }
      });
    }

    $matchedProducts = $queryBuilder->take(3)->get();

    if ($matchedProducts->isEmpty()) {
      $matchedProducts = Product::with('business')->active()->inRandomOrder()->take(2)->get();
    }

    if ($matchedProducts->isEmpty()) {
      return 'Halo! Saya Grownesia AI Assistant. Ada yang bisa saya bantu untuk menemukan produk UMKM unggulan hari ini?';
    }

    $productListText = $matchedProducts->map(function ($p) {
      $price = number_format($p->price, 0, ',', '.');
      $umkm = $p->business?->name ?? 'UMKM Mitra';
      $city = $p->business?->city ?? 'Indonesia';
      return "- **{$p->name}** (Rp{$price}) dari *{$umkm}* ({$city})";
    })->join("\n");

    return "Berdasarkan kebutuhan Anda mengenai **\"{$userQuery}\"**, berikut adalah produk UMKM terverifikasi di Grownesia yang bisa Anda lirik:\n\n" .
      $productListText . "\n\n" .
      "Silakan klik produk di atas untuk melihat detail atau bertanya lebih lanjut!";
  }

  private function fallbackBundle($products, string $userQuery, ?float $maxBudget): array
  {
    $selected = $products->take(2);
    $totalPrice = $selected->sum('price');

    return [
      'success' => true,
      'bundle_name' => 'Paket Hemat Favorit UMKM',
      'narrative' => 'Kombinasi produk favorit yang dikurasi khusus untuk memenuhi kebutuhan kado Anda.',
      'impact_story' => 'Mendukung pemberdayaan pengrajin dan pelaku usaha lokal Nusantara.',
      'products' => $selected->map(fn($p) => [
        'id' => $p->id,
        'name' => $p->name,
        'price' => (float) $p->price,
        'category' => $p->category,
        'umkm' => $p->business?->name ?? 'UMKM Mitra',
        'image' => $p->image_url,
      ])->toArray(),
      'total_price' => (float) $totalPrice,
    ];
  }

  /**
   * Membandingkan dua produk UMKM secara mendalam menggunakan AI.
   */
  public function compareProducts(int $productAId, int $productBId): array
  {
    $productA = Product::with('business')->find($productAId);
    $productB = Product::with('business')->find($productBId);

    if (!$productA || !$productB) {
      return [
        'success' => false,
        'message' => 'Produk tidak ditemukan.',
      ];
    }

    $cacheKey = "compare_products_{$productAId}_{$productBId}";

    return Cache::remember($cacheKey, now()->addDays(7), function () use ($productA, $productB) {
      $catalogSummary = [
        'product_a' => [
          'id' => $productA->id,
          'name' => $productA->name,
          'category' => $productA->category,
          'price' => (float) $productA->price,
          'umkm' => $productA->business?->name ?? 'UMKM Mitra',
          'city' => $productA->business?->city ?? 'Indonesia',
          'description' => $productA->description ?? '',
        ],
        'product_b' => [
          'id' => $productB->id,
          'name' => $productB->name,
          'category' => $productB->category,
          'price' => (float) $productB->price,
          'umkm' => $productB->business?->name ?? 'UMKM Mitra',
          'city' => $productB->business?->city ?? 'Indonesia',
          'description' => $productB->description ?? '',
        ],
      ];

      $prompt = "Bandingkan dua produk UMKM lokal Grownesia berikut secara obyektif:\n" .
        "Produk A:\n" . json_encode($catalogSummary['product_a'], JSON_PRETTY_PRINT) . "\n\n" .
        "Produk B:\n" . json_encode($catalogSummary['product_b'], JSON_PRETTY_PRINT) . "\n\n" .
        "Tugas Anda:\n" .
        "1. Berikan verdict analisis berupa perbandingan harga, kualitas, deskripsi, kesesuaian kebutuhan, dan dampak sosial masing-masing produk.\n" .
        "2. Berikan rekomendasi produk mana yang sebaiknya dipilih sesuai dengan skenario/preferensi pembeli (misal: pilih A jika ingin hemat/mencari produk X, pilih B jika ingin Y).\n" .
        "3. Tulis jawaban dalam bahasa Indonesia yang ramah, profesional, dan ringkas (maksimal 3-4 kalimat untuk verdict dan 1-2 kalimat untuk rekomendasi).\n";

      $schema = [
        'type' => 'object',
        'properties' => [
          'verdict' => ['type' => 'string', 'description' => 'Perbandingan detail produk A dan B, termasuk harga dan kegunaannya.'],
          'recommendation' => ['type' => 'string', 'description' => 'Rekomendasi spesifik mana yang harus dipilih berdasarkan preferensi pembeli.'],
        ],
        'required' => ['verdict', 'recommendation'],
      ];

      $system = 'Kamu adalah AI Product Analyst spesialis kurasi produk UMKM lokal Grownesia. Tugasmu membantu pembeli memilih antara dua produk alternatif dengan seimbang, objektif, dan informatif.';

      try {
        $aiResponse = $this->gemini->generateJson($prompt, $schema, $system);

        if (is_array($aiResponse) && isset($aiResponse['verdict']) && isset($aiResponse['recommendation'])) {
          return [
            'success' => true,
            'verdict' => $aiResponse['verdict'],
            'recommendation' => $aiResponse['recommendation'],
          ];
        }
      } catch (Throwable $e) {
        Log::warning('ShoppingAssistantService: error comparing products', ['msg' => $e->getMessage()]);
      }

      // Offline fallback
      return $this->fallbackComparison($productA, $productB);
    });
  }

  private function fallbackComparison(Product $p1, Product $p2): array
  {
    $price1Fmt = "Rp" . number_format((float) $p1->price, 0, ',', '.');
    $price2Fmt = "Rp" . number_format((float) $p2->price, 0, ',', '.');

    $verdict = "Perbandingan antara {$p1->name} ({$price1Fmt}) dari {$p1->business?->name} dan {$p2->name} ({$price2Fmt}) dari {$p2->business?->name}. ";
    if ($p1->price < $p2->price) {
      $verdict .= "{$p1->name} menawarkan pilihan yang lebih ekonomis.";
    } elseif ($p1->price > $p2->price) {
      $verdict .= "{$p2->name} menawarkan pilihan yang lebih ekonomis.";
    } else {
      $verdict .= "Kedua produk memiliki harga yang sama.";
    }

    $recommendation = $p1->price < $p2->price
      ? "Pilih {$p1->name} jika Anda mengutamakan budget rendah, atau pilih {$p2->name} jika fiturnya lebih sesuai."
      : "Pilih {$p2->name} jika Anda mengutamakan budget rendah, atau pilih {$p1->name} jika fiturnya lebih sesuai.";

    return [
      'success' => true,
      'verdict' => $verdict,
      'recommendation' => $recommendation,
    ];
  }
}
