<?php

namespace App\Livewire\Sales;

use App\Models\SalesOrder;
use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.sales', ['hideFab' => true])]
class OrderDetail extends Component
{
    use EnsuresSalesPerson;

    public SalesOrder $salesOrder;

    public function mount(SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_person_id !== $this->salesPerson->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $this->salesOrder = $salesOrder->load(['customer', 'items.product', 'warehouse']);
    }

    public function title(): string
    {
        return 'Order ' . $this->salesOrder->so_number;
    }

    /**
     * Generate nota lengkap dan dispatch copy event ke browser.
     */
    public function copyNota()
    {
        $order = $this->salesOrder;
        $customer = $order->customer;

        $lines = [];

        $lines[] = 'TOKO: ' . ($customer?->store_name ?? '-');
        $lines[] = 'ALAMAT: ' . ($customer?->address ?? '-') . ', ' . ($customer?->area ?? '-');
        $lines[] = 'HP: ' . ($customer?->phone ?? '-');
        $lines[] = '';
        $lines[] = '--- DAFTAR PRODUK ---';
        $no = 1;
        foreach ($order->items as $item) {
            $productName = $item->product?->name ?? 'Produk';
            $lines[] = $no . '. ' . $productName;
            $lines[] = '   ' . $item->qty . ' ' . ($item->unit ?? 'pcs') . ' x Rp ' . number_format($item->price, 0, ',', '.');
            $lines[] = '   = Rp ' . number_format($item->subtotal, 0, ',', '.');
            $no++;
        }

        $lines[] = '';
        $lines[] = '--------------------------------';
        $lines[] = 'TOTAL    : Rp ' . number_format($order->grand_total, 0, ',', '.');
        $lines[] = '--------------------------------';
        
        if ($order->notes) {
            $lines[] = 'Catatan: ' . $order->notes;
        }

        $notaText = implode("\n", $lines);

        $this->dispatch('copy-nota', text: $notaText);
    }

    /**
     * Generate nota ringkas (hanya info toko dan produk + qty).
     */
    public function copyNotaSimple()
    {
        $order = $this->salesOrder;
        $customer = $order->customer;

        $lines = [];

        $lines[] = 'TOKO: ' . ($customer?->store_name ?? '-');
        $lines[] = 'ALAMAT: ' . ($customer?->address ?? '-') . ', ' . ($customer?->area ?? '-');
        $lines[] = 'HP: ' . ($customer?->phone ?? '-');
        $lines[] = '';
        $lines[] = '--- DAFTAR PRODUK ---';
        
        $no = 1;
        foreach ($order->items as $item) {
            $productName = $item->product?->name ?? 'Produk';
            $lines[] = $no . '. ' . $productName . ',' . $item->qty . ' ' . ($item->unit ?? 'pcs');
            $no++;
        }

        $notaText = implode("\n", $lines);

        $this->dispatch('copy-nota', text: $notaText);
    }

    public function render()
    {
        return view('livewire.sales.order-detail');
    }
}