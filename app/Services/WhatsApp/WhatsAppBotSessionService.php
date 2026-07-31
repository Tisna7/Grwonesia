<?php

namespace App\Services\WhatsApp;

use App\Models\Business;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class WhatsAppBotSessionService
{
  public function __construct(private readonly WhatsAppGateway $gateway)
  {
  }

  public function handleMessage(string $fromPhone, string $type, string $body, ?string $tempPath = null): string
  {
    $phone = preg_replace('/\D/', '', $fromPhone);

    // Find user by phone in database (normalizing for starting with 62 / 0 / +)
    $user = User::where('phone', $phone)
      ->orWhere('phone', 'like', '%' . substr($phone, -9))
      ->first();

    $business = null;
    if ($user && $user->isBusiness()) {
      $business = $user->business;
    }

    $sessionKey = "wa_session_{$phone}";
    $session = Cache::get($sessionKey, [
      'state' => 'idle',
      'data' => [],
    ]);

    $bodyLower = trim(strtolower($body));

    // Let user cancel transaction at any point
    if ($bodyLower === 'batal') {
      Cache::forget($sessionKey);
      $this->gateway->send($fromPhone, "Aksi dibatalkan. Kembali ke menu utama. Ketik 'halo' atau 'menu' untuk bantuan.");
      return 'Action cancelled';
    }

    // Logic check: Seller or Buyer
    if ($business) {
      return $this->handleSellerFlow($fromPhone, $phone, $business, $session, $sessionKey, $type, $body, $tempPath);
    }

    return $this->handleBuyerFlow($fromPhone, $phone, $user, $session, $sessionKey, $type, $body);
  }

  private function handleSellerFlow(
    string $fromPhone,
    string $phone,
    Business $business,
    array $session,
    string $sessionKey,
    string $type,
    string $body,
    ?string $tempPath
  ): string {
    $state = $session['state'];
    $bodyTrim = trim($body);
    $bodyLower = strtolower($bodyTrim);

    if ($state === 'idle') {
      if (Str::contains($bodyLower, 'tambah produk') || $bodyLower === '/tambah') {
        $session['state'] = 'awaiting_name';
        Cache::put($sessionKey, $session, now()->addMinutes(15));

        $this->gateway->send($fromPhone, "🛠 *Tambah Produk Baru*\n\nSilakan ketik *Nama Produk* yang ingin dijual:");
        return 'Seller: Awaiting Name';
      }

      $msg = "Halo Penjual dari *{$business->name}*! 👋\n\n" .
        "Anda dapat mengelola produk Anda via WhatsApp Bot ini.\n\n" .
        "Ketik *'tambah produk'* atau */tambah* untuk mulai menambahkan produk secara langsung.\n" .
        "Ketik *'batal'* kapan saja untuk membatalkan proses.";
      $this->gateway->send($fromPhone, $msg);
      return 'Seller: Greeting dialog';
    }

    if ($state === 'awaiting_name') {
      if (empty($bodyTrim)) {
        $this->gateway->send($phone, "Nama produk tidak boleh kosong. Silakan ketik nama produk:");
        return 'Seller: Empty Name Refused';
      }

      $session['data']['name'] = $bodyTrim;
      $session['state'] = 'awaiting_price';
      Cache::put($sessionKey, $session, now()->addMinutes(15));

      $this->gateway->send($fromPhone, "Nama produk disimpan: *{$bodyTrim}*\n\nBerapa *Harga Produk*? (Ketik angkanya saja, misal: 45000)");
      return 'Seller: Awaiting Price';
    }

    if ($state === 'awaiting_price') {
      $price = preg_replace('/[^\d]/', '', $bodyTrim);
      if (empty($price) || (int) $price <= 0) {
        $this->gateway->send($fromPhone, "Harga tidak valid. Silakan ketik angka saja, contoh: 50000:");
        return 'Seller: Invalid Price Refused';
      }

      $session['data']['price'] = (int) $price;
      $session['state'] = 'awaiting_description';
      Cache::put($sessionKey, $session, now()->addMinutes(15));

      $this->gateway->send($fromPhone, "Harga disimpan: *Rp " . number_format($price, 0, ',', '.') . "*\n\nSilakan masukkan *Deskripsi Singkat* produk:");
      return 'Seller: Awaiting Description';
    }

    if ($state === 'awaiting_description') {
      $session['data']['description'] = $bodyTrim;
      $session['state'] = 'awaiting_photo';
      Cache::put($sessionKey, $session, now()->addMinutes(15));

      $this->gateway->send($fromPhone, "Deskripsi disimpan.\n\nSekarang silakan kirimkan *Foto Produk* Anda, atau ketik *'skip'* untuk melewati:");
      return 'Seller: Awaiting Photo';
    }

    if ($state === 'awaiting_photo') {
      $photoPath = null;

      if ($type === 'image' && !empty($tempPath) && file_exists($tempPath)) {
        $filename = basename($tempPath);
        $destination = 'products/' . $filename;

        $storageDir = storage_path('app/public/products');
        if (!file_exists($storageDir)) {
          mkdir($storageDir, 0755, true);
        }

        copy($tempPath, $storageDir . '/' . $filename);
        $photoPath = $destination;

        @unlink($tempPath);
      } elseif ($bodyLower !== 'skip') {
        $this->gateway->send($fromPhone, "Harap unggah / kirim foto produk, atau ketik *'skip'* untuk melewati:");
        return 'Seller: Awaiting photo attachment';
      }

      // Create actual Product in DB
      $productData = $session['data'];
      $appUrl = config('app.url', 'http://localhost');
      $slug = Str::slug($productData['name']) . '-' . rand(100, 999);

      $product = Product::create([
        'business_id' => $business->id,
        'name' => $productData['name'],
        'slug' => $slug,
        'sku' => 'WA-' . strtoupper(Str::random(6)),
        'category' => 'Lainnya',
        'description' => $productData['description'],
        'price' => $productData['price'],
        'cost_price' => $productData['price'] * 0.8,
        'stock' => 100,
        'min_stock' => 5,
        'photo_path' => $photoPath,
        'status' => 'active',
      ]);

      Cache::forget($sessionKey);

      $msg = "🎉 *Produk Berhasil Ditambahkan!*\n\n" .
        "Nama: *{$product->name}*\n" .
        "Harga: *Rp " . number_format($product->price, 0, ',', '.') . "*\n" .
        "SKU: *{$product->sku}*\n\n" .
        "Produk Anda telah aktif dan dapat dibeli di katalog web Grownesia!\n" .
        "Lihat Dashboard: {$appUrl}/dashboard";

      $this->gateway->send($fromPhone, $msg);
      return 'Seller: Product created';
    }

    return 'Seller: Unknown State';
  }

  private function handleBuyerFlow(
    string $fromPhone,
    string $phone,
    ?User $user,
    array $session,
    string $sessionKey,
    string $type,
    string $body
  ): string {
    $bodyTrim = trim($body);
    $bodyLower = strtolower($bodyTrim);
    $appUrl = config('app.url', 'http://localhost');

    // Beli handler: "beli kopi" or "beli 1"
    if (Str::startsWith($bodyLower, 'beli ') || $bodyLower === 'beli') {
      $queryPart = trim(substr($bodyTrim, 4));
      if (empty($queryPart)) {
        $this->gateway->send($fromPhone, "Ketik *'beli [nama produk]'* atau *'beli [ID]'*. Contoh: *beli 1*");
        return 'Buyer: Empty Buy Query';
      }

      // Resolve numeric lists index from last search results
      if (is_numeric($queryPart)) {
        $indexNum = (int) $queryPart;
        $lastSearch = $session['last_search_results'] ?? [];
        if (isset($lastSearch[$indexNum])) {
          $queryPart = $lastSearch[$indexNum];
        }
      }

      // Find product
      $pQuery = Product::where('status', 'active');
      if (is_numeric($queryPart)) {
        $pQuery->where('id', (int) $queryPart);
      } else {
        $pQuery->where('name', 'like', "%{$queryPart}%");
      }

      $product = $pQuery->first();

      if (!$product) {
        $this->gateway->send($fromPhone, "Maaf, produk *'{$queryPart}'* tidak ditemukan. Ketik *'cari [nama]'* untuk menjelajah.");
        return 'Buyer: Product NotFound';
      }

      if ($user) {
        // Synthesize item directly in cart database
        CartItem::updateOrCreate(
          ['user_id' => $user->id, 'product_id' => $product->id],
          ['qty' => 1]
        );

        $msg = "🛒 *Produk Ditambahkan ke Keranjang!*\n\n" .
          "• *{$product->name}* - Rp " . number_format($product->price, 0, ',', '.') . "\n\n" .
          "Silakan selesaikan pembayaran dan detail kurir melalui tautan singkat berikut:\n" .
          "🔗 *{$appUrl}/dashboard*\n\n" .
          "Terima kasih atas kontribusi Anda mendukung produk lokal! 🇮🇩";
      } else {
        $msg = "🛍 *Tertarik membeli {$product->name}?*\n\n" .
          "Silakan kunjungi dashboard Grownesia untuk mendaftar/masuk dan memproses checkout produk ini:\n" .
          "🔗 *{$appUrl}/dashboard*\n\n" .
          "Atau cari produk lain dengan mengirim pesan contoh: *'cari kopi'*";
      }

      $this->gateway->send($fromPhone, $msg);
      return 'Buyer: Product checkout link sent';
    }

    // Search handler: "cari kopi"
    if (Str::startsWith($bodyLower, 'cari ') || $bodyLower === 'cari') {
      $keyword = trim(substr($bodyTrim, 4));
      if (empty($keyword)) {
        $this->gateway->send($fromPhone, "Ketik *'cari [nama produk]'*. Contoh: *'cari batik'*");
        return 'Buyer: Empty Search';
      }

      $products = Product::where('status', 'active')
        ->where('name', 'like', "%{$keyword}%")
        ->take(5)
        ->get();

      if ($products->isEmpty()) {
        $this->gateway->send($fromPhone, "Maaf, tidak ditemukan produk dengan kata kunci '{$keyword}' di Grownesia. Silakan cari kata kunci lain.");
        return 'Buyer: No products found';
      }

      $searchIds = [];
      $list = "🔍 *Hasil pencarian untuk '{$keyword}':*\n\n";
      foreach ($products as $index => $prod) {
        $itemIndex = $index + 1;
        $searchIds[$itemIndex] = $prod->id;
        $list .= $itemIndex . ". *{$prod->name}* (ID: {$prod->id})\n";
        $list .= "   Harga: Rp " . number_format($prod->price, 0, ',', '.') . "\n";
        $list .= "   Toko: {$prod->business->name}\n\n";
      }
      $list .= "Untuk membeli, ketik: *beli [ID]*\nContoh: *beli {$products->first()->id}*";

      $session['last_search_results'] = $searchIds;
      Cache::put($sessionKey, $session, now()->addMinutes(15));

      $this->gateway->send($fromPhone, $list);
      return 'Buyer: Search results sent';
    }

    // ID handler: "id" or "/id"
    if ($bodyLower === 'id' || $bodyLower === 'myid' || $bodyLower === '/id') {
      $this->gateway->send($fromPhone, "ID WhatsApp Anda: *{$phone}*\n\n(Salin ID di atas dan masukkan ke kolom nomor HP di Profil Seller/Akun Grownesia Anda agar akun WhatsApp Anda terhubung!)");
      return 'Buyer: ID replied';
    }

    // Default greeting details
    $msg = "Selamat Datang di *Grownesia Bot*! 🌿\n\n" .
      "Temukan dan beli produk dari UMKM pilihan langsung lewat WhatsApp.\n\n" .
      "💡 *Menu Perintah:*\n" .
      "1. Ketik *cari [nama]* untuk mencari produk (Contoh: *cari kopi*)\n" .
      "2. Ketik *beli [ID]* untuk membeli (Contoh: *beli 1*)\n" .
      "3. Ketik *id* untuk melihat ID WhatsApp Anda jika menggunakan akun LID.\n\n" .
      "(Untuk mengaitkan data keranjang Anda, pastikan nomor WhatsApp Anda sudah didaftarkan pada profil akun Grownesia Anda!)";

    $this->gateway->send($fromPhone, $msg);
    return 'Buyer: Default greeting';
  }
}
