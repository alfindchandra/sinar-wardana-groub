<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMutationItem extends Model
{
    protected $fillable = [
        'stock_mutation_id', 'product_id', 'qty'
    ];

    protected $casts = [
        'qty' => 'integer'
    ];

    public function stockMutation()
    {
        return $this->belongsTo(StockMutation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
