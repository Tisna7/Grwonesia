<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WhatsAppWebhookTest extends TestCase
{
  use RefreshDatabase;

  private string $token = 'test-token-123';

  protected function setUp(): void
  {
    parent::setUp();

    config([
      'services.whatsapp.url' => 'http://127.0.0.1:3010',
      'services.whatsapp.token' => $this->token,
      'services.whatsapp.driver' => 'baileys',
    ]);

    Storage::fake('public');
  }

  public function test_webhook_requires_valid_bearer_token(): void
  {
    $this->postJson('/whatsapp/webhook', [
      'from' => '62812345678',
      'type' => 'text',
      'body' => 'tambah produk',
    ], [
      'Authorization' => 'Bearer invalid-token',
    ])->assertStatus(401);
  }

  public function test_seller_step_by_step_product_creation_flow(): void
  {
    // Mock gateway call
    Http::fake([
      '127.0.0.1:3010/send' => Http::response(['id' => 'WAMSG123', 'to' => '62812345678']),
    ]);

    $user = User::factory()->create([
      'role' => 'business',
      'phone' => '62812345678',
    ]);
    $business = Business::factory()->create([
      'user_id' => $user->id,
    ]);

    $headers = ['Authorization' => 'Bearer ' . $this->token];

    // Step 1: Send "tambah produk" Command
    $response = $this->postJson('/whatsapp/webhook', [
      'from' => '62812345678',
      'type' => 'text',
      'body' => 'tambah produk',
    ], $headers);

    $response->assertStatus(200);
    $this->assertEquals('Seller: Awaiting Name', $response->json('reply'));
    $this->assertEquals('awaiting_name', Cache::get('wa_session_62812345678')['state']);

    // Step 2: Send Product Name
    $response = $this->postJson('/whatsapp/webhook', [
      'from' => '62812345678',
      'type' => 'text',
      'body' => 'Kopi Gayo Arabica',
    ], $headers);

    $response->assertStatus(200);
    $this->assertEquals('Seller: Awaiting Price', $response->json('reply'));
    $this->assertEquals('Kopi Gayo Arabica', Cache::get('wa_session_62812345678')['data']['name']);
    $this->assertEquals('awaiting_price', Cache::get('wa_session_62812345678')['state']);

    // Step 3: Send Price
    $response = $this->postJson('/whatsapp/webhook', [
      'from' => '62812345678',
      'type' => 'text',
      'body' => 'Rp 85.000',
    ], $headers);

    $response->assertStatus(200);
    $this->assertEquals('Seller: Awaiting Description', $response->json('reply'));
    $this->assertEquals(85000, Cache::get('wa_session_62812345678')['data']['price']);
    $this->assertEquals('awaiting_description', Cache::get('wa_session_62812345678')['state']);

    // Step 4: Send Description
    $response = $this->postJson('/whatsapp/webhook', [
      'from' => '62812345678',
      'type' => 'text',
      'body' => 'Kopi berkualitas dari pegunungan Gayo.',
    ], $headers);

    $response->assertStatus(200);
    $this->assertEquals('Seller: Awaiting Photo', $response->json('reply'));
    $this->assertEquals('Kopi berkualitas dari pegunungan Gayo.', Cache::get('wa_session_62812345678')['data']['description']);
    $this->assertEquals('awaiting_photo', Cache::get('wa_session_62812345678')['state']);

    // Step 5: Skip Photo
    $response = $this->postJson('/whatsapp/webhook', [
      'from' => '62812345678',
      'type' => 'text',
      'body' => 'skip',
    ], $headers);

    $response->assertStatus(200);
    $this->assertEquals('Seller: Product created', $response->json('reply'));
    $this->assertNull(Cache::get('wa_session_62812345678'));

    // Assert Product was actually made in DB
    $this->assertDatabaseHas('products', [
      'business_id' => $business->id,
      'name' => 'Kopi Gayo Arabica',
      'price' => 85000,
      'description' => 'Kopi berkualitas dari pegunungan Gayo.',
      'status' => 'active',
    ]);

    Http::assertSentCount(5);
  }

  public function test_buyer_flow_search_and_add_to_cart(): void
  {
    Http::fake([
      '127.0.0.1:3010/send' => Http::response(['id' => 'WAMSG124', 'to' => '628999999']),
    ]);

    $headers = ['Authorization' => 'Bearer ' . $this->token];

    $businessOwner = User::factory()->create(['role' => 'business']);
    $business = Business::factory()->create(['user_id' => $businessOwner->id]);

    $product1 = Product::create([
      'id' => 991,
      'business_id' => $business->id,
      'name' => 'Baju Batik Halus',
      'slug' => 'baju-batik-halus',
      'sku' => 'BTK1',
      'category' => 'batik',
      'description' => 'Baju batik pria katun prima',
      'price' => 150000.00,
      'stock' => 10,
      'status' => 'active',
    ]);

    $buyer = User::factory()->create([
      'role' => 'user',
      'phone' => '628999999',
    ]);

    // 1. Test search command "cari batik"
    $response = $this->postJson('/whatsapp/webhook', [
      'from' => '628999999',
      'type' => 'text',
      'body' => 'cari batik',
    ], $headers);

    $response->assertStatus(200);
    $this->assertEquals('Buyer: Search results sent', $response->json('reply'));

    // Assert that the index search mapping cache is saved
    $session = Cache::get('wa_session_628999999');
    $this->assertNotNull($session);
    $this->assertArrayHasKey('last_search_results', $session);
    $this->assertEquals($product1->id, $session['last_search_results'][1]);

    // 2. Test buy command "beli 1" (which should map to $product1->id index 1)
    $response = $this->postJson('/whatsapp/webhook', [
      'from' => '628999999',
      'type' => 'text',
      'body' => 'beli 1',
    ], $headers);

    $response->assertStatus(200);
    $this->assertEquals('Buyer: Product checkout link sent', $response->json('reply'));

    // Assert cart item created in database
    $this->assertDatabaseHas('cart_items', [
      'user_id' => $buyer->id,
      'product_id' => $product1->id,
      'qty' => 1,
    ]);
  }

  public function test_profile_update_jid_resolution()
  {
    $user = User::factory()->create([
      'role' => \App\Enums\UserRole::Consumer,
      'phone' => '085183700720',
    ]);

    \Illuminate\Support\Facades\Http::fake([
      'http://localhost:3010/resolve*' => \Illuminate\Support\Facades\Http::response([
        'exists' => true,
        'jid' => '127367322333186@lid'
      ], 200)
    ]);

    $response = $this->actingAs($user)->postJson('/user/profile/update', [
      'name' => 'Budi Santoso',
      'email' => 'budi_resolved@grownesia.id',
      'phone' => '085183700720',
      'address' => 'Jakarta',
    ]);

    $response->assertStatus(200);
    $response->assertJsonPath('success', true);

    // Assert phone is updated to the resolved LID username part: "127367322333186"
    $user->refresh();
    $this->assertEquals('127367322333186', $user->phone);
  }

  public function test_business_profile_update_whatsapp_jid_resolution()
  {
    $businessUser = User::factory()->create([
      'role' => \App\Enums\UserRole::Business,
      'phone' => null,
    ]);

    Business::factory()->create([
      'user_id' => $businessUser->id,
    ]);

    \Illuminate\Support\Facades\Http::fake([
      'http://localhost:3010/resolve*' => \Illuminate\Support\Facades\Http::response([
        'exists' => true,
        'jid' => '127367322333186@lid'
      ], 200)
    ]);

    $response = $this->actingAs($businessUser)->post('/business/whatsapp/update', [
      'phone' => '085183700720',
    ]);

    $response->assertRedirect();
    $businessUser->refresh();
    $this->assertEquals('127367322333186', $businessUser->phone);
  }

  public function test_product_creation_triggers_whatsapp_channel_post(): void
  {
    $token = 'test-token-123';
    config([
      'services.whatsapp.url' => 'http://127.0.0.1:3010',
      'services.whatsapp.token' => $token,
      'services.whatsapp.driver' => 'baileys',
      'services.whatsapp.channel_jid' => '120363303649643194@newsletter',
    ]);

    // Mock the outgoing send API call to the channel
    Http::fake([
      '127.0.0.1:3010/send' => Http::response(['id' => 'WAMSG999', 'to' => '120363303649643194@newsletter']),
    ]);

    $user = User::factory()->create(['role' => 'business']);
    $business = Business::factory()->create(['user_id' => $user->id]);

    $product = Product::create([
      'business_id' => $business->id,
      'name' => 'Kopi Arabika Premium',
      'slug' => 'kopi-arabika-premium-123',
      'sku' => 'KOPI-PREM',
      'category' => 'kopi',
      'description' => 'Kopi Arabika pilihan berkualitas tinggi.',
      'price' => 75000,
      'stock' => 50,
      'status' => 'active',
    ]);

    // Verify that the HTTP fake received the request to send the product message to the WhatsApp channel
    Http::assertSent(function ($request) {
      return $request->url() === 'http://127.0.0.1:3010/send' &&
             $request['to'] === '120363303649643194@newsletter' &&
             str_contains($request['message'], 'Kopi Arabika Premium') &&
             str_contains($request['message'], 'Kopi Arabika pilihan berkualitas tinggi.') &&
             str_contains($request['message'], 'Rp 75.000');
    });
  }
}
