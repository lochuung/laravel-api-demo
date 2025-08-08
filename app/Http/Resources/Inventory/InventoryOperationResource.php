<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryOperationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * This resource is used for import/export/adjust operation responses
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // Transaction details
            'transaction' => [
                'id' => $this->id,
                'type' => $this->type,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'date' => $this->date?->format('Y-m-d H:i:s'),
                'notes' => $this->notes,
                'is_adjustment' => $this->is_adjustment ?? false,
                'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            ],

            // Product information
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'base_sku' => $this->product->base_sku,
                    'current_stock' => $this->product->stock,
                    'min_stock' => $this->product->min_stock,
                    'stock_status' => $this->getStockStatus(),
                ];
            }),

            // Unit information (if applicable)
            'unit' => $this->when($this->unit_id, function () {
                return $this->whenLoaded('unit', function () {
                    return [
                        'id' => $this->unit->id,
                        'unit_name' => $this->unit->unit_name,
                        'sku' => $this->unit->sku,
                        'conversion_rate' => $this->unit->conversion_rate,
                        'is_base_unit' => $this->unit->is_base_unit,
                        'quantity_in_unit' => $this->unit_quantity,
                    ];
                });
            }),

            // Order information (for exports)
            'order' => $this->when($this->order_id, function () {
                return $this->whenLoaded('order', function () {
                    return [
                        'id' => $this->order->id,
                        'order_code' => $this->order->order_code ?? null,
                        'status' => $this->order->status ?? null,
                    ];
                });
            }),

            // Stock impact
            'stock_impact' => [
                'previous_stock' => $this->resource->previous_stock ?? null,
                'current_stock' => $this->whenLoaded('product', fn() => $this->product->stock),
                'stock_change' => $this->getStockChange(),
            ],

            // Operation metadata
            'operation_metadata' => [
                'operation_type' => $this->getOperationType(),
                'affected_units' => $this->getAffectedUnits(),
                'conversion_applied' => $this->unit_id && $this->unit_quantity,
                'requires_reorder' => $this->whenLoaded('product', function () {
                    return $this->product->stock <= $this->product->min_stock;
                }),
            ],
        ];
    }

    /**
     * Get stock status based on current stock levels
     */
    private function getStockStatus(): string
    {
        if (!$this->relationLoaded('product')) {
            return 'unknown';
        }

        $stock = $this->product->stock;
        $minStock = $this->product->min_stock;

        if ($stock <= 0) {
            return 'out_of_stock';
        } elseif ($stock <= $minStock) {
            return 'low_stock';
        } elseif ($stock <= ($minStock * 2)) {
            return 'warning';
        } else {
            return 'adequate';
        }
    }

    /**
     * Get the stock change amount
     */
    private function getStockChange(): ?int
    {
        switch ($this->type) {
            case 'import':
                return $this->quantity;
            case 'export':
                return -$this->quantity;
            case 'adjustment':
                // For adjustments, we'd need to track the previous quantity
                return $this->resource->stock_change ?? null;
            default:
                return null;
        }
    }

    /**
     * Get the operation type description
     */
    private function getOperationType(): string
    {
        if ($this->is_adjustment) {
            return 'adjustment';
        }

        return $this->type;
    }

    /**
     * Get affected units information
     */
    private function getAffectedUnits(): array
    {
        $units = ['base_unit'];

        if ($this->unit_id && $this->relationLoaded('unit')) {
            $units[] = $this->unit->unit_name;
        }

        return $units;
    }
}
