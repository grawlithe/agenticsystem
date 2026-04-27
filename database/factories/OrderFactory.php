<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'order_number' => 'ORD-'.fake()->unique()->numerify('##########'),
            'total_amount' => fake()->randomFloat(2, 50, 5000),
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'notes' => fake()->optional()->paragraph(),
            'shipped_at' => fake()->optional()->dateTimeBetween('2020-01-01', '2026-04-07'),
            'delivered_at' => fake()->optional()->dateTimeBetween('2020-01-01', '2026-04-07'),
        ];
    }
}
