<?php

namespace App\Services;

use App\Events\InventoryLowStock;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Repositories\Contracts\InventoryRepositoryInterface;
use App\Services\Contracts\InventoryServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class InventoryService implements InventoryServiceInterface
{
    public function __construct(
        private InventoryRepositoryInterface $inventoryRepository
    )
    {
    }

    /**
     * Import inventory with base unit
     * @throws \Throwable
     */
    public function importInventory(int $productId, int $quantity, float $price, ?string $notes = null): InventoryTransaction
    {
        return DB::transaction(function () use ($productId, $quantity, $price, $notes) {
            $product = Product::findOrFail($productId);

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
            $product->increment('stock', $quantity);

            return $transaction;
        });
    }

    /**
     * Export inventory with unit options
     * @throws \Throwable
     */
    public function exportInventory(int $productId, int $quantity, ?int $unitId = null, ?int $orderId = null, ?string $notes = null): InventoryTransaction
    {
        return DB::transaction(function () use ($productId, $quantity, $unitId, $orderId, $notes) {
            $product = Product::findOrFail($productId);
            $baseQuantity = $quantity;
            $unitPrice = $product->price;

            // If unit is specified, calculate base quantity and price
            if ($unitId) {
                $unit = ProductUnit::where('product_id', $productId)
                    ->where('id', $unitId)
                    ->firstOrFail();

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
            $product->decrement('stock', $baseQuantity);

            // Check for low stock alert
            if ($product->stock <= (int)$product->min_stock) {
                event(new InventoryLowStock($product));
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
        $lowStockProducts = Product::whereRaw('stock <= CAST(min_stock AS UNSIGNED)')->count();
        $outOfStockProducts = Product::where('stock', 0)->count();
        $totalProducts = Product::count();

        $totalStockValue = Product::selectRaw('SUM(stock * cost) as total_value')->value('total_value') ?? 0;

        $recentTransactions = InventoryTransaction::with(['product', 'order'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

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
     */
    public function adjustInventory(int $productId, int $newQuantity, string $reason = 'Manual adjustment'): InventoryTransaction
    {
        return DB::transaction(function () use ($productId, $newQuantity, $reason) {
            $product = Product::findOrFail($productId);
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
                'notes' => "Adjustment: {$reason}. From {$currentStock} to {$newQuantity}",
                'is_adjustment' => true,
            ]);

            // Update product stock
            $product->update(['stock' => $newQuantity]);

            // Check for low stock if decreased
            if ($difference < 0 && $newQuantity <= (int)$product->min_stock) {
                event(new InventoryLowStock($product));
            }

            return $transaction;
        });
    }
}
