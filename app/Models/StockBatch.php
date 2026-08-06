<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockBatch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id', 'warehouse_id', 'batch_number', 'supplier_id', 'received_date', 'initial_qty', 'remaining_qty', 'expiry_date', 'notes'
    ];

    protected $casts = [
        'received_date' => 'date',
        'initial_qty' => 'integer',
        'remaining_qty' => 'integer',
        'expiry_date' => 'date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'batch_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('remaining_qty', '>', 0);
    }
}
