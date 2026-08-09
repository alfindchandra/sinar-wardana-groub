<?php

namespace App\Services;

use App\Enums\OnlineOrderStatus;
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
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang belanja masih kosong.',
            ]);
        }

        // Validasi stok tersedia sebelum order dibuat (per varian jika ada, atau produk secara umum)
        foreach ($items as $item) {
            $available = $item['variant'] ? $item['variant']->stock : $item['product']->total_stock;
            $label = $item['variant'] ? "{$item['product']->name} ({$item['variant']->name})" : $item['product']->name;

            if ($item['qty'] > $available) {
                throw ValidationException::withMessages([
                    'cart' => "Stok \"{$label}\" tidak mencukupi. Tersisa {$available}, diminta {$item['qty']}.",
                ]);
            }

            if ($item['qty'] < $item['product']->min_purchase) {
                throw ValidationException::withMessages([
                    'cart' => "Minimal pembelian \"{$label}\" adalah {$item['product']->min_purchase}.",
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
                    'product_variant_id' => $item['variant']?->id,
                    'product_variant_name' => $item['variant']?->name,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $this->cart->clear();

            return $order->fresh(['items.product.primaryImage', 'customer']);
        });
    }

    /**
     * Majukan status pesanan ke tahap berikutnya sesuai alur normal
     * (pending → confirmed → processing → shipped → completed).
     */
    public function advanceStatus(OnlineOrder $order): OnlineOrder
    {
        $current = OnlineOrderStatus::from($order->status);
        $next = $current->next();

        if (! $next) {
            throw ValidationException::withMessages([
                'status' => 'Pesanan sudah berada di status akhir dan tidak bisa dimajukan lagi.',
            ]);
        }

        $order->update(['status' => $next->value]);

        return $order->fresh();
    }

    public function cancel(OnlineOrder $order): OnlineOrder
    {
        $current = OnlineOrderStatus::from($order->status);

        if (! $current->canBeCancelled()) {
            throw ValidationException::withMessages([
                'status' => 'Pesanan dengan status "' . $current->label() . '" tidak bisa dibatalkan.',
            ]);
        }

        $order->update(['status' => OnlineOrderStatus::CANCELLED->value]);

        return $order->fresh();
    }
}
