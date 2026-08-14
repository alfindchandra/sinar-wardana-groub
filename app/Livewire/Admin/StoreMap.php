<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\SalesPerson;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Peta Toko')]
class StoreMap extends Component
{
    public $areaFilter = '';
    public $salesFilter = '';

    public function render()
    {
        $stores = Customer::with('salesPerson')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->when($this->areaFilter, function ($q) {
                $q->where('area', $this->areaFilter);
            })
            ->when($this->salesFilter, function ($q) {
                $q->where('sales_person_id', $this->salesFilter);
            })
            ->get()
            ->map(function ($store) {
                return [
                    'id' => $store->id,
                    'name' => $store->store_name,
                    'owner' => $store->owner_name,
                    'address' => $store->address,
                    'area' => $store->area,
                    'lat' => $store->latitude,
                    'lng' => $store->longitude,
                    'photo' => $store->store_photo ? \Storage::url($store->store_photo) : null,
                    'sales' => $store->salesPerson ? $store->salesPerson->name : 'N/A'
                ];
            });

        $areas = Customer::select('area')->distinct()->whereNotNull('area')->pluck('area');
        $salesPersons = SalesPerson::active()->get();

        return view('livewire.admin.store-map', [
            'stores' => $stores,
            'areas' => $areas,
            'salesPersons' => $salesPersons,
        ]);
    }
}
