<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $fillable = [
        'product_id', 'warehouse_id', 'old_stock', 'new_stock', 'change_qty', 'reason', 'reference_type', 'reference_id', 'changed_by'
    ];

    protected $casts = [
        'old_stock' => 'integer',
        'new_stock' => 'integer',
        'change_qty' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
