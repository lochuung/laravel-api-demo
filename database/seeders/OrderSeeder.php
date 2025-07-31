<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function ($user) {
            $user->orders()->createMany(
                Order::factory()->count(rand(2, 5))->make()->toArray()
            )->each(function ($order) {
                $products = Product::inRandomOrder()->take(rand(2, 5))->get();
                $order_items = $products->map(function ($product) use ($order) {
                    $quantity = rand(1, 5); // random quantity between 1 and 5
                    return [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'total' => $product->price * $quantity,
                        'note' => 'Note for product ' . $product->name,
                        'sku' => $product->sku,
                        'product_name' => $product->name,
                        'product_image' => $product->image,
                    ];
                });
                $order->items()->createMany($order_items->toArray());

                // Update order total amount
                $order->total_amount = $order->items->sum('total');
                $order->save();
            });
        });
    }
}
