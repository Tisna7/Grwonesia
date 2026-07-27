<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\BusinessMetricsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthScoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_score_is_zero_band_for_empty_business(): void
    {
        $business = Business::factory()->create();

        $health = app(BusinessMetricsService::class)->healthScore($business);

        $this->assertLessThan(40, $health['score']);
        $this->assertSame('Perlu Perhatian', $health['label']);
    }

    public function test_healthy_business_scores_high(): void
    {
        $business = Business::factory()->create();
        $product = Product::factory()->create([
            'business_id' => $business->id,
            'price' => 100000,
            'cost_price' => 50000, // margin 50%
            'stock' => 100,
            'min_stock' => 5,
        ]);
        $customers = Customer::factory()->count(20)->create(['business_id' => $business->id]);

        // Order konsisten setiap hari selama 60 hari, tren naik
        for ($day = 0; $day < 60; $day++) {
            $date = now()->subDays(59 - $day)->setTime(12, 0);
            $qty = $day < 30 ? 1 : 2; // 30 hari terakhir omzet dobel

            $order = Order::create([
                'business_id' => $business->id,
                'customer_id' => $customers->random()->id,
                'order_number' => 'TST-'.$day,
                'status' => 'completed',
                'channel' => 'manual',
                'total' => 100000 * $qty,
                'total_cost' => 50000 * $qty,
                'ordered_at' => $date,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $qty,
                'unit_price' => 100000,
                'unit_cost' => 50000,
                'subtotal' => 100000 * $qty,
            ]);
        }

        $health = app(BusinessMetricsService::class)->healthScore($business);

        $this->assertGreaterThanOrEqual(85, $health['score']);
        $this->assertSame('Sangat Baik', $health['label']);
    }
}
