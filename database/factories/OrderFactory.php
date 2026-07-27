<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'customer_id' => null,
            'order_number' => Order::generateOrderNumber().fake()->unique()->numberBetween(10, 99),
            'status' => 'completed',
            'channel' => fake()->randomElement(['manual', 'whatsapp', 'marketplace']),
            'total' => 0,
            'total_cost' => 0,
            'ordered_at' => fake()->dateTimeBetween('-90 days'),
        ];
    }
}
