<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
            'name' => $this->faker->words(2, true),
            'code' => $this->faker->unique()->regexify('PRD[0-9]{8}'),
            'category_id' => Category::factory(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 5, 500),
            'cost' => $this->faker->randomFloat(2, 2, 250),
            'stock' => $this->faker->numberBetween(0, 100),
            'min_stock' => $this->faker->numberBetween(0, 20),
            'barcode' => $this->faker->unique()->ean13(),
            'expiry_date' => $this->faker->optional(0.7)->dateTimeBetween('now', '+2 years'),
            'image' => asset('images/default_product.png'),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
