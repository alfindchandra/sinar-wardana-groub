<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number', 'sales_order_id', 'customer_id', 'invoice_date', 'due_date', 'status', 'subtotal', 'discount', 'tax', 'grand_total', 'paid_amount', 'remaining_amount', 'notes', 'created_by'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2'
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function receivable()
    {
        return $this->hasOne(Receivable::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Receivable::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString())
                     ->where('status', '!=', 'paid');
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->invoice_number)) {
                $latest = self::orderBy('id', 'desc')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $date = date('Ymd');
                $model->invoice_number = 'INV-' . $date . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
