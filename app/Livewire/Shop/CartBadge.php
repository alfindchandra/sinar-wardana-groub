<?php

namespace App\Livewire\Shop;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartBadge extends Component
{
    public int $count = 0;

    public function mount(CartService $cart): void
    {
        $this->count = $cart->count();
    }

    #[On('cart-updated')]
    public function refreshCount(CartService $cart): void
    {
        $this->count = $cart->count();
    }

    public function render()
    {
        return view('livewire.shop.cart-badge');
    }
}
