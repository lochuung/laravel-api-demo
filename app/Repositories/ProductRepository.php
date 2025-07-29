<?php

namespace App\Repositories;

class ProductRepository extends BaseRepository implements Contracts\ProductRepositoryInterface
{
    /**
     * ProductRepository constructor.
     *
     * @param \App\Models\Product $model
     */
    public function __construct(\App\Models\Product $model)
    {
        parent::__construct($model);
    }

}
