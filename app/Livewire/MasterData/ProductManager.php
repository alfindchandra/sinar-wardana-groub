<?php

namespace App\Livewire\MasterData;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    
    protected $listeners = ['deleteConfirm' => 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function triggerDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Apakah Anda yakin?',
            'text' => 'Produk ini akan dihapus.',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Produk berhasil dihapus.']);
    }

    public function render()
    {
        $query = Product::with(['category', 'supplier', 'primaryImage']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter);
        }

        $products = $query->latest()->paginate(12);
        $categories = Category::active()->get();

        return view('livewire.master-data.product-manager', [
            'products' => $products,
            'categories' => $categories
        ])->layout('layouts.app');
    }
}
