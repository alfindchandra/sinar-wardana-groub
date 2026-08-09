<?php

namespace App\Livewire\Shop;

use App\Livewire\Shop\Concerns\HasCart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.shop')]
class ProductDetail extends Component
{
    use HasCart;

    public Product $product;
    public int $qty = 1;
    public int $activeImageIndex = 0;

    public function mount(Product $product): void
    {
        abort_unless($product->is_active, 404);

        $this->product = $product->load(['category', 'supplier:id,name', 'images', 'prices', 'warehouses']);
        $this->qty = max(1, $product->min_purchase);
    }

    public function increment(): void
    {
        if ($this->qty < $this->product->total_stock) {
            $this->qty++;
        }
    }

    public function decrement(): void
    {
        $min = max(1, $this->product->min_purchase);
        if ($this->qty > $min) {
            $this->qty--;
        }
    }

    public function addCurrentToCart(): void
    {
        $this->addToCart($this->product->id, $this->qty);
    }

    public function title(): string
    {
        return $this->product->name . ' - ' . config('app.name', 'Sinar Wardana');
    }

    protected function customerType(): string
    {
        $user = Auth::user();

        return $user?->customer?->customer_type ?? 'retail';
    }

    public function getPriceProperty(): float
    {
        return $this->product->priceFor($this->customerType(), $this->qty);
    }

    public function getTierListProperty()
    {
        return $this->product->prices
            ->where('price_type', $this->customerType())
            ->where('is_active', true)
            ->sortBy('min_qty');
    }

    public function render()
    {
        $related = Product::active()
            ->with(['primaryImage', 'prices', 'warehouses'])
            ->where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->take(5)
            ->get();

        return view('livewire.shop.product-detail', [
            'related' => $related,
        ]);
    }
}
