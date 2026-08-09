<?php

namespace App\Livewire\Shop;

use App\Livewire\Shop\Concerns\HasCart;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.shop')]
class ProductDetail extends Component
{
    use HasCart;

    public Product $product;
    public int $qty = 1;
    public ?int $selectedVariantId = null;

    public function mount(Product $product): void
    {
        abort_unless($product->is_active, 404);

        $this->product = $product->load(['category', 'supplier:id,name', 'images', 'variants', 'warehouses']);
        $this->qty = max(1, $product->min_purchase);

        // Jika hanya ada 1 varian aktif, pilih otomatis untuk mempercepat alur beli.
        $activeVariants = $this->product->variants->where('is_active', true);
        if ($activeVariants->count() === 1) {
            $this->selectedVariantId = $activeVariants->first()->id;
        }
    }

    public function selectVariant(int $variantId): void
    {
        $this->selectedVariantId = $variantId;
    }

    public function increment(): void
    {
        $max = $this->currentStock();
        if ($this->qty < $max) {
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

    protected function currentStock(): int
    {
        if ($this->product->hasVariants()) {
            $variant = $this->product->variants->firstWhere('id', $this->selectedVariantId);

            return $variant?->stock ?? 0;
        }

        return $this->product->total_stock;
    }

    public function addCurrentToCart(): void
    {
        if ($this->product->hasVariants() && ! $this->selectedVariantId) {
            $this->dispatch('toast', type: 'error', message: 'Silakan pilih varian terlebih dahulu.');
            return;
        }

        $this->addToCart($this->product->id, $this->qty, $this->selectedVariantId ?? 0);
    }

    public function title(): string
    {
        return $this->product->name . ' - ' . config('app.name', 'Sinar Wardana');
    }

    public function getPriceProperty(): float
    {
        $variant = $this->product->variants->firstWhere('id', $this->selectedVariantId);

        return $this->product->checkoutPrice() + ($variant ? (float) $variant->extra_price : 0);
    }

    public function getCanAddToCartProperty(): bool
    {
        if ($this->product->hasVariants() && ! $this->selectedVariantId) {
            return false;
        }

        return $this->currentStock() > 0;
    }

    public function render()
    {
        $related = Product::active()
            ->with(['primaryImage', 'warehouses'])
            ->where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->take(5)
            ->get();

        return view('livewire.shop.product-detail', [
            'related' => $related,
        ]);
    }
}
