<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Warehouse;
use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.sales', ['hideFab' => true])]
class CreateOrder extends Component
{
    use EnsuresSalesPerson;

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

    public function mount($customer = null)
    {
        if ($customer) {
            $this->selectCustomer($customer);
        }
        
        $defaultWarehouse = Warehouse::where('is_active', true)->first();
        if ($defaultWarehouse) {
            $this->warehouse_id = $defaultWarehouse->id;
        }
    }

    public function title(): string
    {
        return 'Buat Order Baru';
    }

    public function selectCustomer($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $this->customer_id = $customer->id;
            $this->customer_name = $customer->store_name;
            $this->customer_photo = $customer->store_photo;
            $this->customer_lat = $customer->latitude;
            $this->customer_lng = $customer->longitude;
            $this->search = '';
        }
    }

    public function clearCustomer()
    {
        $this->customer_id = null;
        $this->customer_name = null;
        $this->customer_photo = null;
        $this->customer_lat = null;
        $this->customer_lng = null;
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
                    'qty' => 1,
                    'price' => $product->sell_price,
                    'subtotal' => $product->sell_price
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

    public function getCustomersProperty()
    {
        if (strlen($this->search) < 2) return [];
        return Customer::where('sales_person_id', $this->salesPerson->id)
            ->where(function($q) {
                $q->where('store_name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->where('is_active', true)
            ->take(5)
            ->get();
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
            'customer_id' => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'payment_type' => 'required|in:cash,tempo',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:0.1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $subtotal = $this->subtotal;
        $so_number = 'SO-' . date('Ymd') . '-' . strtoupper(uniqid());
        $customer = Customer::find($this->customer_id);

        $order = SalesOrder::create([
            'so_number' => $so_number,
            'customer_id' => $this->customer_id,
            'sales_person_id' => $this->salesPerson->id,
            'warehouse_id' => $this->warehouse_id,
            'order_date' => now(),
            'payment_type' => $this->payment_type,
            'due_date' => $this->payment_type === 'tempo' ? now()->addDays($customer->payment_term_days ?? 30) : now(),
            'status' => 'confirmed',
            'subtotal' => $subtotal,
            'discount' => 0,
            'tax' => 0,
            'shipping_cost' => 0,
            'grand_total' => $subtotal,
            'notes' => $this->notes,
            'source' => 'sales',
            'created_by' => auth()->id(),
        ]);

        foreach ($this->items as $item) {
            SalesOrderItem::create([
                'sales_order_id' => $order->id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'unit' => Product::find($item['product_id'])->unit ?? 'pcs',
                'price' => $item['price'],
                'discount' => 0,
                'subtotal' => $item['subtotal'],
            ]);
        }

        session()->flash('toast', ['type' => 'success', 'message' => 'Order berhasil dibuat!', 'title' => 'Sukses']);
        
        return $this->redirect(route('sales.orders.show', $order->id), navigate: true);
    }

    public function render()
    {
        return view('livewire.sales.create-order');
    }
}
