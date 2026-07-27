<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstagramTest extends TestCase
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

    private function configureInstagram(): void
    {
        config([
            'services.instagram.business_id' => '17840000000000000',
            'services.instagram.token' => 'test-ig-token',
        ]);
    }

    private function productWithPhoto(): Product
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->image('produk.jpg')->store('products', 'public');

        return Product::factory()->create([
            'business_id' => $this->business->id,
            'photo_path' => $path,
        ]);
    }

    public function test_publish_success_records_published_post(): void
    {
        $this->configureInstagram();
        $product = $this->productWithPhoto();

        Http::fake([
            'graph.facebook.com/v21.0/17840000000000000/media' => Http::response(['id' => 'CREATION123']),
            'graph.facebook.com/v21.0/17840000000000000/media_publish' => Http::response(['id' => 'MEDIA456']),
        ]);

        $response = $this->actingAs($this->user)->post('/business/instagram/publish', [
            'product_id' => $product->id,
            'caption' => 'Kopi lokal terbaik! #umkm',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ig_posts', [
            'business_id' => $this->business->id,
            'product_id' => $product->id,
            'status' => 'published',
            'ig_media_id' => 'MEDIA456',
        ]);

        Http::assertSent(fn ($request) => str_contains($request->url(), '/media_publish')
            && $request['creation_id'] === 'CREATION123');
    }

    public function test_publish_failure_records_error_from_meta(): void
    {
        $this->configureInstagram();
        $product = $this->productWithPhoto();

        Http::fake([
            'graph.facebook.com/v21.0/17840000000000000/media' => Http::response([
                'error' => ['message' => 'Invalid image URL'],
            ], 400),
        ]);

        $response = $this->actingAs($this->user)->post('/business/instagram/publish', [
            'product_id' => $product->id,
            'caption' => 'Tes gagal',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('ig_posts', [
            'status' => 'failed',
            'error' => 'Invalid image URL',
        ]);
    }

    public function test_publish_rejected_when_not_configured(): void
    {
        $product = $this->productWithPhoto();

        $this->actingAs($this->user)->post('/business/instagram/publish', [
            'product_id' => $product->id,
            'caption' => 'Tanpa konfigurasi',
        ])->assertSessionHas('error');

        $this->assertDatabaseHas('ig_posts', ['status' => 'failed']);
    }

    public function test_cannot_publish_another_businesses_product(): void
    {
        $this->configureInstagram();
        $foreign = Product::factory()->create(['photo_path' => 'products/x.jpg']);

        $this->actingAs($this->user)->post('/business/instagram/publish', [
            'product_id' => $foreign->id,
            'caption' => 'Bukan punyaku',
        ])->assertNotFound();
    }

    public function test_instagram_page_renders(): void
    {
        $this->actingAs($this->user)->get('/business/instagram')->assertOk();
    }
}
