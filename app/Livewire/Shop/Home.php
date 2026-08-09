<?php

namespace App\Livewire\Shop;

use App\Livewire\Shop\Concerns\HasCart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promo;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.shop')]
#[Title('Belanja Sembako & Grosir Online')]
class Home extends Component
{
    use HasCart;

    public function render()
    {
        $categories = Category::active()->ordered()
            ->withCount(['products' => fn ($q) => $q->active()])
            ->take(8)
            ->get();

        $promos = Promo::active()->ongoing()->latest('start_date')->take(3)->get();

        $newestProducts = Product::active()
            ->with(['category:id,name', 'primaryImage', 'variants', 'warehouses'])
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.shop.home', [
            'categories' => $categories,
            'promos' => $promos,
            'newestProducts' => $newestProducts,
        ]);
    }
}
