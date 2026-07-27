<?php

namespace Tests\Feature;

use App\Models\AiRequest;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_access_all_admin_pages(): void
    {
        Business::factory()->create();
        AiRequest::create([
            'model' => 'gemini-2.5-flash',
            'kind' => 'text',
            'success' => true,
            'duration_ms' => 1200,
            'prompt_tokens' => 500,
            'output_tokens' => 200,
        ]);

        $this->actingAs($this->admin)->get('/admin/dashboard')->assertOk();
        $this->actingAs($this->admin)->get('/admin/businesses')->assertOk();
        $this->actingAs($this->admin)->get('/admin/ai-center')->assertOk();
    }

    public function test_non_admin_roles_cannot_access_admin_pages(): void
    {
        $business = User::factory()->create(['role' => 'business']);
        Business::factory()->create(['user_id' => $business->id]);
        $gov = User::factory()->create(['role' => 'government']);

        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->actingAs($business)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($gov)->get('/admin/dashboard')->assertForbidden();
    }

    public function test_admin_can_verify_business(): void
    {
        $business = Business::factory()->create();

        $this->actingAs($this->admin)
            ->patch("/admin/businesses/{$business->id}", [
                'verification_status' => 'verified',
                'verification_note' => 'Dokumen NIB valid',
            ])
            ->assertRedirect();

        $business->refresh();
        $this->assertSame('verified', $business->verification_status);
        $this->assertSame('Dokumen NIB valid', $business->verification_note);
        $this->assertNotNull($business->verified_at);
    }

    public function test_admin_can_reject_business_and_clear_verified_at(): void
    {
        $business = Business::factory()->create([
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        $this->actingAs($this->admin)
            ->patch("/admin/businesses/{$business->id}", [
                'verification_status' => 'rejected',
                'verification_note' => 'Dokumen tidak lengkap',
            ])
            ->assertRedirect();

        $business->refresh();
        $this->assertSame('rejected', $business->verification_status);
        $this->assertNull($business->verified_at);
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }
}
