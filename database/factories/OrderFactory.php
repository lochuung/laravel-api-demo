<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Customer;
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
        $orderedAt = $this->faker->dateTimeBetween('-1 month', 'now');

        // 60% chance to generate a shipped date after orderedAt
        $shippedAt = $this->faker->optional(0.6)->dateTimeBetween($orderedAt, 'now');

        // 30% chance to generate a delivered date after shippedAt (if shippedAt exists)
        $deliveredAt = $shippedAt
            ? $this->faker->optional(0.5)->dateTimeBetween($shippedAt, 'now')
            : null;

        // 10% chance to generate a canceled date after orderedAt
        $canceledAt = $this->faker->optional(0.1)->dateTimeBetween($orderedAt, 'now');

        return [
            'user_id' => User::factory(),
            'customer_id' => Customer::factory(),
            'order_number' => $this->faker->unique()->regexify('ORD[0-9]{8}'),
            'order_date' => $orderedAt,
            'total_amount' => $this->faker->randomFloat(2, 50, 2000),
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'canceled']),
            'shipping_address' => $this->faker->address,
            'billing_address' => $this->faker->address,
            'ordered_at' => $orderedAt,
            'shipped_at' => $shippedAt,
            'delivered_at' => $deliveredAt,
            'canceled_at' => $canceledAt,
        ];
    }
}
