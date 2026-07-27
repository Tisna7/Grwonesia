<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Business $business;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'business']);
        $this->business = Business::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_order_creation_computes_totals_and_decrements_stock(): void
    {
        $product = Product::factory()->create([
            'business_id' => $this->business->id,
            'price' => 50000,
            'cost_price' => 30000,
            'stock' => 10,
        ]);

        $response = $this->actingAs($this->user)->post('/business/orders', [
            'customer_name' => 'Budi Pelanggan',
            'customer_phone' => '6281111111111',
            'status' => 'paid',
            'channel' => 'manual',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 3],
            ],
        ]);

        $order = Order::first();
        $this->assertNotNull($order);
        $response->assertRedirect(route('business.orders.show', $order));

        $this->assertSame(150000.0, (float) $order->total);
        $this->assertSame(90000.0, (float) $order->total_cost);
        $this->assertSame(7, $product->fresh()->stock);
        $this->assertSame('Budi Pelanggan', $order->customer->name);

        // Konfirmasi WA tercatat sebagai mock (driver log)
        $this->assertDatabaseHas('wa_messages', [
            'order_id' => $order->id,
            'type' => 'order_confirmation',
            'status' => 'mocked',
        ]);
    }

    public function test_order_rejected_when_stock_insufficient(): void
    {
        $product = Product::factory()->create([
            'business_id' => $this->business->id,
            'stock' => 2,
        ]);

        $response = $this->actingAs($this->user)->post('/business/orders', [
            'customer_name' => 'Budi',
            'status' => 'paid',
            'channel' => 'manual',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5],
            ],
        ]);

        $response->assertSessionHasErrors('items');
        $this->assertSame(0, Order::count());
        $this->assertSame(2, $product->fresh()->stock);
    }

    public function test_cannot_order_products_from_another_business(): void
    {
        $foreignProduct = Product::factory()->create(['stock' => 10]);

        $this->actingAs($this->user)->post('/business/orders', [
            'customer_name' => 'Budi',
            'status' => 'paid',
            'channel' => 'manual',
            'items' => [
                ['product_id' => $foreignProduct->id, 'quantity' => 1],
            ],
        ])->assertNotFound();

        $this->assertSame(0, Order::count());
    }
}
