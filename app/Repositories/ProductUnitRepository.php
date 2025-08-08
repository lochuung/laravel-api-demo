<?php

namespace App\Repositories;

use App\Models\ProductUnit;
use App\Repositories\Contracts\ProductUnitRepositoryInterface;

class ProductUnitRepository extends BaseRepository implements ProductUnitRepositoryInterface
{

    public function __construct(ProductUnit $model)
    {
        parent::__construct($model);
    }

    /**
     * Find product unit by product ID and unit ID
     */
    public function findByProductAndUnit(int $productId, int $unitId): ?ProductUnit
    {
        return $this->model->where('product_id', $productId)
            ->where('id', $unitId)
            ->first();
    }
}
