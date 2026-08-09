<?php

namespace App\Livewire\Shop;

use App\Models\OnlineOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.shop')]
#[Title('Pesanan Berhasil Dibuat')]
class OrderSuccess extends Component
{
    public OnlineOrder $order;

    public function mount(OnlineOrder $order): void
    {
        $user = Auth::user();
        $isOwner = $user->customer && $user->customer->id === $order->customer_id;

        abort_unless($isOwner || $user->can('view_products'), 403);

        $this->order = $order->load(['items.product.primaryImage', 'customer']);
    }

    public function render()
    {
        return view('livewire.shop.order-success');
    }
}
