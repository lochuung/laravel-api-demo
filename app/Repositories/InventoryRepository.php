<?php

namespace App\Repositories;

use App\Models\InventoryTransaction;
use App\Repositories\Contracts\InventoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class InventoryRepository extends BaseRepository implements InventoryRepositoryInterface
{
    public function __construct(InventoryTransaction $model)
    {
        parent::__construct($model);
    }

    /**
     * Get inventory transactions by product ID
     */
    public function getByProduct(int $productId, array $filters = []): Collection
    {
        $query = $this->model->where('product_id', $productId)
            ->with(['product', 'order'])
            ->orderBy('created_at', 'desc');

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }

        if (isset($filters['limit'])) {
            $query->limit($filters['limit']);
        }

        return $query->get();
    }

    /**
     * Get total quantity by product and transaction type
     */
    public function getTotalByProductAndType(int $productId, string $type): int
    {
        return $this->model->where('product_id', $productId)
            ->where('type', $type)
            ->sum('quantity');
    }

    /**
     * Get recent transactions for a product
     */
    public function getRecentByProduct(int $productId, int $limit = 10): Collection
    {
        return $this->model->where('product_id', $productId)
            ->with(['product', 'order'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get transactions by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model->whereBetween('date', [$startDate, $endDate])
            ->with(['product', 'order'])
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Get low stock alerts data
     */
    public function getLowStockData(): Collection
    {
        return $this->model->whereHas('product', function ($query) {
            $query->whereRaw('stock <= CAST(min_stock AS UNSIGNED)');
        })
        ->with('product')
        ->orderBy('created_at', 'desc')
        ->get();
    }
}
