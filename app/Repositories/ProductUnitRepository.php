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
}
