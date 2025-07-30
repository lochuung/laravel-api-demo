<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{

    public function getMonthlyRevenue() : float;

    public function getRecentOrders(int $limit = 5) : \Illuminate\Database\Eloquent\Collection;
}
