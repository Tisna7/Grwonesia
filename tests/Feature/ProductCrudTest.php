<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
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

    public function test_business_can_create_product(): void
    {
        $response = $this->actingAs($this->user)->post('/business/products', [
            'name' => 'Kopi Testing 250g',
            'category' => 'Minuman',
            'price' => 50000,
            'cost_price' => 30000,
            'stock' => 20,
            'min_stock' => 5,
            'status' => 'active',
        ]);

        $product = Product::where('name', 'Kopi Testing 250g')->first();
        $this->assertNotNull($product);
        $this->assertSame($this->business->id, $product->business_id);
        $response->assertRedirect(route('business.products.edit', $product));
    }

    public function test_business_can_update_own_product(): void
    {
        $product = Product::factory()->create(['business_id' => $this->business->id]);

        $this->actingAs($this->user)->put("/business/products/{$product->id}", [
            'name' => 'Nama Baru',
            'category' => 'Makanan',
            'price' => 75000,
            'cost_price' => 40000,
            'stock' => 10,
            'min_stock' => 3,
            'status' => 'active',
        ])->assertRedirect();

        $this->assertSame('Nama Baru', $product->fresh()->name);
    }

    public function test_business_cannot_touch_another_businesses_product(): void
    {
        $otherProduct = Product::factory()->create(); // bisnis lain via factory

        $this->actingAs($this->user)
            ->get("/business/products/{$otherProduct->id}/edit")
            ->assertNotFound();

        $this->actingAs($this->user)
            ->delete("/business/products/{$otherProduct->id}")
            ->assertNotFound();

        $this->assertNotNull($otherProduct->fresh());
    }

    public function test_business_can_delete_own_product(): void
    {
        $product = Product::factory()->create(['business_id' => $this->business->id]);

        $this->actingAs($this->user)
            ->delete("/business/products/{$product->id}")
            ->assertRedirect(route('business.products.index'));

        $this->assertNull($product->fresh());
    }
}
