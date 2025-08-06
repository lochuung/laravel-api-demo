<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Products\ProductIndexRequest;
use App\Http\Requests\Products\ProductRequest;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductController extends BaseController
{
    private ProductServiceInterface $productService;

    /**
     * @param ProductServiceInterface $productService
     */
    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ProductIndexRequest $request): JsonResponse
    {
        return response()->json(
            $this->productService
                ->getAllProducts($request->validated())
        );
    }

    /**
     * Get filter options for products
     */
    public function filterOptions(): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            new JsonResource(
                $this->productService->getFilterOptions()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->productService->createProduct($request->validated())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        return $this->apiSuccessSingleResponse(
            $this->productService->getProductById((int)$id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id): JsonResponse
    {
        $product = $this->productService->updateProduct((int)$id, $request->validated());
        return $this->apiSuccessSingleResponse($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->productService->deleteById((int)$id);
        return $this->apiSuccessSingleResponse();
    }
}
