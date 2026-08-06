<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Receivable extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_id', 'customer_id', 'amount', 'paid_amount', 'remaining_amount', 'due_date', 'status', 'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
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

    public function scopeByAgingRange($query, $minDays, $maxDays = null)
    {
        $query->where('due_date', '<', now()->toDateString());
        if ($maxDays === null) {
            return $query->whereRaw('DATEDIFF(NOW(), due_date) > ?', [$minDays]);
        }
        return $query->whereRaw('DATEDIFF(NOW(), due_date) BETWEEN ? AND ?', [$minDays, $maxDays]);
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->status === 'paid' || $this->due_date >= now()->toDateString()) {
            return 0;
        }
        return Carbon::parse($this->due_date)->diffInDays(now());
    }

    public function getAgingCategoryAttribute()
    {
        $days = $this->days_overdue;
        if ($days <= 0) return 'Current';
        if ($days <= 30) return '1-30 Days';
        if ($days <= 60) return '31-60 Days';
        if ($days <= 90) return '61-90 Days';
        return '> 90 Days';
    }
}
