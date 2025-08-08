<?php

namespace App\Services;

use App\Exceptions\BadRequestException;
use App\Http\Resources\Products\ProductCollection;
use App\Http\Resources\Products\ProductResource;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Contracts\ProductServiceInterface;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

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
    public function getAllProducts(array $filters = []): ProductCollection
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
    public function getProductById(int $id): ProductResource
    {
        $product = $this->productRepository->findWithDetails($id);
        if (!$product) {
            throw new BadRequestException(__('exception.not_found', ['name' => "product"]), Response::HTTP_NOT_FOUND);
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
            throw new BadRequestException(__('exception.not_found', ['name' => "product"]), Response::HTTP_NOT_FOUND);
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
            throw new BadRequestException(__('exception.not_found', ['name' => "product"]), Response::HTTP_NOT_FOUND);
        }

        if (Gate::denies('update', $product)) {
            throw new AuthorizationException(__('exception.unauthorized'));
        }

        // keep the base unit data
        $data['price'] = $product->price;
        $data['base_unit_id'] = $product->base_unit_id;
        $data['base_unit'] = $product->base_unit ?? null;
        $data['base_sku'] = $product->base_sku;

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
    function getLowStockProducts(
        int $threshold = 10
    ): array {
        $products = $this->productRepository->getLowStockProducts($threshold);
        return ProductResource::collection($products)->toArray(request());
    }

    public
    function getExpiringSoonProducts(
        int $days = 30
    ): array {
        $products = $this->productRepository->getExpiringSoonProducts($days);
        return ProductResource::collection($products)->toArray(request());
    }
}
