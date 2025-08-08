<?php

namespace Database\Factories;

use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryTransaction>
 */
class InventoryTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'type' => fake()->randomElement(['import', 'export']),
            'quantity' => fake()->numberBetween(1, 100),
            'price' => fake()->randomFloat(2, 10, 1000),
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
            'order_id' => fake()->boolean(30) ? Order::factory() : null,
        ];
    }
}
