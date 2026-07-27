<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\GovProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleFeedbackLoopTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Business $business;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'business']);
        $this->business = Business::factory()->create([
            'user_id' => $this->user->id,
            'city' => 'Bandung',
            'category' => 'Kuliner',
        ]);
    }

    public function test_business_sees_relevant_active_program_on_dashboard(): void
    {
        $relevant = GovProgram::factory()->create([
            'status' => 'aktif',
            'city' => 'Bandung',
            'sector' => 'Kuliner',
            'title' => 'Pelatihan Kuliner Bandung',
        ]);
        GovProgram::factory()->create([
            'status' => 'aktif',
            'city' => 'Surabaya', // kota lain — tidak relevan
            'title' => 'Program Surabaya',
        ]);
        GovProgram::factory()->create([
            'status' => 'draft', // belum aktif — tidak tampil
            'city' => 'Bandung',
            'title' => 'Program Draft',
        ]);

        $response = $this->actingAs($this->user)->get('/business/dashboard');

        $response->assertOk()
            ->assertSee('Pelatihan Kuliner Bandung')
            ->assertDontSee('Program Surabaya')
            ->assertDontSee('Program Draft');
    }

    public function test_business_can_register_to_relevant_program_once(): void
    {
        $program = GovProgram::factory()->create([
            'status' => 'aktif',
            'city' => 'Bandung',
            'sector' => 'Kuliner',
        ]);

        $this->actingAs($this->user)
            ->post("/business/programs/{$program->id}/register")
            ->assertRedirect();

        // Daftar dua kali → tetap satu baris (idempotent)
        $this->actingAs($this->user)
            ->post("/business/programs/{$program->id}/register")
            ->assertRedirect();

        $this->assertDatabaseCount('program_registrations', 1);
        $this->assertDatabaseHas('program_registrations', [
            'gov_program_id' => $program->id,
            'business_id' => $this->business->id,
        ]);
    }

    public function test_business_cannot_register_to_irrelevant_program(): void
    {
        $program = GovProgram::factory()->create([
            'status' => 'aktif',
            'city' => 'Surabaya',
        ]);

        $this->actingAs($this->user)
            ->post("/business/programs/{$program->id}/register")
            ->assertNotFound();

        $this->assertDatabaseCount('program_registrations', 0);
    }

    public function test_government_sees_registration_count(): void
    {
        $gov = User::factory()->create(['role' => 'government']);
        $program = GovProgram::factory()->create([
            'status' => 'aktif',
            'city' => 'Bandung',
            'sector' => 'Kuliner',
        ]);

        $this->actingAs($this->user)->post("/business/programs/{$program->id}/register");

        $this->actingAs($gov)->get('/government/programs')
            ->assertOk()
            ->assertSee('1 UMKM terdaftar');
    }

    public function test_verification_badge_shows_on_business_topbar(): void
    {
        $this->business->update(['verification_status' => 'verified', 'verified_at' => now()]);

        $this->actingAs($this->user)->get('/business/dashboard')
            ->assertSee('Terverifikasi');

        $this->business->update(['verification_status' => 'pending', 'verified_at' => null]);
        $this->user->unsetRelation('business');

        $this->actingAs($this->user)->get('/business/dashboard')
            ->assertSee('Menunggu Verifikasi');
    }
}
