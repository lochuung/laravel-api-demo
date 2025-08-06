<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySku(string $code): ?Product;

    public function findByBarcode(string $barcode): ?Product;

    public function getActiveProducts(): Collection;

    public function getFeaturedProducts(): Collection;

    public function getProductsByCategory(int $categoryId): Collection;

    public function searchByName(string $name): Collection;

    public function getLowStockProducts(int $threshold = 10): Collection;

    public function getExpiringSoonProducts(int $days = 30): Collection;

    public function searchAndFilter(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    public function getFilterOptions(): array;

    public function findWithDetails(int $id): ?Product;
}
