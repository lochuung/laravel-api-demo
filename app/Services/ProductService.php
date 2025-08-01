<?php

namespace App\Services;

use App\Exceptions\BadRequestException;
use App\Helpers\CodeGenerator;
use App\Http\Resources\Products\ProductCollection;
use App\Http\Resources\Products\ProductResource;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Contracts\ProductServiceInterface;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class ProductService implements ProductServiceInterface
{
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @throws AuthorizationException
     */
    function getAllProducts(array $filters = []): ProductCollection
    {
        if (Gate::denies('viewAny', Product::class)) {
            throw new AuthorizationException(__('exception.unauthorized'));
        }

        $perPage = $filters['per_page'] ?? 10;
        $products = $this->productRepository->searchAndFilter($filters, $perPage);

        return new ProductCollection($products);
    }

    /**
     * @throws AuthorizationException|BadRequestException
     */
    function getProductById(int $id): ProductResource
    {
        $product = $this->productRepository->findWithCategory($id);
        if (!$product) {
            throw new BadRequestException(__('exception.not_found', ['name' => "product"]), 404);
        }

        if (Gate::denies('view', $product)) {
            throw new AuthorizationException(__('exception.unauthorized'));
        }

        return new ProductResource($product);
    }

    /**
     * @throws AuthorizationException
     */
    public function getFilterOptions(): array
    {
        if (Gate::denies('viewAny', Product::class)) {
            throw new AuthorizationException(__('exception.unauthorized'));
        }

        return $this->productRepository->getFilterOptions();
    }

    /**
     * @throws AuthorizationException|BadRequestException
     */
    public function deleteById(int $id): void
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new BadRequestException(__('exception.not_found', ['name' => "product"]), 404);
        }

        if (Gate::denies('delete', $product)) {
            throw new AuthorizationException(__('exception.unauthorized'));
        }

        $this->productRepository->delete($id);
    }

    /**
     * @throws AuthorizationException
     * @throws Exception
     */
    public function createProduct(array $data): ProductResource
    {
        if (Gate::denies('create', Product::class)) {
            throw new AuthorizationException(__('exception.unauthorized'));
        }

        $codePrefix = $data['code_prefix'] ?? 'PRD';
        $code = CodeGenerator::for($codePrefix);

        $data['code'] = $code;
        if (!isset($data['barcode'])) {
            $data['barcode'] = $data['code']; // Use code as barcode if not provided
        }

        $product = $this->productRepository->create($data);
        return new ProductResource($product);
    }

    /**
     * @throws AuthorizationException|BadRequestException
     * @throws Exception
     */
    public function updateProduct(int $id, array $data): ProductResource
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new BadRequestException(__('exception.not_found', ['name' => "product"]), 404);
        }

        if (Gate::denies('update', $product)) {
            throw new AuthorizationException(__('exception.unauthorized'));
        }

        $data['code'] = $product->code; // Keep the existing code if it already has the prefix
        $codePrefix = $data['code_prefix'] ?? 'PRD';
        if (!str_contains($product->code, $codePrefix)) {
            $data['code'] = CodeGenerator::for($codePrefix);
        }

        $updatedProduct = $this->productRepository->update($id, $data);
        return new ProductResource($updatedProduct);
    }

    public
    function getFeaturedProducts(): array
    {
        $products = $this->productRepository->getFeaturedProducts();
        return ProductResource::collection($products)->toArray(request());
    }

    public
    function getLowStockProducts(int $threshold = 10): array
    {
        $products = $this->productRepository->getLowStockProducts($threshold);
        return ProductResource::collection($products)->toArray(request());
    }

    public
    function getExpiringSoonProducts(int $days = 30): array
    {
        $products = $this->productRepository->getExpiringSoonProducts($days);
        return ProductResource::collection($products)->toArray(request());
    }
}
