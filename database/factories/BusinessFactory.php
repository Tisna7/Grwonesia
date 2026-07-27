<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Business>
 */
class BusinessFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'user_id' => User::factory()->state(['role' => 'business']),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'category' => fake()->randomElement(['Kuliner', 'Fashion', 'Kerajinan', 'Pertanian']),
            'description' => fake()->sentence(12),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement(['Bandung', 'Sukabumi', 'Garut', 'Cianjur', 'Tasikmalaya']),
            'wa_number' => '628'.fake()->numerify('##########'),
            'monthly_fixed_cost' => fake()->numberBetween(2, 8) * 1000000,
        ];
    }
}
