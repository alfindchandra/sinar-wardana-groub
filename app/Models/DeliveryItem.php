<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryItem extends Model
{
    protected $fillable = [
        'delivery_id', 'sales_order_item_id', 'product_id', 'qty'
    ];

    protected $casts = [
        'qty' => 'integer'
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function salesOrderItem()
    {
        return $this->belongsTo(SalesOrderItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
