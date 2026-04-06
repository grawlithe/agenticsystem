<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => fake()->unique()->bothify('SKU-####??'),
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 500),
            'stock' => fake()->numberBetween(0, 1000),
            'reorder_level' => fake()->numberBetween(5, 50),
            'status' => fake()->randomElement(['active', 'inactive', 'discontinued']),
        ];
    }
}
