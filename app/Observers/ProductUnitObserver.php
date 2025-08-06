<?php

namespace App\Observers;

use App\Helpers\CodeGenerator;
use App\Models\ProductUnit;
use App\Utilities\BarcodeUtil;
use Exception;

class ProductUnitObserver
{

    /**
     * Handle the ProductUnit "saving" event.
     * @throws Exception
     */
    public function saving(ProductUnit $productUnit): void
    {
        if (empty($productUnit->sku)) {
            $productUnit->sku = CodeGenerator::for("PRD") . $productUnit->id . '-'
                . ProductUnit::normalize($productUnit->unit_name);
        }

        if (empty($productUnit->barcode)) {
            $productUnit->barcode = BarcodeUtil::generateCode128($productUnit->sku);
        }
    }

    /**
     * Handle the ProductUnit "saved" event.
     *
     */
    public function saved(ProductUnit $productUnit)
    {
        if ($productUnit->is_base_unit) {
            $productUnit->product()->update([
                'base_sku' => $productUnit->sku,
                'base_unit' => $productUnit->unit_name,
                'base_barcode' => $productUnit->barcode,
                'price' => $productUnit->selling_price,
                'base_unit_id' => $productUnit->id,
            ]);
        }
    }


    /**
     * Handle the ProductUnit "created" event.
     */
    public function created(ProductUnit $productUnit): void
    {
        //
    }

    /**
     * Handle the ProductUnit "updated" event.
     */
    public function updated(ProductUnit $productUnit): void
    {
        //
    }

    /**
     * Handle the ProductUnit "deleted" event.
     */
    public function deleted(ProductUnit $productUnit): void
    {
        //
    }

    /**
     * Handle the ProductUnit "restored" event.
     */
    public function restored(ProductUnit $productUnit): void
    {
        //
    }

    /**
     * Handle the ProductUnit "force deleted" event.
     */
    public function forceDeleted(ProductUnit $productUnit): void
    {
        //
    }
}
