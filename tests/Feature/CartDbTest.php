<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartDbTest extends TestCase
{
  use RefreshDatabase;

  public function test_guest_can_sync_and_load_cart_via_session()
  {
    $this->startSession();

    $product = Product::factory()->create();

    // 1. Post to /cart/sync
    $response = $this->postJson('/cart/sync', [
      'cart' => [
        [
          'product' => ['id' => $product->id],
          'qty' => 3
        ]
      ]
    ]);
    $response->assertStatus(200)->assertJson(['success' => true]);

    // 2. Assert database matches
    $this->assertDatabaseCount('cart_items', 1);
    $cartItem = CartItem::first();
    $this->assertEquals($product->id, $cartItem->product_id);
    $this->assertEquals(3, $cartItem->qty);

    // 3. Query /cart using the same session
    $loadResponse = $this->getJson('/cart');
    $loadResponse->assertStatus(200)
      ->assertJsonFragment([
        'qty' => 3
      ]);
  }

  public function test_authenticated_user_can_sync_and_load_cart()
  {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    // Sync cart
    $response = $this->actingAs($user)
      ->postJson('/cart/sync', [
        'cart' => [
          [
            'product' => ['id' => $product->id],
            'qty' => 2
          ]
        ]
      ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('cart_items', [
      'product_id' => $product->id,
      'qty' => 2,
      'user_id' => $user->id
    ]);

    // Load cart
    $response = $this->actingAs($user)->getJson('/cart');
    $response->assertStatus(200)
      ->assertJsonFragment([
        'qty' => 2
      ]);
  }
}
