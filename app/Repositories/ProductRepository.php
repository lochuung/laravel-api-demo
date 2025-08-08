<?php

namespace App\Repositories;

use App\Helpers\CodeGenerator;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Repositories\Contracts\ProductRepositoryInterface;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * ProductRepository constructor.
     *
     * @param Product $model
     */
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * @throws Throwable
     */
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $unitName = $data['base_unit'] ?? 'units';
            $data['base_sku'] = $data['base_sku'] ?? CodeGenerator::for("PRD") . '-' . ProductUnit::normalize(
                $unitName
            );

            $product = new Product(); // ← create a new instance
            $product->fill($data);
            $product->save();

            // Create the base unit if it doesn't exist
            $baseUnit = ProductUnit::firstOrCreate(
                ['product_id' => $product->id, 'is_base_unit' => true],
                [
                    'product_id' => $product->id,
                    'unit_name' => $unitName,
                    'sku' => $data['base_sku'],
                    'conversion_rate' => 1.0,
                    'selling_price' => $data['price'] ?? 0.0,
                    'is_base_unit' => true,
                ]
            );

            // Update product with base_unit_id
            $product->base_unit_id = $baseUnit->id;
            $product->save();

            return $product; // ← return the created product
        });
    }


    public function findBySku(string $code): ?Product
    {
        /** @var Product|null $product */
        $product = $this->findBy('base_sku', $code);
        return $product;
    }

    public function findByBarcode(string $barcode): ?Product
    {
        return $this->newQuery()
            ->whereHas('units', function ($query) use ($barcode) {
                $query->where('barcode', $barcode);
            })
            ->first();
    }

    public function getActiveProducts(): Collection
    {
        return $this->findWhere(['is_active' => true]);
    }

    public function getFeaturedProducts(): Collection
    {
        return $this->findWhere(['is_featured' => true, 'is_active' => true]);
    }

    public function getProductsByCategory(int $categoryId): Collection
    {
        return $this->findWhere(['category_id' => $categoryId, 'is_active' => true]);
    }

    public function searchByName(string $name): Collection
    {
        return $this->newQuery()
            ->where('name', 'LIKE', "%{$name}%")
            ->where('is_active', true)
            ->get();
    }

    public function getLowStockProducts(int $threshold = 10): Collection
    {
        return $this->newQuery()
            ->where('stock', '<=', $threshold)
            ->where('is_active', true)
            ->get();
    }

    public function getExpiringSoonProducts(int $days = 30): Collection
    {
        return $this->newQuery()
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now())
            ->where('is_active', true)
            ->get();
    }

    public function searchAndFilter(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->newQuery()->with('category');

        // Search by name, code, or description
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('base_sku', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filter by category
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Filter by active status
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        // Filter by price range
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // filter by code_prefix
        if (!empty($filters['code_prefix'])) {
            $query->where('base_sku', 'LIKE', "{$filters['code_prefix']}%");
        }

        // expiring_soon_days
        if (!empty($filters['expiring_soon_days'])) {
            $days = (int)$filters['expiring_soon_days'];

            $query->whereNotNull('expiry_date')
                ->where('expiry_date', '<=', now()->addDays($days))
                ->where('expiry_date', '>', now());
        }

        // is_expired
        if (isset($filters['is_expired'])) {
            if ($filters['is_expired']) {
                $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '<=', now());
            } else {
                $query->where(function ($q) {
                    $q->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>', now());
                });
            }
        }

        // stock_threshold
        if (isset($filters['stock_threshold'])) {
            $threshold = (int)$filters['stock_threshold'];
            $query->where('stock', '<=', $threshold);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    public function getFilterOptions(): array
    {
        return [
            'categories' => Category::all()->pluck('name', 'id'),
            'price_range' => [
                'min' => $this->newQuery()->min('price') ?? 0,
                'max' => $this->newQuery()->max('price') ?? 1000,
            ],
            'code_prefixes' => CodeGenerator::getSuggestedPrefixes()
        ];
    }

    public function findWithDetails(int $id): ?Product
    {
        return $this->newQuery()->with(['category', 'units'])->find($id);
    }

    /**
     * Update product stock
     */
    public function updateStock(int $productId, int $newStock): bool
    {
        return $this->model->where('id', $productId)->update(['stock' => $newStock]);
    }

    /**
     * Get products count
     */
    public function getProductsCount(): int
    {
        return $this->model->count();
    }

    /**
     * Get total stock value
     */
    public function getTotalStockValue(): float
    {
        return $this->model->selectRaw('SUM(stock * cost) as total_value')->value('total_value') ?? 0;
    }

    /**
     * Get out of stock products count
     */
    public function getOutOfStockProductsCount(): int
    {
        return $this->model->where('stock', 0)->count();
    }

    public function getProductsBelowMinimumStock()
    {
        return $this->model->where('stock', '<=', 'min_stock')->get();
    }
}
