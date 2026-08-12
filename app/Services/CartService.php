<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

/**
 * Keranjang belanja berbasis session (bekerja untuk guest maupun customer login).
 * Struktur session: ['cart' => ["{product_id}-{variant_id}" => qty, ...]]
 * variant_id = 0 artinya produk tanpa varian.
 */
class CartService
{
    protected const SESSION_KEY = 'cart';

    /**
     * Simpan data keranjang ke session.
     */
    protected function save(array $cart): void
    {
        Session::put(self::SESSION_KEY, $cart);
    }

    protected function key(int $productId, int $variantId = 0): string
    {
        return "{$productId}-{$variantId}";
    }

    public function add(int $productId, int $qty = 1, int $variantId = 0): void
    {
        $cart = $this->raw();
        $key = $this->key($productId, $variantId);
        $cart[$key] = ($cart[$key] ?? 0) + $qty;
        $this->save($cart);
    }

    public function setQty(int $productId, int $variantId, int $qty): void
    {
        $cart = $this->raw();
        $key = $this->key($productId, $variantId);

        if ($qty <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key] = $qty;
        }

        $this->save($cart);
    }

    public function remove(int $productId, int $variantId = 0): void
    {
        $cart = $this->raw();
        unset($cart[$this->key($productId, $variantId)]);
        $this->save($cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function raw(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return array_sum($this->raw());
    }

    /**
     * Ambil item keranjang lengkap dengan data produk, varian (jika ada), & harga terkini.
     * Harga checkout SELALU `sell_price` (Harga Jual Umum) — tidak ada tipe harga lain.
     *
     * @return Collection<int, array{product: Product, variant: ?ProductVariant, qty: int, price: float, subtotal: float}>
     */
    public function items(): Collection
    {
        $cart = $this->raw();

        if (empty($cart)) {
            return collect();
        }

        $parsed = collect($cart)->map(function ($qty, $key) {
            [$productId, $variantId] = array_pad(explode('-', $key), 2, 0);

            return [
                'product_id' => (int) $productId,
                'variant_id' => (int) $variantId,
                'qty' => (int) $qty,
            ];
        })->values();

        $productIds = $parsed->pluck('product_id')->unique();
        $variantIds = $parsed->pluck('variant_id')->filter()->unique();

        $products = Product::with(['primaryImage', 'warehouses'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $variants = ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id');

        return $parsed
            ->map(function ($row) use ($products, $variants) {
                $product = $products->get($row['product_id']);

                if (! $product) {
                    return null;
                }

                $variant = $row['variant_id'] ? $variants->get($row['variant_id']) : null;
                $price = $product->checkoutPrice() + ($variant ? (float) $variant->extra_price : 0);

                return [
                    'product' => $product,
                    'variant' => $variant,
                    'qty' => $row['qty'],
                    'price' => $price,
                    'subtotal' => $price * $row['qty'],
                ];
            })
            ->filter()
            ->values();
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('subtotal');
    }
}