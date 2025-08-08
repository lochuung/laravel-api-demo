<?php

namespace Database\Seeders;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Database\Seeder;

class InventoryTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo các giao dịch import cho tất cả sản phẩm
        $products = Product::all();

        foreach ($products as $product) {
            // Mỗi sản phẩm có 2-5 giao dịch import
            InventoryTransaction::factory(rand(2, 5))->create([
                'product_id' => $product->id,
                'type' => 'import',
                'order_id' => null,
            ]);

            // Và có thể có 1-3 giao dịch export
            InventoryTransaction::factory(rand(1, 3))->create([
                'product_id' => $product->id,
                'type' => 'export',
            ]);
        }
    }
}
