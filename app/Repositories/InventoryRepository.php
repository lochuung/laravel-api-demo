<?php

namespace App\Repositories;

use App\Models\InventoryTransaction;
use App\Repositories\Contracts\InventoryRepositoryInterface;

class InventoryRepository extends BaseRepository implements InventoryRepositoryInterface
{
    public function __construct(InventoryTransaction $model)
    {
        parent::__construct($model);
    }
}
