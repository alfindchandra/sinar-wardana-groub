<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'code', 'store_name', 'owner_name', 'phone', 'email', 'address', 'city', 'province', 'postal_code', 'latitude', 'longitude', 'area', 'sales_person_id', 'credit_limit', 'payment_term_days', 'customer_type', 'notes', 'is_active'
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'credit_limit' => 'decimal:2',
        'payment_term_days' => 'integer',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salesPerson()
    {
        return $this->belongsTo(SalesPerson::class);
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function receivables()
    {
        return $this->hasMany(Receivable::class);
    }

    public function onlineOrders()
    {
        return $this->hasMany(OnlineOrder::class);
    }

    public function salesVisits()
    {
        return $this->hasMany(SalesVisit::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByArea($query, $area)
    {
        return $query->where('area', $area);
    }

    public function scopeBySalesPerson($query, $salesPersonId)
    {
        return $query->where('sales_person_id', $salesPersonId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('store_name', 'like', "%{$term}%")
                ->orWhere('owner_name', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%");
        });
    }

    public function getFormattedCreditLimitAttribute()
    {
        return 'Rp ' . number_format($this->credit_limit, 0, ',', '.');
    }

    public function getOutstandingReceivablesAttribute()
    {
        return $this->receivables()->where('status', '!=', 'paid')->sum('remaining_amount');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->code)) {
                $latest = self::orderBy('id', 'desc')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $model->code = 'CUST-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
