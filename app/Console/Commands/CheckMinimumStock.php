<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckMinimumStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:check-minimum-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for products below their minimum stock level';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lowStockProducts = Product::with("owner")
            ->whereColumn('stock', '<', 'min_stock')->get();

        if ($lowStockProducts->isEmpty()) {
            $this->info('All products have sufficient stock.');
            return 0;
        }

        foreach ($lowStockProducts as $product) {
            $ownerEmail = $product->owner->email;

            Log::warning("Product {$product->name} (ID: {$product->id}) is below minimum stock level. Current stock: {$product->stock}, Minimum stock: {$product->min_stock}. Notifying owner at {$ownerEmail}.");
        }

    }
}
