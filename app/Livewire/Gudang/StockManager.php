<?php

namespace App\Livewire\Gudang;

use App\Models\Product;
use App\Models\Warehouse;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class StockManager extends Component
{
    use WithPagination;

    public $search = '';
    public $warehouseFilter = '';
    public $stockFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingWarehouseFilter()
    {
        $this->resetPage();
    }

    public function updatingStockFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::with(['category', 'warehouses'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%')
                      ->orWhere('barcode', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->warehouseFilter, function ($query) {
                $query->whereHas('warehouses', function ($q) {
                    $q->where('warehouse_id', $this->warehouseFilter);
                });
            })
            ->when($this->stockFilter === 'low', function ($query) {
                $query->lowStock();
            })
            ->when($this->stockFilter === 'out', function ($query) {
                // As per instruction, filter where total stock = 0
                // We'll rely on a scope or a condition, but we can do a query on the pivot if needed
                // Assuming we use a has() or a check on the model's computed stock, but since we need a query builder:
                // We will use where(function) to handle it or if Product has a scope, but let's do:
                $query->whereDoesntHave('warehouses', function ($q) {
                    $q->where('stock', '>', 0);
                });
            });

        $products = $query->paginate(15);
        $warehouses = Warehouse::where('is_active', true)->get();

        return view('livewire.gudang.stock-manager', [
            'products' => $products,
            'warehouses' => $warehouses
        ])->layout('layouts.app');
    }
}
