<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOpname extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'opname_number', 'warehouse_id', 'opname_date', 'status', 'notes', 'created_by', 'approved_by', 'approved_at'
    ];

    protected $casts = [
        'opname_date' => 'date',
        'approved_at' => 'datetime'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items()
    {
        return $this->hasMany(StockOpnameItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->opname_number)) {
                $latest = self::orderBy('id', 'desc')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $date = date('Ymd');
                $model->opname_number = 'SO-' . $date . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
