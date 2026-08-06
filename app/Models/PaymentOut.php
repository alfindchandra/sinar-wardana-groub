<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentOut extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payment_number', 'payable_id', 'supplier_id', 'payment_date', 'amount', 'payment_method', 'reference_number', 'bank_name', 'notes', 'paid_by'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function payable()
    {
        return $this->belongsTo(Payable::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->payment_number)) {
                $latest = self::orderBy('id', 'desc')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $date = date('Ymd');
                $model->payment_number = 'POUT-' . $date . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
