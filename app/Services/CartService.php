<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

/**
 * Keranjang belanja berbasis session (bekerja untuk guest maupun customer login).
 * Struktur session: ['cart' => [product_id => qty, ...]]
 */
class CartService
{
    protected const SESSION_KEY = 'cart';

    public function add(int $productId, int $qty = 1): void
    {
        $cart = $this->raw();
        $cart[$productId] = ($cart[$productId] ?? 0) + $qty;
        $this->save($cart);
    }

    public function setQty(int $productId, int $qty): void
    {
        $cart = $this->raw();

        if ($qty <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $qty;
        }

        $this->save($cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->raw();
        unset($cart[$productId]);
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
     * Ambil item keranjang lengkap dengan data produk & harga terkini.
     * @return Collection<int, array{product: Product, qty: int, price: float, subtotal: float}>
     */
    public function items(string $customerType = 'retail'): Collection
    {
        $cart = $this->raw();

        if (empty($cart)) {
            return collect();
        }

        $products = Product::with(['primaryImage', 'prices', 'warehouses'])
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $hasMissingProducts = false;

        $items = collect($cart)
            ->map(function ($qty, $productId) use ($products, $customerType, &$cart, &$hasMissingProducts) {
                $product = $products->get($productId);

                if (! $product) {
                    unset($cart[$productId]);
                    $hasMissingProducts = true;
                    return null;
                }

                $price = $product->priceFor($customerType, $qty);

                return [
                    'product'  => $product,
                    'qty'      => $qty,
                    'price'    => $price,
                    'subtotal' => $price * $qty,
                ];
            })
            ->filter()
            ->values();

        // Clean up deleted products from session automatically
        if ($hasMissingProducts) {
            $this->save($cart);
        }

        return $items;
    }

    public function subtotal(string $customerType = 'retail'): float
    {
        return (float) $this->items($customerType)->sum('subtotal');
    }

    /**
     * Simpan array keranjang ke session.
     */
    protected function save(array $cart): void
    {
        Session::put(self::SESSION_KEY, $cart);
    }
}