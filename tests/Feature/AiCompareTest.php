<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiCompareTest extends TestCase
{
  use RefreshDatabase;

  private User $user;

  protected function setUp(): void
  {
    parent::setUp();

    $this->user = User::factory()->create(['role' => 'user']);
  }

  public function test_guest_cannot_access_ai_compare(): void
  {
    $response = $this->postJson('/user/ai/compare', [
      'product1_id' => 1,
      'product2_id' => 2,
    ]);

    $response->assertStatus(401);
  }

  public function test_user_can_compare_products_with_ai(): void
  {
    $business = Business::factory()->create();
    $product1 = Product::factory()->create([
      'business_id' => $business->id,
      'price' => 50000,
      'name' => 'Kopi Robusta',
    ]);
    $product2 = Product::factory()->create([
      'business_id' => $business->id,
      'price' => 75000,
      'name' => 'Kopi Arabika',
    ]);

    $response = $this->actingAs($this->user)->postJson('/user/ai/compare', [
      'product1_id' => $product1->id,
      'product2_id' => $product2->id,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'success',
      'verdict',
      'recommendation',
    ]);

    $this->assertTrue($response->json('success'));
    $this->assertNotEmpty($response->json('verdict'));
    $this->assertNotEmpty($response->json('recommendation'));
  }
}
