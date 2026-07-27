<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Kopi Gula Aren', 'Keripik Pedas', 'Batik Tulis', 'Tas Anyaman',
            'Madu Hutan', 'Rendang Kemasan', 'Teh Herbal', 'Sambal Bawang',
        ]).' '.fake()->randomElement(['Premium', '250g', '500g', 'Original', 'Spesial']);

        $price = fake()->numberBetween(15, 150) * 1000;

        return [
            'business_id' => Business::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 99999),
            'sku' => strtoupper(Str::random(8)),
            'category' => fake()->randomElement(['Makanan', 'Minuman', 'Fashion', 'Kerajinan']),
            'description' => fake()->sentence(15),
            'price' => $price,
            'cost_price' => (int) round($price * fake()->randomFloat(2, 0.5, 0.7)),
            'stock' => fake()->numberBetween(10, 120),
            'min_stock' => 5,
            'status' => 'active',
        ];
    }
}
