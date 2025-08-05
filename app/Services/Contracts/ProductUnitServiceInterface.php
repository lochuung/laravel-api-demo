<?php

namespace App\Services\Contracts;

use App\Http\Resources\ProductUnits\ProductUnitCollection;
use App\Http\Resources\ProductUnits\ProductUnitResource;

interface ProductUnitServiceInterface
{
    public function getProductUnits(int $productId): ProductUnitCollection;

    public function getProductUnitById(int $unitId): ProductUnitResource;

    public function createProductUnit(int $productId, array $data): ProductUnitResource;

    public function updateProductUnit(int $unitId, array $data): ProductUnitResource;

    public function deleteProductUnit(int $unitId): void;
}
