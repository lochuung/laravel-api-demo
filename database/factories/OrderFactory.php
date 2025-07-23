<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
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
            'user_id' => User::factory(),
            'order_number' => $this->faker->unique()->numerify('ORD-#####'),
            'total_amount' => $this->faker->randomFloat(2, 20, 500) * 1000,
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'canceled']),
            'shipping_address' => $this->faker->address,
            'billing_address' => $this->faker->address,
            'ordered_at' => $this->faker->dateTimeBetween('-1 month'),
            'shipped_at' => $this->faker->optional()->dateTimeBetween('ordered_at'),
            'delivered_at' => $this->faker->optional()->dateTimeBetween('shipped_at'),
            'canceled_at' => $this->faker->optional()->dateTimeBetween('ordered_at'),
        ];
    }
}
