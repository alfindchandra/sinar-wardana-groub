<?php

namespace App\Livewire\Shop;

use App\Livewire\Shop\Concerns\HasCart;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.shop')]
#[Title('Semua Produk')]
class ProductCatalog extends Component
{
    use HasCart, WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(as: 'category', history: true)]
    public string $categoryFilter = '';

    #[Url(history: true)]
    public string $sort = 'terbaru';

    public int $perPage = 15;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSort(): void
    {
        $this->resetPage();
    }

    public function selectCategory(string $categoryId): void
    {
        $this->categoryFilter = $this->categoryFilter === $categoryId ? '' : $categoryId;
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query()
            ->active()
            ->with(['category:id,name', 'primaryImage', 'prices', 'warehouses']);

        if ($this->search !== '') {
            $query->search($this->search);
        }

        if ($this->categoryFilter !== '') {
            $query->byCategory($this->categoryFilter);
        }

        match ($this->sort) {
            'harga_rendah' => $query->orderBy('sell_price', 'asc'),
            'harga_tinggi' => $query->orderBy('sell_price', 'desc'),
            'nama' => $query->orderBy('name', 'asc'),
            default => $query->latest(),
        };

        $products = $query->paginate($this->perPage);

        $categories = Category::active()->ordered()
            ->withCount(['products' => fn ($q) => $q->active()])
            ->get();

        return view('livewire.shop.product-catalog', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
