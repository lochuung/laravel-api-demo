<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * OrderRepository constructor.
     *
     * @param Order $model
     */
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getMonthlyRevenue(): float
    {
        // TODO: Implement getMonthlyRevenue() method.
        return $this->model::whereNotNull('ordered_at')
            ->whereMonth('ordered_at', now()->month)
            ->whereYear('ordered_at', now()->year)
            ->sum('total_amount')
            ?? 0;
    }

    public function getRecentOrders(int $limit = 5): Collection
    {
        // TODO: Implement getRecentOrders() method.
        return $this->model::with('user')
            ->whereNotNull('ordered_at')
            ->orderBy('ordered_at', 'desc')
            ->take($limit)
            ->get();
    }
}
