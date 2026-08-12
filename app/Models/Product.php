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
        'barcode', 'sku', 'name', 'slug', 'brand', 'category_id', 'supplier_id', 'unit',
        'weight', 'price_breakdowns', 'description', 'min_purchase',
        'base_cost', 'sell_price', 'min_stock', 'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'base_cost' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'min_purchase' => 'integer',
        'min_stock' => 'integer',
        'price_breakdowns' => 'array',
        'is_active' => 'boolean',
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

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
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
        return $query->whereHas('warehouses', function ($q) {
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

    public function hasVariants(): bool
    {
        return $this->variants->isNotEmpty();
    }

    /**
     * Satu-satunya harga yang bisa masuk keranjang/checkout: Harga Jual Umum
     * (per satuan utama — Sak/Dus/dll, sesuai kolom `unit`).
     */
    public function checkoutPrice(): float
    {
        return (float) $this->sell_price;
    }

    /**
     * Hitung breakdown harga bertingkat (jumlah level bebas, satuan bebas — mis. Dus -> Bal -> Pcs).
     * Hanya untuk tampilan/info — TIDAK bisa dibeli terpisah per level.
     *
     * Mengembalikan array berisi, untuk tiap level:
     * - unit / unit_label : satuan level ini (mis. "bal" / "Bal")
     * - qty                : isi level ini di dalam 1 level sebelumnya (mis. 8)
     * - cumulative_qty     : total isi level ini di dalam 1 satuan utama produk (mis. 8, lalu 160)
     * - price              : estimasi harga per level ini (sell_price dibagi cumulative_qty)
     */
    public function breakdownSteps(): array
    {
        $steps = $this->price_breakdowns ?? [];

        if (empty($steps) || (float) $this->sell_price <= 0) {
            return [];
        }

        $result = [];
        $cumulativeQty = 1;

        foreach ($steps as $step) {
            $qty = (int) ($step['qty'] ?? 0);
            $unit = $step['unit'] ?? null;

            if ($qty <= 0 || ! $unit) {
                continue;
            }

            $cumulativeQty *= $qty;

            $result[] = [
                'unit' => $unit,
                'unit_label' => \App\Enums\BreakdownUnit::tryFrom($unit)?->label() ?? ucfirst($unit),
                'qty' => $qty,
                'cumulative_qty' => $cumulativeQty,
                'price' => (float) $this->sell_price / $cumulativeQty,
            ];
        }

        return $result;
    }

    /**
     * Apakah produk ini punya data breakdown untuk ditampilkan.
     */
    public function hasBreakdown(): bool
    {
        return count($this->breakdownSteps()) > 0;
    }

    /**
     * Teks ringkas breakdown harga untuk ditampilkan di deskripsi produk, mis:
     * "Isi 1 Dus = 8 Bal (Rp 39.313/bal) | 1 Bal = 20 Pcs, Total 160 Pcs (Rp 1.966/pcs)"
     */
    public function breakdownDescription(): ?string
    {
        $steps = $this->breakdownSteps();

        if (empty($steps)) {
            return null;
        }

        $unitLabel = \App\Enums\ProductUnit::from($this->unit)->label();
        $parts = [];
        $previousLabel = $unitLabel;

        foreach ($steps as $step) {
            $parts[] = sprintf(
                'Isi 1 %s = %d %s (Rp %s/%s)',
                $previousLabel,
                $step['qty'],
                $step['unit_label'],
                number_format($step['price'], 0, ',', '.'),
                strtolower($step['unit_label'])
            );

            $previousLabel = $step['unit_label'];
        }

        return implode(' | ', $parts);
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
