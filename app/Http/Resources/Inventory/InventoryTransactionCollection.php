<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InventoryTransactionCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = InventoryTransactionResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->collection->count(),
                'has_imports' => $this->collection->where('type', 'import')->count() > 0,
                'has_exports' => $this->collection->where('type', 'export')->count() > 0,
                'has_adjustments' => $this->collection->where('is_adjustment', true)->count() > 0,
                'total_import_value' => $this->collection->where('type', 'import')->sum('amount'),
                'total_export_value' => $this->collection->where('type', 'export')->sum('amount'),
                'net_quantity_change' => $this->calculateNetQuantityChange(),
                'date_range' => [
                    'earliest' => $this->collection->min('date'),
                    'latest' => $this->collection->max('date'),
                ],
                'generated_at' => now()->format('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * Calculate the net quantity change from all transactions
     */
    private function calculateNetQuantityChange(): int
    {
        $imports = $this->collection->where('type', 'import')->sum('quantity');
        $exports = $this->collection->where('type', 'export')->sum('quantity');
        $adjustments = $this->collection->where('is_adjustment', true)->sum(function ($transaction) {
            // For adjustments, the quantity represents the final amount, not the change
            // We would need additional logic here if we track the change amount separately
            return 0; // Placeholder - adjust based on actual adjustment logic
        });

        return $imports - $exports + $adjustments;
    }

    /**
     * Additional metadata when the collection is wrapped
     */
    public function with(Request $request): array
    {
        return [
            'links' => [
                'self' => $request->url(),
            ],
            'filters_applied' => $request->only(['type', 'date_from', 'date_to', 'limit']),
        ];
    }
}
