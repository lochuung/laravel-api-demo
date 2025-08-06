<?php

namespace App\Services;

use App\Exceptions\BadRequestException;
use App\Http\Resources\ProductUnits\ProductUnitCollection;
use App\Http\Resources\ProductUnits\ProductUnitResource;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\ProductUnitRepositoryInterface;
use App\Services\Contracts\ProductUnitServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

readonly class ProductUnitService implements ProductUnitServiceInterface
{
    public function __construct(
        private ProductUnitRepositoryInterface $productUnitRepository,
        private ProductRepositoryInterface     $productRepository
    )
    {
    }

    public function getProductUnits(int $productId): ProductUnitCollection
    {
        // Verify product exists
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new ModelNotFoundException(__('exception.not_found', ['model' => 'Product']));
        }

        $units = $this->productUnitRepository->findWhere(['product_id' => $productId]);

        return new ProductUnitCollection($units);
    }

    public function getProductUnitById(int $unitId): ProductUnitResource
    {
        $unit = $this->productUnitRepository->find($unitId);

        if (!$unit) {
            throw new ModelNotFoundException(__('exception.not_found', ['model' => 'ProductUnit']));
        }

        return new ProductUnitResource($unit);
    }

    /**
     * @throws \Throwable
     */
    public function createProductUnit(int $productId, array $data): ProductUnitResource
    {
        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new ModelNotFoundException(__('exception.not_found', ['model' => 'Product']));
        }

        return DB::transaction(function () use ($product, $data) {
            $data['product_id'] = $product->id;

            // Handle base unit logic before creation
            if (!empty($data['is_base_unit'])) {
                $data = $this->handleBaseUnitUpdate($product, null, $data);
            }

            $unit = $this->productUnitRepository->create($data);

            // Update product's base_unit_id after creating new base unit
            if (!empty($data['is_base_unit'])) {
                $product->update(['base_unit_id' => $unit->id]);
            }

            return new ProductUnitResource($unit);
        });
    }

    /**
     * @throws \Throwable
     */
    public function updateProductUnit(int $unitId, array $data): ProductUnitResource
    {
        $unit = $this->productUnitRepository->find($unitId);

        if (!$unit) {
            throw new ModelNotFoundException(__('exception.not_found', ['model' => 'ProductUnit']));
        }

        return DB::transaction(function () use ($unit, $data) {
            $product = $unit->product;

            if (!empty($data['is_base_unit'])) {
                $data = $this->handleBaseUnitUpdate($product, $unit, $data);
            }

            $updatedUnit = $this->productUnitRepository->update($unit->id, $data);

            return new ProductUnitResource($updatedUnit);
        });
    }

    /**
     * Handle logic for converting a unit to the new base unit:
     * - Unset old base unit
     * - Convert stock
     * - Update conversion rates
     * - Set conversion_rate = 1.0
     */
    private function handleBaseUnitUpdate($product, $newBaseUnit = null, array $data): array
    {
        // 1. Unset all current base units
        $existingBaseUnits = $this->productUnitRepository->findWhere([
            'product_id' => $product->id,
            'is_base_unit' => true,
        ]);

        foreach ($existingBaseUnits as $baseUnit) {
            if (!$newBaseUnit || $baseUnit->id !== $newBaseUnit->id) {
                $this->productUnitRepository->update($baseUnit->id, ['is_base_unit' => false]);
            }
        }

        // 2. Convert product stock to new base unit
        $originalStock = $product->stock;
        $newBaseRate = $data['conversion_rate'] ?? ($newBaseUnit?->conversion_rate ?? 1.0);

        if ($newBaseRate > 0 && $originalStock > 0) {
            $convertedStock = $originalStock * $newBaseRate;
            $product->stock = (int)round($convertedStock);
            $product->min_stock = (int)round($convertedStock * ($product->min_stock / $originalStock));
            $product->save();
        }

        // 3. Set conversion_rate = 1.0 for new base unit
        $data['conversion_rate'] = 1.0;

        // 4. Adjust all other units’ conversion rates
        $excludeId = $newBaseUnit?->id;

        $otherUnits = $this->productUnitRepository->findWhere([
            ['product_id', '=', $product->id],
            ['id', '!=', $excludeId],
        ]);

        foreach ($otherUnits as $other) {
            $adjustedRate = $other->conversion_rate / $newBaseRate;
            $this->productUnitRepository->update($other->id, [
                'conversion_rate' => round($adjustedRate, 4),
            ]);
        }

        return $data;
    }

    /**
     * @throws BadRequestException
     */
    public function deleteProductUnit(int $unitId): void
    {
        $unit = $this->productUnitRepository->find($unitId);

        if (!$unit) {
            throw new ModelNotFoundException(__('exception.not_found', ['model' => 'ProductUnit']));
        }

        // Check if this unit is the base unit for the product
        $product = $this->productRepository->find($unit->product_id);

        if ($product && $product->base_unit_id == $unitId) {
            throw new BadRequestException(__('exception.cannot_delete_base_unit'));
        }

        $this->productUnitRepository->delete($unitId);
    }
}
