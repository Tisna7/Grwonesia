<?php
// FILE: database/seeders/UserDashboardSeeder.php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserDashboardSeeder extends Seeder
{
  public function run(): void
  {
    // 1. Get or create Budi Santoso (user)
    $user = User::where('email', 'budi@grownesia.id')->first();
    if ($user) {
      $user->update([
        'phone' => '0812-3456-7890',
        'address' => 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan',
      ]);
    } else {
      $user = User::create([
        'name' => 'Budi Santoso',
        'email' => 'budi@grownesia.id',
        'role' => 'user',
        'password' => bcrypt('password'),
        'phone' => '0812-3456-7890',
        'address' => 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan',
      ]);
    }

    // 2. Ensure we have the demo business for products
    $business = Business::first() ?: Business::create([
      'user_id' => User::where('role', 'business')->first()?->id ?: User::create([
        'name' => 'Mitra Bisnis UMKM',
        'email' => 'mitra@grownesia.id',
        'role' => 'business',
        'password' => bcrypt('password')
      ])->id,
      'name' => 'Kopi Wong Kito & Mitra',
      'slug' => 'kopi-wong-kito-mitra',
      'category' => 'Kuliner',
      'description' => 'UMKM Kopi and local crafts.',
      'city' => 'Palembang',
    ]);

    // 3. Define and seed the 6 showcase products
    $showcaseProducts = [
      [
        'name' => 'Kopi Gula Aren Nusantara Premium 500ml',
        'category' => 'kopi',
        'price' => 45000,
        'cost_price' => 25000,
        'stock' => 100,
        'description' => 'Kopi biji robusta dipadu dengan gula aren murni khas Sumatra. Aroma harum, rasa legit dan pas di lidah.',
        'photo_path' => 'images/products/kopi_gula_aren.webp',
        'reviews' => [
          [
            'user_name' => 'Siti Rahmawati',
            'rating' => 5,
            'comment' => 'Kopi gula aren ini rasanya mantap banget! Manisnya alami dari gula aren asli Palembang dan seneng banget dapet sertifikat pemberdayaan 2 petani lokal.',
          ]
        ]
      ],
      [
        'name' => 'Batik Tulis Mega Mendung Signature Solo',
        'category' => 'batik',
        'price' => 185000,
        'cost_price' => 11000,
        'stock' => 50,
        'description' => 'Batik tulis buatan tangan pewarna alami indigo khas Solo. Kain katun primissima super lembut dan tahan lama.',
        'photo_path' => 'images/products/batik_solo.webp',
        'reviews' => [
          [
            'user_name' => 'Rian Hidayat',
            'rating' => 5,
            'comment' => 'Kain tenun ikatnya sangat halus dan motifnya sungguh eksklusif. Kemasan dilapisi besek ramah lingkungan. Sangat memuaskan!',
          ]
        ]
      ],
      [
        'name' => 'Tas Anyaman Pandan Organik Modern',
        'category' => 'kerajinan',
        'price' => 65000,
        'cost_price' => 35000,
        'stock' => 75,
        'description' => 'Tas tote bag ramah lingkungan berbahan serat daun pandan alami dengan pegangan kulit sintestis premium.',
        'photo_path' => 'images/products/tas_anyaman.webp',
        'reviews' => [
          [
            'user_name' => 'Dewi Lestari',
            'rating' => 4,
            'comment' => 'Tas anyaman serat pandannya sangat kokoh, muat banyak barang. Pengiriman cepat langsung dari pengrajin Kebumen.',
          ]
        ]
      ],
      [
        'name' => 'Paket Keripik Cassava Coffee Crunch 250g',
        'category' => 'makanan',
        'price' => 35000,
        'cost_price' => 18000,
        'stock' => 150,
        'description' => 'Keripik singkong renyah bertabur bubuk kopi gurih manis khas Boyolali. Cocok untuk teman ngopi & cemilan harian.',
        'photo_path' => 'images/products/keripik_kopi.webp',
        'reviews' => []
      ],
      [
        'name' => 'Kopi Klepon Heritage Reserve 250g',
        'category' => 'kopi',
        'price' => 60000,
        'cost_price' => 32000,
        'stock' => 80,
        'description' => 'Perpaduan biji kopi arabika Bromo dengan sensasi aroma pandan dan gurihnya kelapa khas kue klepon tradisional.',
        'photo_path' => 'images/products/kopi_gula_aren.webp',
        'reviews' => []
      ],
      [
        'name' => 'Kain Tenun Ikat NTT Handmade Royal Violet',
        'category' => 'batik',
        'price' => 195000,
        'cost_price' => 120000,
        'stock' => 30,
        'description' => 'Kain tenun ikat tradisional buatan tangan asli Kupang NTT. Motif etnik yang anggun dengan benang sutra tenun.',
        'photo_path' => 'images/products/batik_solo.webp',
        'reviews' => []
      ]
    ];

    foreach ($showcaseProducts as $pData) {
      $product = Product::where('name', $pData['name'])->first();
      if (!$product) {
        $product = Product::create([
          'business_id' => $business->id,
          'name' => $pData['name'],
          'slug' => Str::slug($pData['name']),
          'sku' => strtoupper(Str::random(8)),
          'category' => $pData['category'],
          'description' => $pData['description'],
          'price' => $pData['price'],
          'cost_price' => $pData['cost_price'],
          'stock' => $pData['stock'],
          'min_stock' => 5,
          'status' => 'active',
          'photo_path' => $pData['photo_path'],
        ]);
      }

      // Seed reviews for this product
      foreach ($pData['reviews'] as $revData) {
        ProductReview::firstOrCreate(
          [
            'product_id' => $product->id,
            'user_name' => $revData['user_name']
          ],
          [
            'rating' => $revData['rating'],
            'comment' => $revData['comment'],
            'verified' => true
          ]
        );
      }
    }

    // 4. Seed user notifications
    $notifications = [
      [
        'title' => 'Pesanan Dikirim',
        'desc' => 'Pesanan #GRW-2026-7812 sedang dibawa kurir ke lokasi Anda.',
        'time_label' => '10 menit yang lalu',
      ],
      [
        'title' => 'Voucher Subsidi Ongkir',
        'desc' => 'Diskon ongkos kirim 100% aktif untuk produk UMKM mitra.',
        'time_label' => '2 jam yang lalu',
      ],
      [
        'title' => 'AI Personal Shopper',
        'desc' => 'AI menemukan 2 kado batik yang cocok untuk anggaran Anda.',
        'time_label' => '1 hari yang lalu',
      ]
    ];

    foreach ($notifications as $notif) {
      UserNotification::firstOrCreate(
        [
          'user_id' => $user->id,
          'title' => $notif['title'],
          'desc' => $notif['desc']
        ],
        [
          'time_label' => $notif['time_label'],
          'is_read' => false
        ]
      );
    }
  }
}
