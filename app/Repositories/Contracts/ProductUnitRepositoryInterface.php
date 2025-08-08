<?php

namespace App\Repositories\Contracts;

use App\Models\ProductUnit;

interface ProductUnitRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find product unit by product ID and unit ID
     */
    public function findByProductAndUnit(int $productId, int $unitId): ?ProductUnit;
}
