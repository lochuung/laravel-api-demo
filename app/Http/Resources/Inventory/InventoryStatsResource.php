<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryStatsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_products' => $this->resource['total_products'] ?? 0,
            'total_stock_value' => $this->resource['total_stock_value'] ?? 0,
            'low_stock_products' => $this->resource['low_stock_products'] ?? 0,
            'out_of_stock_products' => $this->resource['out_of_stock_products'] ?? 0,
            'recent_transactions' => $this->resource['recent_transactions'] ?? 0,
        ];
    }
}
