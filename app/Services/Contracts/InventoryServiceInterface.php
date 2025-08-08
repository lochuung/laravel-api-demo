<?php

namespace App\Services\Contracts;

use App\Models\InventoryTransaction;
use Illuminate\Database\Eloquent\Collection;

interface InventoryServiceInterface
{
    /**
     * Import inventory with base unit
     */
    public function importInventory(
        int $productId,
        int $quantity,
        float $price,
        ?string $notes = null
    ): InventoryTransaction;

    /**
     * Export inventory with unit options
     */
    public function exportInventory(
        int $productId,
        int $quantity,
        ?int $unitId = null,
        ?int $orderId = null,
        ?string $notes = null
    ): InventoryTransaction;

    /**
     * Get inventory transactions for a product
     */
    public function getProductInventoryHistory(int $productId, array $filters = []): Collection;

    /**
     * Get overall inventory statistics
     */
    public function getInventoryStats(): array;

    /**
     * Adjust inventory (for corrections)
     */
    public function adjustInventory(
        int $productId,
        int $newQuantity,
        string $reason = 'Manual adjustment'
    ): InventoryTransaction;
}
