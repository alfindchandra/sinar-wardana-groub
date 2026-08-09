<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return Product::with(['category', 'supplier', 'primaryImage'])->get();
    }

    public function find($id)
    {
        return Product::with(['category', 'supplier', 'images', 'warehouses'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }

    public function getActive()
    {
        return Product::active()->with(['category', 'supplier', 'primaryImage'])->get();
    }

    public function getByCategory($categoryId)
    {
        return Product::byCategory($categoryId)->with(['category', 'supplier', 'primaryImage'])->get();
    }

    public function search($term)
    {
        return Product::search($term)->with(['category', 'supplier', 'primaryImage'])->get();
    }

    public function getLowStock()
    {
        return Product::lowStock()->with(['category', 'supplier', 'primaryImage'])->get();
    }
}
