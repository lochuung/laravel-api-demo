<?php

namespace App\Console\Commands;

use App\Events\LowStockDetected;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as CommandAlias;

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
    public function handle(): int
    {
        $lowStockProducts = Product::with("owner")
            ->whereColumn('stock', '<', 'min_stock')->get();

        foreach ($lowStockProducts as $product) {
            event(new LowStockDetected($product));
        }

        $this->info("Checked stock. Low stock products: " . $lowStockProducts->count());
        return CommandAlias::SUCCESS;
    }
}
