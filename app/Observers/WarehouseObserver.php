<?php

namespace App\Observers;

use App\Models\Warehouse;

class WarehouseObserver
{
    /**
     * Handle the Warehouse "creating" event.
     */
    public function creating(Warehouse $warehouse): void
    {
        if (empty($warehouse->code)) {
            $latest = Warehouse::withTrashed()->orderBy('id', 'desc')->first();
            $nextId = $latest ? $latest->id + 1 : 1;
            $warehouse->code = 'GD-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }
    }
}
