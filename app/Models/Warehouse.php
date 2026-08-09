<?php

namespace App\Models;

use App\Observers\WarehouseObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([WarehouseObserver::class])]
class Warehouse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'type', 'address', 'phone', 'pic', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('stock', 'min_stock');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function stockBatches()
    {
        return $this->hasMany(StockBatch::class);
    }

    public function stockOpnames()
    {
        return $this->hasMany(StockOpname::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->code)) {
                $latest = self::withTrashed()->orderBy('id', 'desc')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $model->code = 'GD-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
