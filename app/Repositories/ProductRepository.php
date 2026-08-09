<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['category:id,name', 'supplier:id,name', 'primaryImage', 'warehouses']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        if (! empty($filters['stock_status'])) {
            match ($filters['stock_status']) {
                'low' => $query->whereHas('warehouses', function ($q) {
                    $q->whereColumn('product_warehouse.stock', '<=', 'product_warehouse.min_stock')
                        ->where('product_warehouse.stock', '>', 0);
                }),
                'out' => $query->whereDoesntHave('warehouses', function ($q) {
                    $q->where('product_warehouse.stock', '>', 0);
                }),
                default => null,
            };
        }

        $sortBy = in_array($filters['sort_by'] ?? '', ['name', 'sku', 'sell_price', 'created_at'])
            ? $filters['sort_by']
            : 'created_at';
        $sortDir = ($filters['sort_dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }

    public function find(int $id): ?Product
    {
        return Product::with(['category', 'supplier', 'images', 'variants', 'warehouses'])->find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product;
    }

    public function delete(Product $product): bool
    {
        return (bool) $product->delete();
    }
}