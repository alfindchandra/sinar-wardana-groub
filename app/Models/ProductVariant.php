<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'name', 'extra_price', 'stock', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'extra_price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Harga jual varian ini = harga umum produk + selisih (biasanya 0).
     */
    public function price(): float
    {
        return (float) $this->product->sell_price + (float) $this->extra_price;
    }
}
