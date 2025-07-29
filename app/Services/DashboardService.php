<?php

namespace App\Services;

use App\Http\Resources\Dashboard\DashboardResource;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Services\Contracts\DashboardServiceInterface;

class DashboardService implements DashboardServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private OrderRepositoryInterface $orderRepository;
    private ProductRepositoryInterface $productRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, OrderRepositoryInterface $orderRepository, ProductRepositoryInterface $productRepository)
    {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }


    public function getDashboardData(): DashboardResource
    {
        // TODO: Implement getDashboardData() method.
        $totalUsers = $this->userRepository->count();
        $totalProducts = $this->productRepository->count();
        $totalOrders = $this->orderRepository->count();
        $monthlyRevenue = $this->orderRepository->getMonthlyRevenue();

        $recentOrders = $this->orderRepository->getRecentOrders(5);
        $recentUsers = $this->userRepository->getRecentUsers(5);

        return new DashboardResource(
            (object)[
                'total_users' => $totalUsers,
                'total_products' => $totalProducts,
                'total_orders' => $totalOrders,
                'monthly_revenue' => $monthlyRevenue,
                'recent_orders' => $recentOrders,
                'recent_users' => $recentUsers,
            ]
        );
    }
}
