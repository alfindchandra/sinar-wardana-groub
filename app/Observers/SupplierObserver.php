<?php

namespace App\Observers;

use App\Models\Supplier;

class SupplierObserver
{
    /**
     * Handle the Supplier "creating" event.
     */
    public function creating(Supplier $supplier): void
    {
        if (empty($supplier->code)) {
            $latest = Supplier::withTrashed()->orderBy('id', 'desc')->first();
            $nextId = $latest ? $latest->id + 1 : 1;
            $supplier->code = 'SUP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }
    }
}
