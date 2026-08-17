<?php

namespace App\Livewire\Gudang;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Livewire\Component;
use Livewire\WithPagination;

class StockCardManager extends Component
{
    use WithPagination;

    public $search = '';
    public $warehouseFilter = '';
    public $productFilter = '';
    public $typeFilter = '';
    public $dateFrom = '';
    public $dateTo = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingWarehouseFilter()
    {
        $this->resetPage();
    }

    public function updatingProductFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = StockMovement::with(['product', 'warehouse', 'creator'])
            ->when($this->warehouseFilter, function ($q) {
                $q->where('warehouse_id', $this->warehouseFilter);
            })
            ->when($this->productFilter, function ($q) {
                $q->where('product_id', $this->productFilter);
            })
            ->when($this->typeFilter, function ($q) {
                $q->where('type', $this->typeFilter);
            })
            ->when($this->dateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->dateTo);
            })
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->whereHas('product', function ($p) {
                        $p->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('sku', 'like', '%' . $this->search . '%');
                    })->orWhere('notes', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc');

        $movements = $query->paginate(20);
        $warehouses = Warehouse::active()->get();
        $products = Product::active()->orderBy('name')->get();

        return view('livewire.gudang.stock-card-manager', [
            'movements' => $movements,
            'warehouses' => $warehouses,
            'products' => $products,
        ])->layout('layouts.app');
    }
}
