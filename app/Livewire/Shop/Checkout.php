<?php

namespace App\Livewire\Shop;

use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesPerson;
use App\Models\Warehouse;
use App\Services\CartService;
use App\Services\OnlineOrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.shop')]
#[Title('Checkout')]
class Checkout extends Component
{
    // Diisi hanya jika profil toko customer belum lengkap
    public string $store_name = '';
    public string $owner_name = '';
    public string $phone = '';
    public string $address = '';
    public string $city = '';

    public string $notes = '';

    // ==== Khusus alur Sales: sales mencari & memilih toko binaannya ====
    public ?SalesPerson $salesPerson = null;
    public ?int $selectedCustomerId = null;
    public string $customerSearch = '';
    public $warehouse_id = null;
    public string $payment_type = 'cash';

    public function mount(): void
    {
        $user = Auth::user();

        $this->salesPerson = $user->salesPerson()->where('is_active', true)->first();

        if ($this->isSales()) {
            // Sales tidak mengisi profil toko sendiri, cukup pilih toko binaan.
            $defaultWarehouse = Warehouse::where('is_active', true)->first();
            if ($defaultWarehouse) {
                $this->warehouse_id = $defaultWarehouse->id;
            }

            // Dukungan datang dari halaman "Daftarkan Toko Baru" (redirect=checkout&customer=ID)
            $customerId = request()->query('customer');
            if ($customerId) {
                $this->selectCustomer((int) $customerId);
            }

            return;
        }

        if (! $user->customer) {
            $this->owner_name = $user->name;
        }
    }

    public function isSales(): bool
    {
        return $this->salesPerson !== null;
    }

    public function completeProfile(): void
    {
        $data = $this->validate([
            'store_name' => ['required', 'string', 'max:200'],
            'owner_name' => ['required', 'string', 'max:200'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:2000'],
            'city' => ['required', 'string', 'max:100'],
        ]);

        $user = Auth::user();

        $customer = Customer::create([
            'user_id' => $user->id,
            'store_name' => $data['store_name'],
            'owner_name' => $data['owner_name'],
            'phone' => $data['phone'],
            'email' => $user->email,
            'address' => $data['address'],
            'city' => $data['city'],
            'customer_type' => 'retail',
            'is_active' => true,
        ]);

        if (! $user->hasRole('pelanggan')) {
            $user->assignRole('pelanggan');
        }

        $user->refresh();
        $this->dispatch('toast', type: 'success', message: 'Profil toko berhasil disimpan.');
    }

    /**
     * Pilih toko binaan (khusus sales). Toko harus milik sales yang sedang login.
     */
    public function selectCustomer(int $id): void
    {
        if (! $this->isSales()) {
            return;
        }

        $customer = Customer::where('id', $id)
            ->where('sales_person_id', $this->salesPerson->id)
            ->where('is_active', true)
            ->first();

        if ($customer) {
            $this->selectedCustomerId = $customer->id;
            $this->customerSearch = '';
        }
    }

    public function clearCustomer(): void
    {
        $this->selectedCustomerId = null;
    }

    public function getCustomersProperty()
    {
        if (! $this->isSales() || strlen($this->customerSearch) < 2) {
            return collect();
        }

        return Customer::where('sales_person_id', $this->salesPerson->id)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('store_name', 'like', '%' . $this->customerSearch . '%')
                    ->orWhere('code', 'like', '%' . $this->customerSearch . '%');
            })
            ->take(5)
            ->get();
    }

    public function getSelectedCustomerProperty(): ?Customer
    {
        if (! $this->isSales() || ! $this->selectedCustomerId) {
            return null;
        }

        return Customer::where('id', $this->selectedCustomerId)
            ->where('sales_person_id', $this->salesPerson->id)
            ->first();
    }

    public function getWarehousesProperty()
    {
        return Warehouse::where('is_active', true)->get();
    }

    /**
     * Buat Sales Order dari isi keranjang, otomatis tersimpan ke toko yang dipilih sales.
     */
    protected function placeSalesOrder(): void
    {
        $customer = $this->selectedCustomer;

        if (! $customer) {
            $this->dispatch('toast', type: 'error', message: 'Pilih toko terlebih dahulu.');
            return;
        }

        $this->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'payment_type' => 'required|in:cash,tempo',
        ]);

        $cart = app(CartService::class);
        $items = $cart->items();

        if ($items->isEmpty()) {
            $this->dispatch('toast', type: 'error', message: 'Keranjang belanja masih kosong.');
            return;
        }

        foreach ($items as $item) {
            $available = $item['variant'] ? $item['variant']->stock : $item['product']->total_stock;
            $label = $item['variant'] ? "{$item['product']->name} ({$item['variant']->name})" : $item['product']->name;

            if ($item['qty'] > $available) {
                $this->dispatch('toast', type: 'error', message: "Stok \"{$label}\" tidak mencukupi. Tersisa {$available}.");
                return;
            }
        }

        $subtotal = (float) $items->sum('subtotal');

        $order = DB::transaction(function () use ($customer, $items, $subtotal, $cart) {
    $order = SalesOrder::create([
        'customer_id' => $customer->id,
        'sales_person_id' => $this->salesPerson->id,
        'warehouse_id' => $this->warehouse_id,
        'order_date' => now(),
        'payment_type' => $this->payment_type,
        'due_date' => $this->payment_type === 'tempo' ? now()->addDays($customer->payment_term_days ?? 30) : now(),
        'status' => 'draft',
        'subtotal' => $subtotal,
        'discount' => 0,
        'tax' => 0,
        'shipping_cost' => 0,
        'grand_total' => $subtotal,
        'notes' => $this->notes,
        'source' => 'sales',
        'created_by' => auth()->id(),
    ]);

    foreach ($items as $item) {
        SalesOrderItem::create([
            'sales_order_id' => $order->id,
            'product_id' => $item['product']->id,
            'qty' => $item['qty'],
            'unit' => $item['product']->unit ?? 'pcs',
            'price' => $item['price'],
            'discount' => 0,
            'subtotal' => $item['subtotal'],
            'notes' => $item['variant'] ? 'Varian: ' . $item['variant']->name : null,
        ]);
    }

    $cart->clear();

    return $order;
});

        $this->dispatch('cart-updated');
        session()->flash('toast', ['type' => 'success', 'title' => 'Sukses', 'message' => 'Order untuk toko ' . $customer->store_name . ' berhasil dibuat!']);

        $this->redirect(route('sales.orders.show', $order->id), navigate: true);
    }

    public function placeOrder(OnlineOrderService $service): void
    {
        if ($this->isSales()) {
            $this->placeSalesOrder();
            return;
        }

        $customer = Auth::user()->customer;

        if (! $customer) {
            $this->dispatch('toast', type: 'error', message: 'Lengkapi profil toko Anda terlebih dahulu.');
            return;
        }

        try {
            $order = $service->checkout($customer, ['notes' => $this->notes]);

            $this->dispatch('cart-updated');

            $this->redirect(route('shop.order.success', $order), navigate: true);
        } catch (ValidationException $e) {
            $this->dispatch('toast', type: 'error', message: collect($e->errors())->flatten()->first(), title: 'Gagal Membuat Pesanan');
        }
    }

    public function render()
    {
        $cart = app(CartService::class);
        $items = $cart->items();

        $customer = $this->isSales() ? null : Auth::user()->customer;

        return view('livewire.shop.checkout', [
            'customer' => $customer,
            'items' => $items,
            'subtotal' => (float) $items->sum('subtotal'),
        ]);
    }
}