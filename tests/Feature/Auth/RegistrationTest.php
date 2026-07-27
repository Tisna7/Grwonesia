<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_business_registration_creates_user_and_business(): void
    {
        $response = $this->post('/register', [
            'name' => 'Pemilik Usaha',
            'email' => 'owner@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'business_name' => 'Warung Kopi Test',
            'business_category' => 'Kuliner',
            'business_city' => 'Bandung',
        ]);

        $response->assertRedirect(route('business.dashboard'));

        $user = User::where('email', 'owner@test.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isBusiness());
        $this->assertNotNull($user->business);
        $this->assertSame('Warung Kopi Test', $user->business->name);
        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_requires_business_fields(): void
    {
        $response = $this->post('/register', [
            'name' => 'Tanpa Usaha',
            'email' => 'x@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['business_name', 'business_category']);
    }
}
