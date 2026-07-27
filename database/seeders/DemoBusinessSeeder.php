<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\WaMessage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DemoBusinessSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Demo UMKM',
            'email' => 'demo@grownesia.test',
            'password' => 'password',
            'role' => 'business',
            'phone' => '6281234567890',
        ]);

        $business = Business::factory()->create([
            'user_id' => $user->id,
            'name' => 'Kopi & Keripik Nusantara',
            'slug' => 'kopi-keripik-nusantara',
            'category' => 'Kuliner',
            'description' => 'UMKM olahan kopi lokal dan camilan khas Jawa Barat, memberdayakan petani dan pekerja desa.',
            'city' => 'Sukabumi',
            'monthly_fixed_cost' => 4500000,
        ]);

        // [nama, kategori, harga, hpp, stok, tren] — tren: rising | stable | declining
        $productDefs = [
            ['Kopi Gula Aren 250g', 'Minuman', 75000, 42000, 85, 'rising'],
            ['Kopi Klepon 250g', 'Minuman', 78000, 45000, 60, 'rising'],
            ['Kopi Robusta Klasik 500g', 'Minuman', 95000, 60000, 40, 'stable'],
            ['Keripik Pedas Level 5', 'Makanan', 45000, 25000, 4, 'rising'],
            ['Keripik Singkong Original', 'Makanan', 35000, 20000, 90, 'stable'],
            ['Sambal Bawang Premium', 'Makanan', 40000, 22000, 55, 'stable'],
            ['Madu Hutan Asli 350ml', 'Minuman', 120000, 78000, 30, 'stable'],
            ['Teh Herbal Rosella', 'Minuman', 38000, 20000, 3, 'declining'],
            ['Rendang Kemasan 200g', 'Makanan', 85000, 55000, 45, 'stable'],
            ['Keripik Talas Balado', 'Makanan', 42000, 24000, 70, 'declining'],
            ['Kopi Drip Bag Isi 10', 'Minuman', 65000, 38000, 50, 'rising'],
            ['Gula Aren Cair 500ml', 'Bahan', 55000, 32000, 65, 'stable'],
        ];

        $products = collect($productDefs)->map(fn (array $d) => Product::create([
            'business_id' => $business->id,
            'name' => $d[0],
            'slug' => Str::slug($d[0]),
            'sku' => strtoupper(Str::random(8)),
            'category' => $d[1],
            'description' => 'Produk '.$d[0].' asli buatan UMKM lokal Sukabumi dengan bahan pilihan berkualitas.',
            'price' => $d[2],
            'cost_price' => $d[3],
            'stock' => $d[4],
            'min_stock' => 5,
            'status' => 'active',
        ])->setAttribute('trend', $d[5]));

        $customers = Customer::factory()->count(40)->create(['business_id' => $business->id]);

        // ~250 order tersebar 90 hari: tren naik ringan + bobot akhir pekan
        $start = Carbon::today()->subDays(89);
        for ($day = 0; $day < 90; $day++) {
            $date = $start->copy()->addDays($day);
            $progress = $day / 89; // 0 → 1, tren naik
            $base = 1.6 + $progress * 1.8;
            $weekendBoost = in_array($date->dayOfWeek, [0, 5, 6]) ? 1.4 : 1.0;
            $orderCount = (int) round($base * $weekendBoost * (mt_rand(70, 130) / 100));

            for ($i = 0; $i < $orderCount; $i++) {
                $picked = $products->filter(function ($p) use ($progress) {
                    $w = match ($p->trend) {
                        'rising' => 0.25 + $progress * 0.55,
                        'declining' => 0.65 - $progress * 0.55,
                        default => 0.40,
                    };

                    return mt_rand() / mt_getrandmax() < $w;
                })->shuffle()->take(mt_rand(1, 3));

                if ($picked->isEmpty()) {
                    $picked = collect([$products->random()]);
                }

                $orderedAt = $date->copy()->setTime(mt_rand(8, 20), mt_rand(0, 59));
                $isRecent = $date->greaterThanOrEqualTo(Carbon::today()->subDays(7));
                $status = $isRecent
                    ? fake()->randomElement(['pending', 'paid', 'shipped', 'completed', 'completed'])
                    : fake()->randomElement(['completed', 'completed', 'completed', 'completed', 'cancelled']);

                $order = Order::create([
                    'business_id' => $business->id,
                    'customer_id' => $customers->random()->id,
                    'order_number' => 'GRW-'.$orderedAt->format('ymd').'-'.strtoupper(Str::random(5)),
                    'status' => $status,
                    'channel' => fake()->randomElement(['manual', 'whatsapp', 'whatsapp', 'marketplace']),
                    'total' => 0,
                    'total_cost' => 0,
                    'ordered_at' => $orderedAt,
                ]);

                $total = 0;
                $totalCost = 0;
                foreach ($picked as $product) {
                    $qty = mt_rand(1, 4);
                    $subtotal = $product->price * $qty;
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $qty,
                        'unit_price' => $product->price,
                        'unit_cost' => $product->cost_price,
                        'subtotal' => $subtotal,
                    ]);
                    $total += $subtotal;
                    $totalCost += $product->cost_price * $qty;
                }

                $order->update(['total' => $total, 'total_cost' => $totalCost]);
            }
        }

        // Contoh pesan WA mocked agar halaman WhatsApp tidak kosong
        foreach ($customers->take(5) as $customer) {
            WaMessage::create([
                'business_id' => $business->id,
                'customer_id' => $customer->id,
                'to_number' => $customer->phone,
                'type' => 'broadcast',
                'body' => 'Halo kak! Promo akhir pekan: diskon 10% untuk semua varian kopi. Yuk order sekarang 🎉',
                'status' => 'mocked',
                'sent_at' => now()->subDays(2),
            ]);
        }

        // User consumer — bukti middleware role menolak akses dashboard bisnis
        User::factory()->create([
            'name' => 'Konsumen Biasa',
            'email' => 'consumer@grownesia.test',
            'password' => 'password',
            'role' => 'consumer',
        ]);
    }
}
