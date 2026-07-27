<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppStubTest extends TestCase
{
    use RefreshDatabase;

    public function test_broadcast_creates_mocked_wa_messages(): void
    {
        $user = User::factory()->create(['role' => 'business']);
        $business = Business::factory()->create(['user_id' => $user->id]);
        Customer::factory()->count(3)->create(['business_id' => $business->id]);
        Customer::factory()->create(['business_id' => $business->id, 'phone' => null]);

        $response = $this->actingAs($user)->post('/business/whatsapp/broadcast', [
            'body' => 'Promo spesial akhir pekan!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('wa_messages', 3);
        $this->assertDatabaseHas('wa_messages', [
            'business_id' => $business->id,
            'type' => 'broadcast',
            'status' => 'mocked',
        ]);
    }

    public function test_ai_endpoints_return_503_when_gemini_not_configured(): void
    {
        $user = User::factory()->create(['role' => 'business']);
        $business = Business::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->postJson('/business/coach', [
            'message' => 'Halo coach',
        ])->assertStatus(503);

        $this->actingAs($user)->postJson('/business/marketing/generate', [
            'type' => 'promo_copy',
            'brief' => 'Promo kopi',
        ])->assertStatus(503);
    }
}
