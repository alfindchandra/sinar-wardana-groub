<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesVisit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sales_person_id', 'customer_id', 'visit_date', 'check_in_time', 'check_in_latitude', 'check_in_longitude', 'check_out_time', 'check_out_latitude', 'check_out_longitude', 'photo', 'notes', 'sales_order_id', 'created_by'
    ];

    protected $casts = [
        'visit_date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'check_in_latitude' => 'decimal:7',
        'check_in_longitude' => 'decimal:7',
        'check_out_latitude' => 'decimal:7',
        'check_out_longitude' => 'decimal:7'
    ];

    public function salesPerson()
    {
        return $this->belongsTo(SalesPerson::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('visit_date', now()->toDateString());
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('visit_date', [$startDate, $endDate]);
    }

    public function scopeBySalesPerson($query, $salesPersonId)
    {
        return $query->where('sales_person_id', $salesPersonId);
    }
}
