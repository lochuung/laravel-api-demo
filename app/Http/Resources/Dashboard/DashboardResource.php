<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    public int $total_users;
    public int $total_products;
    public int $total_orders;
    public float $monthly_revenue;
    public ?array $recent_orders;
    public ?array $recent_users;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_users' => $this->total_users,
            'total_products' => $this->total_products,
            'total_orders' => $this->total_orders,
            'monthly_revenue' => $this->monthly_revenue,
            'recent_orders' => RecentOrderResource::collection($this->recent_orders ?? []),
            'recent_users' => RecentUserResource::collection($this->recent_users ?? []),
        ];
    }
}
