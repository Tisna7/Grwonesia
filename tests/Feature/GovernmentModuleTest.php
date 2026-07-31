<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\GovProgram;
use App\Models\User;
use App\Services\PolicySimulatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GovernmentModuleTest extends TestCase
{
  use RefreshDatabase;

  private User $gov;

  protected function setUp(): void
  {
    parent::setUp();

    $this->gov = User::factory()->create(['role' => 'government', 'status' => 'terverifikasi']);
  }

  public function test_government_can_access_dashboard(): void
  {
    Business::factory()->create();

    $this->actingAs($this->gov)->get('/government/dashboard')->assertOk();
  }

  public function test_business_user_cannot_access_government_dashboard(): void
  {
    $business = User::factory()->create(['role' => 'business', 'status' => 'terverifikasi']);
    Business::factory()->create(['user_id' => $business->id]);

    $this->actingAs($business)->get('/government/dashboard')->assertRedirect(route('business.dashboard'));
  }

  public function test_guest_redirected_from_government_routes(): void
  {
    $this->get('/government/dashboard')->assertRedirect('/login');
  }

  public function test_government_can_create_program(): void
  {
    $response = $this->actingAs($this->gov)->post('/government/programs', [
      'title' => 'Pelatihan Digital Marketing',
      'type' => 'pelatihan',
      'sector' => 'Kuliner',
      'city' => 'Bandung',
      'description' => 'Pelatihan untuk 50 UMKM.',
      'status' => 'aktif',
    ]);

    $response->assertRedirect(route('government.programs'));
    $this->assertDatabaseHas('gov_programs', [
      'title' => 'Pelatihan Digital Marketing',
      'user_id' => $this->gov->id,
    ]);
  }

  public function test_government_can_update_program_status_and_delete(): void
  {
    $program = GovProgram::factory()->create(['user_id' => $this->gov->id]);

    $this->actingAs($this->gov)
      ->patch("/government/programs/{$program->id}", ['status' => 'aktif'])
      ->assertRedirect();
    $this->assertSame('aktif', $program->fresh()->status);

    $this->actingAs($this->gov)
      ->delete("/government/programs/{$program->id}")
      ->assertRedirect();
    $this->assertNull($program->fresh());
  }

  public function test_policy_simulator_returns_projection(): void
  {
    Business::factory()->create();

    $response = $this->actingAs($this->gov)->postJson('/government/policy/simulate', [
      'subsidi_ongkir' => 20,
      'bantuan_alat' => 50,
      'pelatihan_batch' => 2,
      'durasi_bulan' => 3,
    ]);

    $response->assertOk()
      ->assertJsonStructure([
        'data' => ['baseline_monthly', 'uplift_pct', 'monthly', 'cumulative_extra', 'estimated_cost', 'new_jobs', 'components'],
      ]);

    $this->assertCount(3, $response->json('data.monthly'));
    $this->assertGreaterThan(0, $response->json('data.uplift_pct'));
  }

  public function test_simulator_uplift_increases_with_bigger_subsidy(): void
  {
    $simulator = app(PolicySimulatorService::class);

    $small = $simulator->simulate(['subsidi_ongkir' => 10, 'durasi_bulan' => 3]);
    $large = $simulator->simulate(['subsidi_ongkir' => 40, 'durasi_bulan' => 3]);

    $this->assertGreaterThan($small['uplift_pct'], $large['uplift_pct']);
  }

  public function test_program_recommend_returns_503_without_gemini(): void
  {
    $this->actingAs($this->gov)
      ->postJson('/government/programs/recommend')
      ->assertStatus(503);
  }
}
