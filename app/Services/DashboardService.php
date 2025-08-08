<?php

namespace App\Services;

use App\Http\Resources\Dashboard\DashboardResource;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\DashboardServiceInterface;
use Illuminate\Support\Facades\DB;

class DashboardService implements DashboardServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private OrderRepositoryInterface $orderRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, OrderRepositoryInterface $orderRepository)
    {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
    }


    public function getDashboardData(): DashboardResource
    {
        // TODO: Implement getDashboardData() method.
        $totals = $this->getTotals();

        $recentOrders = $this->orderRepository->getRecentOrders();
        $recentUsers = $this->userRepository->getRecentUsers();

        return new DashboardResource(
            (object)[
                ...$totals,
                'recent_orders' => $recentOrders,
                'recent_users' => $recentUsers,
            ]
        );
    }

    private function getTotals(): array
    {
        $totals = DB::table('users')
            ->selectRaw(
                '
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM products) as total_products,
        (SELECT COUNT(*) FROM orders) as total_orders,
        (SELECT COALESCE(SUM(total_amount), 0)
            FROM orders
            WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
        ) as monthly_revenue',
                [now()->month, now()->year]
            )
            ->first();
        return [
            'total_users' => $totals->total_users,
            'total_products' => $totals->total_products,
            'total_orders' => $totals->total_orders,
            'monthly_revenue' => $totals->monthly_revenue,
        ];
    }
}
