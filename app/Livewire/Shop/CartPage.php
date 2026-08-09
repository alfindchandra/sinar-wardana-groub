<?php

namespace App\Livewire\Shop;

use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.shop')]
#[Title('Keranjang Belanja')]
class CartPage extends Component
{
    public function updateQty(int $productId, int $qty): void
    {
        app(CartService::class)->setQty($productId, max(0, $qty));
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $productId): void
    {
        app(CartService::class)->remove($productId);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', type: 'success', message: 'Item dihapus dari keranjang.');
    }

    protected function customerType(): string
    {
        $user = Auth::user();

        return $user?->customer?->customer_type ?? 'retail';
    }

    public function render()
    {
        $cart = app(CartService::class);
        $items = $cart->items($this->customerType());

        return view('livewire.shop.cart-page', [
            'items' => $items,
            'subtotal' => (float) $items->sum('subtotal'),
        ]);
    }
}
