<?php

namespace App\Livewire\Sales;

use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.sales', ['hideFab' => true])]
class StoreRegister extends Component
{
    use EnsuresSalesPerson;
    use WithFileUploads;

    public $store_name;
    public $owner_name;
    public $phone;
    public $address;
    public $city;
    public $area;
    public $customer_type = 'retail';
    
    public $store_photo;
    public $latitude;
    public $longitude;

    public function rules()
    {
        return [
            'store_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'customer_type' => 'required|in:retail,agen,distributor',
            'store_photo' => 'nullable|image|max:3072',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ];
    }


    public function title(): string
    {
        return 'Daftarkan Toko Baru';
    }

    public function render()
    {
        return view('livewire.sales.store-register');
    }

    public function save()
    {
        $this->validate();

        $photoPath = null;
        if ($this->store_photo) {
            $photoPath = $this->store_photo->store('store-photos', 'public');
        }

        // Generate unique code
        $count = Customer::count();
        $code = 'CUST' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);

        $customer = Customer::create([
            'sales_person_id' => $this->salesPerson->id,
            'code' => $code,
            'store_name' => $this->store_name,
            'owner_name' => $this->owner_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'area' => $this->area,
            'customer_type' => $this->customer_type,
            'store_photo' => $photoPath,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_active' => true,
        ]);

        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Toko baru berhasil didaftarkan.'
        ]);

        $this->redirect(route('sales.stores.show', $customer->id), navigate: true);
    }
}
