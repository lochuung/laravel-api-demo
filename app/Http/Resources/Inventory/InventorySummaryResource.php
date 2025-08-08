<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventorySummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->resource['product_id'] ?? null,
            'product_name' => $this->resource['product_name'] ?? null,
            'product_sku' => $this->resource['product_sku'] ?? null,

            // Current stock information
            'current_stock' => $this->resource['current_stock'] ?? 0,
            'min_stock' => $this->resource['min_stock'] ?? 0,
            'stock_status' => $this->resource['stock_status'] ?? 'unknown', // available, low, critical, out_of_stock
            'stock_value' => $this->resource['stock_value'] ?? 0,

            // Base unit information
            'base_unit' => [
                'id' => $this->resource['base_unit_id'] ?? null,
                'name' => $this->resource['base_unit_name'] ?? null,
                'sku' => $this->resource['base_unit_sku'] ?? null,
            ],

            // Transaction summaries
            'transactions' => [
                'total_imports' => $this->resource['total_imports'] ?? 0,
                'total_exports' => $this->resource['total_exports'] ?? 0,
                'total_adjustments' => $this->resource['total_adjustments'] ?? 0,
                'last_import_date' => $this->resource['last_import_date'] ?? null,
                'last_export_date' => $this->resource['last_export_date'] ?? null,
                'last_adjustment_date' => $this->resource['last_adjustment_date'] ?? null,
            ],

            // Available units for this product
            'available_units' => $this->when(
                isset($this->resource['available_units']),
                function () {
                    return collect($this->resource['available_units'])->map(function ($unit) {
                        return [
                            'id' => $unit['id'] ?? null,
                            'unit_name' => $unit['unit_name'] ?? null,
                            'sku' => $unit['sku'] ?? null,
                            'conversion_rate' => $unit['conversion_rate'] ?? 1,
                            'is_base_unit' => $unit['is_base_unit'] ?? false,
                            'current_stock_in_unit' => $unit['current_stock_in_unit'] ?? 0,
                        ];
                    });
                }
            ),

            // Recent activity
            'recent_activity' => $this->when(
                isset($this->resource['recent_transactions']),
                function () {
                    return collect($this->resource['recent_transactions'])->map(function ($transaction) {
                        return [
                            'id' => $transaction['id'] ?? null,
                            'type' => $transaction['type'] ?? null,
                            'quantity' => $transaction['quantity'] ?? 0,
                            'date' => $transaction['date'] ?? null,
                            'notes' => $transaction['notes'] ?? null,
                        ];
                    });
                }
            ),

            'generated_at' => $this->resource['generated_at'] ?? now()->format('Y-m-d H:i:s'),
        ];
    }
}
