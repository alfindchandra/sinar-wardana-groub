<?php

namespace App\Livewire\Sales;

use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.sales', ['hideFab' => true])]
class StoreDetail extends Component
{
    use EnsuresSalesPerson;

    public Customer $customer;

    public function mount(Customer $customer)
    {
        if ($customer->sales_person_id !== $this->salesPerson->id) {
            abort(403, 'Unauthorized action.');
        }

        $this->customer = $customer;
    }

    public function title(): string
    {
        return $this->customer->store_name;
    }

    public function render()
    {
        $recentOrders = $this->customer->salesOrders()
            ->latest()
            ->take(5)
            ->get();

        $totalOmset = $this->customer->salesOrders()
            ->whereIn('status', ['confirmed', 'processing', 'shipped', 'completed'])
            ->sum('grand_total');

        return view('livewire.sales.store-detail', [
            'recentOrders' => $recentOrders,
            'totalOmset' => $totalOmset,
        ]);
    }
}
