<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BaileysGatewayTest extends TestCase
{
    use RefreshDatabase;

    private function useBaileysDriver(): void
    {
        config([
            'services.whatsapp.driver' => 'baileys',
            'services.whatsapp.url' => 'http://127.0.0.1:3010',
            'services.whatsapp.token' => 'test-token',
        ]);
    }

    public function test_broadcast_via_baileys_marks_messages_sent_and_calls_gateway(): void
    {
        $this->useBaileysDriver();

        Http::fake([
            '127.0.0.1:3010/send' => Http::response(['id' => 'WAMSG123', 'to' => '628111']),
        ]);

        $user = User::factory()->create(['role' => 'business']);
        $business = Business::factory()->create(['user_id' => $user->id]);
        Customer::factory()->count(2)->create(['business_id' => $business->id]);

        $this->actingAs($user)
            ->post('/business/whatsapp/broadcast', ['body' => 'Promo gateway asli!'])
            ->assertRedirect();

        $this->assertDatabaseCount('wa_messages', 2);
        $this->assertDatabaseHas('wa_messages', ['status' => 'sent']);
        $this->assertDatabaseMissing('wa_messages', ['status' => 'mocked']);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/send')
                && $request->hasHeader('Authorization', 'Bearer test-token')
                && isset($request['to'], $request['message']);
        });
    }

    public function test_gateway_failure_marks_message_failed_without_breaking_flow(): void
    {
        $this->useBaileysDriver();

        Http::fake([
            '127.0.0.1:3010/send' => Http::response(['error' => 'WhatsApp belum terhubung'], 503),
        ]);

        $user = User::factory()->create(['role' => 'business']);
        $business = Business::factory()->create(['user_id' => $user->id]);
        Customer::factory()->create(['business_id' => $business->id]);

        $this->actingAs($user)
            ->post('/business/whatsapp/broadcast', ['body' => 'Tes gagal'])
            ->assertRedirect();

        $this->assertDatabaseHas('wa_messages', ['status' => 'failed']);
    }
}
