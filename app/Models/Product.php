<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[ObservedBy([ProductObserver::class])]
class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'barcode', 'sku', 'name', 'slug', 'brand', 'category_id', 'supplier_id', 'unit', 'weight', 'content_per_unit', 'description', 'min_purchase', 'base_cost', 'sell_price', 'distributor_price', 'agent_price', 'store_price', 'min_stock', 'is_active'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'base_cost' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'distributor_price' => 'decimal:2',
        'agent_price' => 'decimal:2',
        'store_price' => 'decimal:2',
        'min_purchase' => 'integer',
        'min_stock' => 'integer',
        'content_per_unit' => 'integer',
        'is_active' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class)->withPivot('stock', 'min_stock');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function stockBatches()
    {
        return $this->hasMany(StockBatch::class);
    }

    public function promos()
    {
        return $this->belongsToMany(Promo::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeLowStock($query)
    {
        return $query->whereHas('warehouses', function($q) {
            $q->whereColumn('product_warehouse.stock', '<=', 'product_warehouse.min_stock');
        });
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('sku', 'like', "%{$term}%")
                ->orWhere('barcode', 'like', "%{$term}%");
        });
    }

    public function getFormattedSellPriceAttribute()
    {
        return 'Rp ' . number_format($this->sell_price, 0, ',', '.');
    }

    public function getTotalStockAttribute()
    {
        return $this->warehouses->sum('pivot.stock');
    }

    public function getStockStatusAttribute()
    {
        $stock = $this->total_stock;
        if ($stock <= 0) return 'Out of Stock';
        if ($stock <= $this->min_stock) return 'Low Stock';
        return 'In Stock';
    }

    /**
     * Resolusi harga jual untuk tipe customer & qty tertentu.
     * Prioritas: tier harga bertingkat (product_prices) yang aktif & qty masuk rentang,
     * lalu fallback ke kolom harga flat sesuai tipe customer.
     */
    public function priceFor(string $customerType = 'retail', int $qty = 1): float
    {
        $tier = $this->prices
            ->where('price_type', $customerType)
            ->where('is_active', true)
            ->filter(fn ($p) => $qty >= $p->min_qty && ($p->max_qty === null || $qty <= $p->max_qty))
            ->sortByDesc('min_qty')
            ->first();

        if ($tier) {
            return (float) $tier->price;
        }

        return match ($customerType) {
            'agen' => (float) ($this->agent_price ?: $this->sell_price),
            'distributor' => (float) ($this->distributor_price ?: $this->sell_price),
            default => (float) $this->sell_price,
        };
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
