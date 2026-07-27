<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/business/dashboard')->assertRedirect('/login');
    }

    public function test_consumer_cannot_access_business_dashboard(): void
    {
        $consumer = User::factory()->create(['role' => 'consumer']);

        $this->actingAs($consumer)->get('/business/dashboard')->assertForbidden();
    }

    public function test_business_user_can_access_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'business']);
        Business::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get('/business/dashboard')->assertOk();
    }
}
