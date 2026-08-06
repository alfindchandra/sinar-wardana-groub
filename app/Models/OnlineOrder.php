<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlineOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'customer_id', 'order_date', 'status', 'subtotal', 'discount', 'grand_total', 'notes', 'converted_so_id'
    ];

    protected $casts = [
        'order_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OnlineOrderItem::class);
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'converted_so_id');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->order_number)) {
                $latest = self::orderBy('id', 'desc')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $date = date('Ymd');
                $model->order_number = 'OO-' . $date . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
