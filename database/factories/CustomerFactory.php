<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'name' => fake()->name(),
            'phone' => '628'.fake()->numerify('##########'),
            'email' => fake()->unique()->safeEmail(),
            'city' => fake()->randomElement(['Bandung', 'Jakarta', 'Bogor', 'Bekasi', 'Depok', 'Sukabumi']),
        ];
    }
}
