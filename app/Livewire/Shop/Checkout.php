<?php

namespace App\Livewire\Shop;

use App\Models\Customer;
use App\Services\CartService;
use App\Services\OnlineOrderService;
use Illuminate\Support\Facades\Auth;
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

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user->customer) {
            $this->owner_name = $user->name;
        }
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

    public function placeOrder(OnlineOrderService $service): void
    {
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
        $customer = Auth::user()->customer;
        $cart = app(CartService::class);
        $items = $cart->items($customer?->customer_type ?? 'retail');

        return view('livewire.shop.checkout', [
            'customer' => $customer,
            'items' => $items,
            'subtotal' => (float) $items->sum('subtotal'),
        ]);
    }
}
