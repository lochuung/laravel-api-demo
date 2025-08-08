<?php

namespace App\Console\Commands;

use App\Events\LowStockDetected;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Console\Command;
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
     * @param ProductRepositoryInterface $productRepository
     */
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        parent::__construct();
        $this->productRepository = $productRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $lowStockProducts = $this->productRepository->getProductsBelowMinimumStock();
        foreach ($lowStockProducts as $product) {
            event(new LowStockDetected($product));
        }

        $this->info("Checked stock. Low stock products: " . $lowStockProducts->count());
        return CommandAlias::SUCCESS;
    }
}
