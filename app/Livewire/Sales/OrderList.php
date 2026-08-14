<?php

namespace App\Livewire\Sales;

use App\Models\SalesOrder;
use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.sales')]
class OrderList extends Component
{
    use EnsuresSalesPerson;
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    #[Title('Orderan Saya')]
    public function title()
    {
        return 'Orderan Saya';
    }

    public function render()
    {
        $this->ensureSalesPersonExists();

        $query = SalesOrder::with('customer')
            ->where('sales_person_id', $this->salesPerson->id);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('so_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('customer', function($q2) {
                      $q2->where('store_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $orders = $query->latest('order_date')->paginate(10);

        return view('livewire.sales.order-list', [
            'orders' => $orders
        ]);
    }
}
