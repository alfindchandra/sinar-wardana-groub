<?php

namespace App\Livewire\Sales;

use App\Models\SalesOrder;
use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.sales', ['hideFab' => true])]
class OrderDetail extends Component
{
    use EnsuresSalesPerson;

    public SalesOrder $salesOrder;

    public function mount(SalesOrder $salesOrder)
    {
        $this->ensureSalesPersonExists();
        
        if ($salesOrder->sales_person_id !== $this->salesPerson->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $this->salesOrder = $salesOrder->load(['customer', 'items.product', 'warehouse']);
    }

    #[Title('Order Detail')]
    public function title()
    {
        return 'Order ' . $this->salesOrder->so_number;
    }

    public function render()
    {
        return view('livewire.sales.order-detail');
    }
}
