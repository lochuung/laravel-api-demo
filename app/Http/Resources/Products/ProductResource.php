<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\Category\CategoryResource;
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
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'price' => $this->price,
            'cost' => $this->cost,
            'stock' => $this->stock,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'expiry_date' => $this->expiry_date?->format('Y-m-d'),
            'image' => $this->image ? asset($this->image) : null,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', fn() => new CategoryResource($this->category)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
