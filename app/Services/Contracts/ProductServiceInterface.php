<?php

namespace App\Services\Contracts;

use App\Http\Resources\Products\ProductCollection;
use App\Http\Resources\Products\ProductResource;

interface ProductServiceInterface
{
    function getAllProducts(array $filters = []): ProductCollection;

    function getProductById(int $id): ProductResource;

    public function getFilterOptions(): array;

    public function deleteById(int $id): void;

    public function createProduct(array $data): ProductResource;

    public function updateProduct(int $id, array $data): ProductResource;

    public function getFeaturedProducts(): array;

    public function getLowStockProducts(int $threshold = 10): array;

    public function getExpiringSoonProducts(int $days = 30): array;
}
