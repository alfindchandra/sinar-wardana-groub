<?php

namespace App\Livewire\Shop\Concerns;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;

trait HasCart
{
    /**
     * Tambah produk ke keranjang. Jika produk punya varian, $variantId WAJIB diisi.
     */
    public function addToCart(int $productId, int $qty = 1, int $variantId = 0): void
    {
        $product = Product::active()->with('variants')->find($productId);

        if (! $product) {
            $this->dispatch('toast', type: 'error', message: 'Produk tidak ditemukan.');
            return;
        }

        if ($product->hasVariants() && $variantId === 0) {
            $this->dispatch('toast', type: 'error', message: 'Silakan pilih varian terlebih dahulu.');
            return;
        }

        $variant = null;
        if ($variantId > 0) {
            $variant = ProductVariant::where('product_id', $productId)->find($variantId);

            if (! $variant || ! $variant->is_active) {
                $this->dispatch('toast', type: 'error', message: 'Varian tidak tersedia.');
                return;
            }

            if ($variant->stock <= 0) {
                $this->dispatch('toast', type: 'error', message: "Stok varian \"{$variant->name}\" sedang habis.");
                return;
            }
        } elseif ($product->total_stock <= 0) {
            $this->dispatch('toast', type: 'error', message: 'Stok produk sedang habis.');
            return;
        }

        $qty = max($qty, $product->min_purchase);

        app(CartService::class)->add($productId, $qty, $variantId);

        $this->dispatch('cart-updated');

        $label = $variant ? "{$product->name} ({$variant->name})" : $product->name;
        $this->dispatch('toast', type: 'success', message: "\"{$label}\" ditambahkan ke keranjang.", title: 'Berhasil');
    }
}
