<?php

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\StockBatch;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Increase stock of a product in a warehouse and log the movement.
     */
    public function increaseStock(
        int $productId,
        int $warehouseId,
        int $qty,
        StockMovementType $type,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?int $batchId = null,
        ?string $notes = null
    ): StockMovement {
        return $this->adjustStock($productId, $warehouseId, $qty, $type, $referenceType, $referenceId, $batchId, $notes);
    }

    /**
     * Decrease stock of a product in a warehouse and log the movement.
     */
    public function decreaseStock(
        int $productId,
        int $warehouseId,
        int $qty,
        StockMovementType $type,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?int $batchId = null,
        ?string $notes = null
    ): StockMovement {
        return $this->adjustStock($productId, $warehouseId, -$qty, $type, $referenceType, $referenceId, $batchId, $notes);
    }

    /**
     * Create a stock batch entry (used on goods receipt).
     */
    public function createBatch(
        int $productId,
        int $warehouseId,
        ?string $batchNumber,
        int $qty,
        ?string $receivedDate,
        ?int $supplierId = null,
        ?string $expiryDate = null,
        ?string $notes = null
    ): StockBatch {
        return StockBatch::create([
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'batch_number' => $batchNumber ?: ('BATCH-' . now()->format('YmdHis')),
            'supplier_id' => $supplierId,
            'received_date' => $receivedDate ?: now()->toDateString(),
            'initial_qty' => $qty,
            'remaining_qty' => $qty,
            'expiry_date' => $expiryDate,
            'notes' => $notes,
        ]);
    }

    protected function adjustStock(
        int $productId,
        int $warehouseId,
        int $qtyChange,
        StockMovementType $type,
        ?string $referenceType,
        ?int $referenceId,
        ?int $batchId,
        ?string $notes
    ): StockMovement {
        return DB::transaction(function () use ($productId, $warehouseId, $qtyChange, $type, $referenceType, $referenceId, $batchId, $notes) {
            $pivot = DB::table('product_warehouse')
                ->where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->lockForUpdate()
                ->first();

            $stockBefore = $pivot->stock ?? 0;
            $stockAfter = $stockBefore + $qtyChange;

            if ($pivot) {
                DB::table('product_warehouse')
                    ->where('product_id', $productId)
                    ->where('warehouse_id', $warehouseId)
                    ->update(['stock' => $stockAfter, 'updated_at' => now()]);
            } else {
                DB::table('product_warehouse')->insert([
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    'stock' => $stockAfter,
                    'min_stock' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => $type->value,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'batch_id' => $batchId,
                'qty' => $qtyChange,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'notes' => $notes,
                'created_by' => auth()->id(),
            ]);
        });
    }
}
