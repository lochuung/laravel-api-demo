<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'date' => $this->date?->format('Y-m-d H:i:s'),
            'order_id' => $this->order_id,
            'notes' => $this->notes,
            'unit_id' => $this->unit_id,
            'is_adjustment' => $this->is_adjustment,
            'formatted_type' => $this->getFormattedTypeAttribute(),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

            // Relationships
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'base_sku' => $this->product->base_sku,
                    'base_unit' => $this->product->base_unit,
                ];
            }),

            'unit' => $this->whenLoaded('unit', function () {
                return [
                    'id' => $this->unit->id,
                    'unit_name' => $this->unit->unit_name,
                    'sku' => $this->unit->sku,
                    'conversion_rate' => $this->unit->conversion_rate,
                ];
            }),

            'order' => $this->whenLoaded('order', function () {
                return [
                    'id' => $this->order->id,
                    'order_number' => $this->order->order_number,
                    'order_date' => $this->order->order_date?->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }
}
