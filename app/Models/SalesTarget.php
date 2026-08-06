<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTarget extends Model
{
    protected $fillable = [
        'sales_person_id', 'month', 'year', 'target_amount', 'achieved_amount'
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'target_amount' => 'decimal:2',
        'achieved_amount' => 'decimal:2'
    ];

    public function salesPerson()
    {
        return $this->belongsTo(SalesPerson::class);
    }

    public function getAchievementPercentageAttribute()
    {
        if ($this->target_amount <= 0) return 100;
        return round(($this->achieved_amount / $this->target_amount) * 100, 2);
    }

    public function getPeriodLabelAttribute()
    {
        return date('F', mktime(0, 0, 0, $this->month, 10)) . ' ' . $this->year;
    }
}
