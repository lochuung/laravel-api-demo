<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\ProductUnits\ProductUnitResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // Basic info
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float)$this->price,
            'cost' => (float)$this->cost,
            'stock' => (float)$this->stock,
            'min_stock' => (int)$this->min_stock,
            'expiry_date' => $this->expiry_date?->format('Y-m-d'),
            'image' => $this->image ? asset($this->image) : null,
            'is_active' => (bool)$this->is_active,

            // New fields
            'base_sku' => $this->base_sku,
            'base_unit' => $this->base_unit,
            'base_unit_id' => $this->base_unit_id,
            'base_barcode' => $this->base_barcode,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'units' => ProductUnitResource::collection($this->whenLoaded('units')),

            // Relations
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', fn() => new CategoryResource($this->category)),

            // Computed / UI helper fields
            'stock_status' => $this->getStockStatus(),
            'margin_percentage' => $this->getMarginPercentage(),
            'is_expired' => $this->isExpired(),
            'days_until_expiry' => $this->getDaysUntilExpiry(),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Get stock status based on stock level
     */
    private function getStockStatus(): string
    {
        $stock = $this->stock ?? 0;

        if ($stock <= 0) {
            return 'out_of_stock';
        } elseif ($stock <= 10) {
            return 'low_stock';
        } elseif ($stock <= 50) {
            return 'medium_stock';
        } else {
            return 'in_stock';
        }
    }

    /**
     * Calculate margin percentage
     */
    private function getMarginPercentage(): ?float
    {
        if (!$this->price || !$this->cost || $this->price <= 0) {
            return null;
        }

        return round((($this->price - $this->cost) / $this->price) * 100, 2);
    }

    /**
     * Check if product is expired
     */
    private function isExpired(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->isPast();
    }

    /**
     * Get days until expiry
     */
    private function getDaysUntilExpiry(): ?int
    {
        if (!$this->expiry_date) {
            return null;
        }

        return now()->diffInDays($this->expiry_date, false);
    }
}
