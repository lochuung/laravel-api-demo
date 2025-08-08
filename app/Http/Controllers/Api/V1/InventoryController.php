<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Inventory\InventoryAdjustRequest;
use App\Http\Requests\Inventory\InventoryExportRequest;
use App\Http\Requests\Inventory\InventoryImportRequest;
use App\Http\Resources\Inventory\InventoryOperationResource;
use App\Http\Resources\Inventory\InventoryStatsResource;
use App\Http\Resources\Inventory\InventoryTransactionCollection;
use App\Services\Contracts\InventoryServiceInterface;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InventoryController extends BaseController
{
    public function __construct(
        private readonly InventoryServiceInterface $inventoryService
    ) {
    }

    /**
     * Get inventory statistics
     * @throws AuthorizationException
     */
    public function stats(): JsonResponse
    {
        if (Gate::denies('is-admin')) {
            throw new AuthorizationException(__('exception.unauthorized'));
        }

        $stats = $this->inventoryService->getInventoryStats();
        return $this->apiSuccessSingleResponse(
            new InventoryStatsResource($stats)
        );
    }

    /**
     * Import inventory
     * @throws AuthorizationException
     */
    public function import(InventoryImportRequest $request): JsonResponse
    {
        if (Gate::denies('is-admin')) {
            throw new AuthorizationException(__('exception.unauthorized'));
        }

        $transaction = $this->inventoryService->importInventory(
            $request->product_id,
            $request->quantity,
            $request->price,
            $request->notes
        );

        return $this->apiSuccessSingleResponse(
            new InventoryOperationResource($transaction),
            __('inventory.import.success')
        );
    }

    /**
     * Export inventory
     * @throws AuthorizationException
     */
    public function export(InventoryExportRequest $request): JsonResponse
    {
        if (Gate::denies('is-admin')) {
            throw new AuthorizationException(__('exception.unauthorized'));
        }

        $transaction = $this->inventoryService->exportInventory(
            $request->product_id,
            $request->quantity,
            $request->unit_id,
            $request->order_id,
            $request->notes
        );

        return $this->apiSuccessSingleResponse(
            new InventoryOperationResource($transaction),
            __('inventory.export.success')
        );
    }

    /**
     * Adjust inventory
     * @throws AuthorizationException
     */
    public function adjust(InventoryAdjustRequest $request): JsonResponse
    {
        if (Gate::denies('is-admin')) {
            throw new AuthorizationException(__('exception.unauthorized'));
        }

        try {
            $transaction = $this->inventoryService->adjustInventory(
                $request->product_id,
                $request->new_quantity,
                $request->reason
            );

            return $this->apiSuccessSingleResponse(
                new InventoryOperationResource($transaction),
                __('inventory.adjust.success')
            );
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'INVENTORY_ADJUST_ERROR'
            ], 500);
        }
    }

    /**
     * Get product inventory history
     * @throws AuthorizationException
     */
    public function productHistory(Request $request, int $productId): JsonResponse
    {
        if (Gate::denies('is-admin')) {
            throw new AuthorizationException(__('exception.unauthorized'));
        }

        $filters = $request->only(['type', 'date_from', 'date_to', 'limit']);
        $transactions = $this->inventoryService->getProductInventoryHistory($productId, $filters);

        return response()->json(
            new InventoryTransactionCollection($transactions)
        );
    }
}
