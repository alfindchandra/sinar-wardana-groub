<?php

namespace App\Livewire\Sales;

use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.sales')]
class StoreList extends Component
{
    use EnsuresSalesPerson;
    use WithPagination;

    public $search = '';

    public function mount()
    {
        $this->ensureSalesPerson();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[Title('Toko Saya')]
    public function render()
    {
        $query = Customer::where('sales_person_id', $this->salesPerson->id)
            ->where('is_active', true);
            
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('store_name', 'like', '%' . $this->search . '%')
                  ->orWhere('owner_name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('area', 'like', '%' . $this->search . '%');
            });
        }

        $stores = $query->orderBy('store_name')->paginate(10);

        return view('livewire.sales.store-list', [
            'stores' => $stores
        ]);
    }
}
