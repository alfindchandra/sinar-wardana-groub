<?php

namespace App\Livewire\Shop;

use App\Services\CartService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.shop')]
#[Title('Keranjang Belanja')]
class CartPage extends Component
{
    public function updateQty(int $productId, int $variantId, int $qty): void
    {
        app(CartService::class)->setQty($productId, $variantId, max(0, $qty));
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $productId, int $variantId): void
    {
        app(CartService::class)->remove($productId, $variantId);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', type: 'success', message: 'Item dihapus dari keranjang.');
    }

    public function render()
    {
        $cart = app(CartService::class);
        $items = $cart->items();

        return view('livewire.shop.cart-page', [
            'items' => $items,
            'subtotal' => (float) $items->sum('subtotal'),
        ]);
    }
}
