<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductObserver
{
    /**
     * Handle the Product "creating" event.
     */
    public function creating(Product $product): void
    {
        if (empty($product->slug)) {
            $product->slug = Str::slug($product->name);
        }

        if (empty($product->sku)) {
            $latest = Product::withTrashed()->orderBy('id', 'desc')->first();
            $nextId = $latest ? $latest->id + 1 : 1;
            $product->sku = 'PRD-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Handle the Product "updating" event.
     */
    public function updating(Product $product): void
    {
        if ($product->isDirty('name')) {
            $product->slug = Str::slug($product->name);
        }
    }
}
