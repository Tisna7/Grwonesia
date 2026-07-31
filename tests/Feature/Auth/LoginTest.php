<?php

namespace Tests\Feature\Auth;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
  use RefreshDatabase;

  public function test_business_user_can_login_and_is_redirected_to_dashboard(): void
  {
    $user = User::factory()->create(['role' => 'business', 'password' => 'password123']);
    Business::factory()->create(['user_id' => $user->id]);

    $response = $this->post('/login', [
      'email' => $user->email,
      'password' => 'password123',
    ]);

    $response->assertRedirect(route('business.dashboard'));
    $this->assertAuthenticatedAs($user);
  }

  public function test_login_fails_with_wrong_password(): void
  {
    $user = User::factory()->create(['password' => 'password123']);

    $response = $this->from('/login')->post('/login', [
      'email' => $user->email,
      'password' => 'salah-total',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
  }

  public function test_user_can_logout(): void
  {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout')->assertRedirect('/login');
    $this->assertGuest();
  }
}
