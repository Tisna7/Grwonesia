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
    ]);

    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'owner@test.com')->first();
    $this->assertNotNull($user);
    $this->assertSame('pending', $user->status);
  }

  public function test_registration_requires_business_fields(): void
  {
    $response = $this->post('/register', [
      'name' => '',
      'email' => 'not-an-email',
      'password' => '123',
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
  }
}
