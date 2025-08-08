<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface InventoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get inventory transactions by product ID
     */
    public function getByProduct(int $productId, array $filters = []): Collection;

    /**
     * Get total quantity by product and transaction type
     */
    public function getTotalByProductAndType(int $productId, string $type): int;

    /**
     * Get recent transactions for a product
     */
    public function getRecentByProduct(int $productId, int $limit = 10): Collection;

    /**
     * Get transactions by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection;

    /**
     * Get low stock alerts data
     */
    public function getLowStockData(): Collection;
}
