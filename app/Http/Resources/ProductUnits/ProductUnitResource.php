<?php

namespace App\Http\Resources\ProductUnits;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductUnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseStock = $this->product->stock ?? 0;
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'unit_name' => $this->unit_name,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'conversion_rate' => (float)$this->conversion_rate,
            'selling_price' => (float)$this->selling_price,
            'is_base_unit' => (bool)$this->is_base_unit,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'stock_in_unit' => round($baseStock * $this->conversion_rate, 2),
        ];
    }
}
