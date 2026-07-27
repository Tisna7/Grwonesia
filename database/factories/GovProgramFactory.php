<?php

namespace Database\Factories;

use App\Models\GovProgram;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GovProgram>
 */
class GovProgramFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'government']),
            'title' => 'Program '.fake()->words(3, true),
            'type' => fake()->randomElement(['pelatihan', 'bantuan', 'event', 'pameran']),
            'sector' => fake()->randomElement(['Kuliner', 'Fashion', 'Kerajinan']),
            'city' => fake()->randomElement(['Bandung', 'Garut', 'Sukabumi']),
            'description' => fake()->sentence(10),
            'status' => 'draft',
        ];
    }
}
