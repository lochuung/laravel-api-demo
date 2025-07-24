<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Nette\Utils\Random;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // each user should have 5 orders
        // each order should have 3 products

        User::all()->each(function ($user) {
            $user->orders()->createMany(
                Order::factory()->count(5)->make()->toArray()
            )->each(function ($order) {
                $products = Product::inRandomOrder()->take(5)->get();
                $order_items = $products->map(function ($product) use ($order) {
                    $quantity = Random::generate(5, '12345'); // random quantity between 1 and 5
                    return [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity, // random quantity between 1 and 5
                        'price' => $product->price, // assuming price is a field in Product model,
                        'total' => $product->price * $quantity, // total price
                        'note' => 'This is a note for product ' . $product->name,
                        'sku' => $product->sku, // assuming sku is a field in Product model
                        'product_name' => $product->name, // assuming name is a field in Product model
                        'product_image' => $product->image, // assuming image is a field in Product model
                    ];
                });
                $order->items()->createMany($order_items->toArray());

                // Update order total amount
                $order->total_amount = $order->items->sum('total');
                $order->save();
            }
            );
        });
    }
}
