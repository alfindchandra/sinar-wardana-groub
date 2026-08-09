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
        'weight', 'content_per_bal', 'pcs_per_bal', 'description', 'min_purchase',
        'base_cost', 'sell_price', 'min_stock', 'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'base_cost' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'min_purchase' => 'integer',
        'min_stock' => 'integer',
        'content_per_bal' => 'integer',
        'pcs_per_bal' => 'integer',
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
     * Estimasi harga per Bal, dihitung otomatis dari harga Sak/Dus dibagi isi Bal.
     * Hanya untuk tampilan/deskripsi — TIDAK bisa dibeli terpisah.
     */
    public function pricePerBal(): ?float
    {
        if (! $this->content_per_bal || $this->content_per_bal <= 0) {
            return null;
        }

        return (float) $this->sell_price / $this->content_per_bal;
    }

    /**
     * Estimasi harga per Pcs, dihitung otomatis dari harga per Bal dibagi isi Pcs per Bal.
     * Hanya untuk tampilan/deskripsi — TIDAK bisa dibeli terpisah.
     */
    public function pricePerPcs(): ?float
    {
        $balPrice = $this->pricePerBal();

        if ($balPrice === null || ! $this->pcs_per_bal || $this->pcs_per_bal <= 0) {
            return null;
        }

        return $balPrice / $this->pcs_per_bal;
    }

    /**
     * Total Pcs dalam 1 Sak/Dus (isi Bal x isi Pcs per Bal).
     */
    public function totalPcs(): ?int
    {
        if (! $this->content_per_bal || ! $this->pcs_per_bal) {
            return null;
        }

        return $this->content_per_bal * $this->pcs_per_bal;
    }

    /**
     * Apakah produk ini punya data breakdown Bal/Pcs untuk ditampilkan.
     */
    public function hasBreakdown(): bool
    {
        return $this->pricePerBal() !== null;
    }

    /**
     * Teks ringkas breakdown harga untuk ditampilkan di deskripsi produk, mis:
     * "Isi 1 Sak = 8 Bal (Rp 39.313/bal) | Total 160 Pcs (Rp 1.966/pcs)"
     */
    public function breakdownDescription(): ?string
    {
        if (! $this->hasBreakdown()) {
            return null;
        }

        $unitLabel = \App\Enums\ProductUnit::from($this->unit)->label();
        $balPrice = $this->pricePerBal();
        $pcsPrice = $this->pricePerPcs();
        $totalPcs = $this->totalPcs();

        $text = sprintf(
            'Isi 1 %s = %d Bal (Rp %s/bal)',
            $unitLabel,
            $this->content_per_bal,
            number_format($balPrice, 0, ',', '.')
        );

        if ($pcsPrice !== null && $totalPcs !== null) {
            $text .= sprintf(
                ' | Total %d Pcs (Rp %s/pcs)',
                $totalPcs,
                number_format($pcsPrice, 0, ',', '.')
            );
        }

        return $text;
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
