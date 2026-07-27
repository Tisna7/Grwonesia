<?php

namespace Database\Seeders;

use App\Models\AiRequest;
use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminDemoSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin Grownesia',
            'email' => 'admin@grownesia.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        // Status verifikasi campuran agar halaman verifikasi berisi
        $businesses = Business::orderBy('id')->get();
        foreach ($businesses as $index => $business) {
            if ($index < 2) {
                $business->update([
                    'verification_status' => 'verified',
                    'verified_at' => now()->subDays(mt_rand(10, 60)),
                ]);
            }
            // sisanya tetap pending
        }

        // Contoh log AI request agar AI Center berisi (data historis simulasi)
        $kinds = ['text', 'json', 'chat', 'vision'];
        for ($i = 0; $i < 60; $i++) {
            $success = mt_rand(1, 20) > 1; // ~95% sukses
            AiRequest::create([
                'model' => 'gemini-2.5-flash',
                'kind' => $kinds[array_rand($kinds)],
                'success' => $success,
                'duration_ms' => mt_rand(600, 3200),
                'prompt_tokens' => $success ? mt_rand(200, 2500) : null,
                'output_tokens' => $success ? mt_rand(80, 900) : null,
                'error' => $success ? null : 'HTTP 429',
                'created_at' => now()->subHours(mt_rand(0, 168)),
                'updated_at' => now(),
            ]);
        }
    }
}
