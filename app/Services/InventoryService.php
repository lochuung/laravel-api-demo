<?php

namespace App\Services;

use App\Events\LowStockDetected;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Repositories\Contracts\InventoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\ProductUnitRepositoryInterface;
use App\Services\Contracts\InventoryServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class InventoryService implements InventoryServiceInterface
{
    public function __construct(
        private InventoryRepositoryInterface $inventoryRepository,
        private ProductRepositoryInterface $productRepository,
        private ProductUnitRepositoryInterface $productUnitRepository
    ) {
    }

    /**
     * Import inventory with base unit
     * @throws Throwable
     */
    public function importInventory(
        int $productId,
        int $quantity,
        float $price,
        ?string $notes = null
    ): InventoryTransaction {
        return DB::transaction(function () use ($productId, $quantity, $price, $notes) {
            $product = $this->productRepository->find($productId);
            if (!$product) {
                throw new Exception("Product not found");
            }

            // Create inventory transaction
            $transaction = $this->inventoryRepository->create([
                'product_id' => $productId,
                'type' => 'import',
                'quantity' => $quantity,
                'price' => $price,
                'date' => Carbon::now(),
                'notes' => $notes,
            ]);

            // Update product stock (always in base unit)
            $newStock = $product->stock + $quantity;
            $this->productRepository->updateStock($productId, $newStock);

            return $transaction;
        });
    }

    /**
     * Export inventory with unit options
     * @throws Throwable
     */
    public function exportInventory(
        int $productId,
        int $quantity,
        ?int $unitId = null,
        ?int $orderId = null,
        ?string $notes = null
    ): InventoryTransaction {
        return DB::transaction(function () use ($productId, $quantity, $unitId, $orderId, $notes) {
            /** @var Product|null $product */
            $product = $this->productRepository->find($productId);
            if (!$product) {
                throw new Exception("Product not found");
            }
            $baseQuantity = $quantity;
            $unitPrice = $product->price;

            // If unit is specified, calculate base quantity and price
            if ($unitId) {
                $unit = $this->productUnitRepository->findByProductAndUnit($productId, $unitId);

                if (!$unit) {
                    throw new Exception("Product unit not found");
                }

                $baseQuantity = $quantity / $unit->conversion_rate;
                $unitPrice = $unit->selling_price ?? $product->price;
            }

            // Check if enough stock available
            if ($product->stock < $baseQuantity) {
                throw new Exception("Insufficient stock. Available: $product->stock, Required: $baseQuantity");
            }

            // Create inventory transaction (store in base units)
            $transaction = $this->inventoryRepository->create([
                'product_id' => $productId,
                'type' => 'export',
                'quantity' => $quantity,
                'price' => $unitPrice,
                'date' => Carbon::now(),
                'order_id' => $orderId,
                'notes' => $notes,
                'unit_id' => $unitId,
            ]);

            // Update product stock
            $newStock = $product->stock - $baseQuantity;
            $this->productRepository->updateStock($productId, $newStock);

            // Check for low stock alert
            if ($newStock <= $product->min_stock) {
                event(new LowStockDetected($product));
            }

            return $transaction;
        });
    }

    /**
     * Get inventory transactions for a product
     */
    public function getProductInventoryHistory(int $productId, array $filters = []): Collection
    {
        return $this->inventoryRepository->getByProduct($productId, $filters);
    }

    /**
     * Get overall inventory statistics
     */
    public function getInventoryStats(): array
    {
        $lowStockProducts = count($this->productRepository->getLowStockProducts());
        $outOfStockProducts = $this->productRepository->getOutOfStockProductsCount();
        $totalProducts = $this->productRepository->getProductsCount();
        $totalStockValue = $this->productRepository->getTotalStockValue();
        $recentTransactions = $this->inventoryRepository->getRecentTransactions();

        return [
            'low_stock_products' => $lowStockProducts,
            'out_of_stock_products' => $outOfStockProducts,
            'total_products' => $totalProducts,
            'total_stock_value' => $totalStockValue,
            'recent_transactions' => $recentTransactions,
        ];
    }

    /**
     * Adjust inventory (for corrections)
     * @throws Throwable
     */
    public function adjustInventory(
        int $productId,
        int $newQuantity,
        string $reason = 'Manual adjustment'
    ): InventoryTransaction {
        return DB::transaction(function () use ($productId, $newQuantity, $reason) {
            /** @var Product|null $product */
            $product = $this->productRepository->find($productId);
            if (!$product) {
                throw new Exception("Product not found");
            }
            $currentStock = $product->stock;
            $difference = $newQuantity - $currentStock;

            if ($difference == 0) {
                throw new Exception('No adjustment needed - stock is already at the specified quantity');
            }

            $type = $difference > 0 ? 'import' : 'export';
            $quantity = abs($difference);

            // Create adjustment transaction
            $transaction = $this->inventoryRepository->create([
                'product_id' => $productId,
                'type' => $type,
                'quantity' => $quantity,
                'price' => 0, // Adjustments don't have a price
                'date' => Carbon::now(),
                'notes' => "Adjustment: $reason. From $currentStock to $newQuantity",
                'is_adjustment' => true,
            ]);

            // Update product stock
            $this->productRepository->updateStock($productId, $newQuantity);

            // Check for low stock if decreased
            if ($difference < 0 && $newQuantity <= $product->min_stock) {
                event(new LowStockDetected($product));
            }

            return $transaction;
        });
    }
}
