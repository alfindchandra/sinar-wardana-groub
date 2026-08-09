<?php

namespace App\Livewire\Shop\Concerns;

use App\Models\Product;
use App\Services\CartService;

trait HasCart
{
    public function addToCart(int $productId, int $qty = 1): void
    {
        $product = Product::active()->find($productId);

        if (! $product) {
            $this->dispatch('toast', type: 'error', message: 'Produk tidak ditemukan.');
            return;
        }

        if ($product->total_stock <= 0) {
            $this->dispatch('toast', type: 'error', message: 'Stok produk sedang habis.');
            return;
        }

        $qty = max($qty, $product->min_purchase);

        app(CartService::class)->add($productId, $qty);

        $this->dispatch('cart-updated');
        $this->dispatch('toast', type: 'success', message: "\"{$product->name}\" ditambahkan ke keranjang.", title: 'Berhasil');
    }
}
