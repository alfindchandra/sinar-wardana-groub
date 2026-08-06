<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineOrderItem extends Model
{
    protected $fillable = [
        'online_order_id', 'product_id', 'qty', 'price', 'subtotal'
    ];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function onlineOrder()
    {
        return $this->belongsTo(OnlineOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
