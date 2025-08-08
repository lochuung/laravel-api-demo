<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{

    public function getMonthlyRevenue(): float;

    public function getRecentOrders(int $limit = 5): Collection;
}
