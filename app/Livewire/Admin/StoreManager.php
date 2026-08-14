<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Kelola Toko')]
class StoreManager extends Component
{
    use WithPagination;

    public $search = '';
    public $areaFilter = '';
    public $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'areaFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->is_active = !$customer->is_active;
        $customer->save();

        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Sukses',
            'message' => 'Status toko berhasil diperbarui.',
        ]);
    }

    public function render()
    {
        $query = Customer::with('salesPerson')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('store_name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%')
                        ->orWhere('owner_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->areaFilter, function ($q) {
                $q->where('area', $this->areaFilter);
            })
            ->when($this->statusFilter !== '', function ($q) {
                $q->where('is_active', $this->statusFilter);
            })
            ->orderBy('store_name');

        // Fetch distinct areas for filter
        $areas = Customer::select('area')->distinct()->whereNotNull('area')->pluck('area');

        return view('livewire.admin.store-manager', [
            'stores' => $query->paginate(15),
            'areas' => $areas,
        ]);
    }
}
