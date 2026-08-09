<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\OnlineOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OnlineOrderService
{
    public function __construct(
        protected CartService $cart
    ) {
    }

    /**
     * Buat OnlineOrder dari isi keranjang session milik customer yang login.
     */
    public function checkout(Customer $customer, array $data = []): OnlineOrder
    {
        $items = $this->cart->items($customer->customer_type);

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang belanja masih kosong.',
            ]);
        }

        // Validasi stok tersedia sebelum order dibuat
        foreach ($items as $item) {
            $available = $item['product']->total_stock;

            if ($item['qty'] > $available) {
                throw ValidationException::withMessages([
                    'cart' => "Stok \"{$item['product']->name}\" tidak mencukupi. Tersisa {$available}, diminta {$item['qty']}.",
                ]);
            }

            if ($item['qty'] < $item['product']->min_purchase) {
                throw ValidationException::withMessages([
                    'cart' => "Minimal pembelian \"{$item['product']->name}\" adalah {$item['product']->min_purchase}.",
                ]);
            }
        }

        $subtotal = (float) $items->sum('subtotal');
        $discount = (float) ($data['discount'] ?? 0);

        return DB::transaction(function () use ($customer, $items, $subtotal, $discount, $data) {
            $order = OnlineOrder::create([
                'customer_id' => $customer->id,
                'order_date' => now()->toDateString(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'grand_total' => $subtotal - $discount,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $this->cart->clear();

            return $order->fresh(['items.product.primaryImage', 'customer']);
        });
    }
}
