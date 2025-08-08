<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\ProductUnits\ProductUnitRequest;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Services\Contracts\ProductUnitServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductUnitController extends BaseController
{
    private ProductUnitServiceInterface $productUnitService;

    public function __construct(ProductUnitServiceInterface $productUnitService)
    {
        $this->productUnitService = $productUnitService;
    }

    /**
     * Display a listing of the product units for a specific product.
     */
    public function index(Product $product): JsonResponse
    {
        $units = $this->productUnitService->getProductUnits($product->id);
        return response()->json($units);
    }

    /**
     * Store a newly created product unit in storage.
     */
    public function store(ProductUnitRequest $request, Product $product): JsonResponse
    {
        $unit = $this->productUnitService->createProductUnit($product->id, $request->validated());
        return $this->apiSuccessSingleResponse($unit);
    }

    /**
     * Display the specified product unit.
     */
    public function show(Product $product, ProductUnit $unit): JsonResponse
    {
        // Ensure the unit belongs to the product
        if ($unit->product_id !== $product->id) {
            abort(Response::HTTP_FORBIDDEN, __('exception.unit_not_belongs_to_product'));
        }

        $unitResource = $this->productUnitService->getProductUnitById($unit->id);
        return $this->apiSuccessSingleResponse($unitResource);
    }

    /**
     * Update the specified product unit in storage.
     */
    public function update(ProductUnitRequest $request, ProductUnit $unit): JsonResponse
    {
        $updatedUnit = $this->productUnitService->updateProductUnit($unit->id, $request->validated());
        return $this->apiSuccessSingleResponse($updatedUnit);
    }

    /**
     * Remove the specified product unit from storage.
     */
    public function destroy(ProductUnit $unit): JsonResponse
    {
        $this->productUnitService->deleteProductUnit($unit->id);
        return $this->apiSuccessSingleResponse();
    }
}
