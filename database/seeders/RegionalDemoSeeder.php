<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Customer;
use App\Models\GovProgram;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RegionalDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo Government (institusional — dibuat manual, bukan self-register)
        $gov = User::factory()->create([
            'name' => 'Dinas Koperasi & UMKM Jabar',
            'email' => 'gov@grownesia.test',
            'password' => 'password',
            'role' => 'government',
        ]);

        // Bisnis regional tambahan di kota berbeda agar data agregat bermakna
        $regionDefs = [
            ['Batik Priangan Asli', 'Fashion', 'Bandung', [
                ['Batik Tulis Motif Mega Mendung', 250000, 150000],
                ['Batik Cap Priangan', 150000, 90000],
                ['Selendang Batik Sutra', 180000, 110000],
            ]],
            ['Kerajinan Bambu Tasik', 'Kerajinan', 'Tasikmalaya', [
                ['Tas Anyaman Bambu Premium', 95000, 55000],
                ['Tampah Hias Dekorasi', 45000, 25000],
                ['Lampu Gantung Bambu', 135000, 80000],
            ]],
            ['Dapur Rendang Garut', 'Kuliner', 'Garut', [
                ['Rendang Daging 250g', 90000, 58000],
                ['Dodol Garut Original', 35000, 20000],
                ['Abon Sapi Premium', 65000, 40000],
            ]],
            ['Kopi Puncak Cianjur', 'Kuliner', 'Cianjur', [
                ['Kopi Arabika Cianjur 200g', 85000, 50000],
                ['Kopi Honey Process 200g', 98000, 60000],
            ]],
        ];

        foreach ($regionDefs as $index => [$name, $category, $city, $productDefs]) {
            $owner = User::factory()->create([
                'name' => 'Pemilik '.$name,
                'email' => 'umkm'.($index + 2).'@grownesia.test',
                'password' => 'password',
                'role' => 'business',
            ]);

            $business = Business::factory()->create([
                'user_id' => $owner->id,
                'name' => $name,
                'slug' => Str::slug($name),
                'category' => $category,
                'city' => $city,
                'monthly_fixed_cost' => mt_rand(2, 5) * 1000000,
            ]);

            $products = collect($productDefs)->map(fn (array $d) => Product::create([
                'business_id' => $business->id,
                'name' => $d[0],
                'slug' => Str::slug($d[0]).'-'.Str::lower(Str::random(4)),
                'sku' => strtoupper(Str::random(8)),
                'category' => $category,
                'description' => 'Produk unggulan '.$name.' dari '.$city.'.',
                'price' => $d[1],
                'cost_price' => $d[2],
                'stock' => mt_rand(20, 80),
                'min_stock' => 5,
                'status' => 'active',
            ]));

            $customers = Customer::factory()->count(15)->create(['business_id' => $business->id]);

            // ~55-75 order per bisnis, 90 hari; intensitas beda per bisnis → hotspot bervariasi
            $intensity = [1.0, 0.7, 0.85, 0.5][$index];
            $start = Carbon::today()->subDays(89);

            for ($day = 0; $day < 90; $day++) {
                $date = $start->copy()->addDays($day);
                $orderCount = (int) round($intensity * (mt_rand(30, 110) / 100));

                for ($i = 0; $i < $orderCount; $i++) {
                    $picked = $products->random(min(mt_rand(1, 2), $products->count()));
                    $orderedAt = $date->copy()->setTime(mt_rand(8, 20), mt_rand(0, 59));

                    $order = Order::create([
                        'business_id' => $business->id,
                        'customer_id' => $customers->random()->id,
                        'order_number' => 'GRW-'.$orderedAt->format('ymd').'-'.strtoupper(Str::random(5)),
                        'status' => mt_rand(1, 10) > 1 ? 'completed' : 'cancelled',
                        'channel' => fake()->randomElement(['manual', 'whatsapp', 'marketplace']),
                        'total' => 0,
                        'total_cost' => 0,
                        'ordered_at' => $orderedAt,
                    ]);

                    $total = 0;
                    $totalCost = 0;
                    foreach ($picked as $product) {
                        $qty = mt_rand(1, 3);
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
        }

        // Contoh program dinas
        GovProgram::create([
            'user_id' => $gov->id,
            'title' => 'Pelatihan Pemasaran Digital UMKM Kuliner',
            'type' => 'pelatihan',
            'sector' => 'Kuliner',
            'city' => 'Sukabumi',
            'description' => 'Pelatihan optimasi marketplace dan media sosial untuk 50 UMKM kuliner terpilih.',
            'status' => 'aktif',
            'starts_at' => Carbon::today()->addDays(7),
            'ends_at' => Carbon::today()->addDays(9),
        ]);

        GovProgram::create([
            'user_id' => $gov->id,
            'title' => 'Pameran Produk Unggulan Jawa Barat',
            'type' => 'pameran',
            'sector' => null,
            'city' => 'Bandung',
            'description' => 'Pameran tahunan produk UMKM se-Jawa Barat di Trans Studio Mall.',
            'status' => 'draft',
            'starts_at' => Carbon::today()->addMonth(),
            'ends_at' => Carbon::today()->addMonth()->addDays(3),
        ]);
    }
}
