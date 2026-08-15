<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Warehouse;
use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.sales', ['hideFab' => true])]
class EditOrder extends Component
{
    use EnsuresSalesPerson;

    public SalesOrder $salesOrder;

    public $customer_id;
    public $customer_name;
    public $customer_photo;
    public $customer_lat;
    public $customer_lng;
    
    public $search = '';
    public $warehouse_id;
    public $payment_type = 'cash';
    public $items = [];
    public $productSearch = '';
    public $notes = '';

    public function mount(SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_person_id !== $this->salesPerson->id) {
            abort(403);
        }
        if ($salesOrder->status !== 'draft') {
            session()->flash('toast', ['type' => 'error', 'message' => 'Hanya order draft yang bisa diedit', 'title' => 'Error']);
            return $this->redirect(route('sales.orders.show', $salesOrder), navigate: true);
        }
        
        $this->salesOrder = $salesOrder;
        $customer = $salesOrder->customer;
        $this->customer_id = $customer->id;
        $this->customer_name = $customer->store_name;
        $this->customer_photo = $customer->store_photo;
        $this->customer_lat = $customer->latitude;
        $this->customer_lng = $customer->longitude;
        $this->warehouse_id = $salesOrder->warehouse_id;
        $this->payment_type = $salesOrder->payment_type;
        $this->notes = $salesOrder->notes;
        
        // Load existing items
        foreach ($salesOrder->items as $item) {
            $this->items[] = [
                'product_id' => $item->product_id,
                'product_name' => $item->product?->name ?? 'Unknown',
                'unit' => $item->unit ?? 'pcs',
                'qty' => (float)$item->qty,
                'price' => (float)$item->price,
                'subtotal' => (float)$item->subtotal,
            ];
        }
    }

    #[Title('Edit Order')]
    public function title()
    {
        return 'Edit Order ' . $this->salesOrder->so_number;
    }

    public function addProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $existingIndex = collect($this->items)->search(fn($item) => $item['product_id'] == $product->id);
            
            if ($existingIndex !== false) {
                $this->items[$existingIndex]['qty']++;
                $this->updateQty($existingIndex, $this->items[$existingIndex]['qty']);
            } else {
                $this->items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit' => $product->unit ?? 'pcs',
                    'qty' => 1,
                    'price' => (float) $product->sell_price,
                    'subtotal' => (float) $product->sell_price
                ];
            }
            $this->productSearch = '';
        }
    }

    public function removeProduct($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updateQty($index, $qty)
    {
        $qty = (float) $qty;
        if ($qty > 0) {
            $this->items[$index]['qty'] = $qty;
            $this->items[$index]['subtotal'] = $qty * $this->items[$index]['price'];
        } else {
            $this->removeProduct($index);
        }
    }

    public function getSubtotalProperty()
    {
        return collect($this->items)->sum('subtotal');
    }

    public function getProductsProperty()
    {
        if (strlen($this->productSearch) < 2) return [];
        
        return Product::where('is_active', true)
            ->where('name', 'like', '%' . $this->productSearch . '%')
            ->take(5)
            ->get();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::where('is_active', true)->get();
    }

    public function submit()
    {
        $this->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'payment_type' => 'required|in:cash,tempo',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:0.1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $subtotal = $this->subtotal;
        $customer = Customer::find($this->customer_id);

        $this->salesOrder->update([
            'warehouse_id' => $this->warehouse_id,
            'payment_type' => $this->payment_type,
            'due_date' => $this->payment_type === 'tempo' ? $this->salesOrder->order_date->addDays($customer->payment_term_days ?? 30) : $this->salesOrder->order_date,
            'subtotal' => $subtotal,
            'grand_total' => $subtotal,
            'notes' => $this->notes,
        ]);

        $this->salesOrder->items()->delete();
        
        foreach ($this->items as $item) {
            SalesOrderItem::create([
                'sales_order_id' => $this->salesOrder->id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'unit' => $item['unit'] ?? 'pcs',
                'price' => $item['price'],
                'discount' => 0,
                'subtotal' => $item['subtotal'],
            ]);
        }

        session()->flash('toast', ['type' => 'success', 'message' => 'Order berhasil diperbarui!', 'title' => 'Sukses']);
        
        return $this->redirect(route('sales.orders.show', $this->salesOrder->id), navigate: true);
    }

    public function render()
    {
        return view('livewire.sales.edit-order');
    }
}
