<?php

namespace App\Livewire\Sales;

use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use App\Models\Customer;
use App\Models\SalesVisit;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.sales', ['hideFab' => true])]
class VisitCheckIn extends Component
{
    use EnsuresSalesPerson, WithFileUploads;

    public string $search = '';
    public ?int $customer_id = null;
    public ?string $customer_name = null;

    public ?float $latitude = null;
    public ?float $longitude = null;
    public bool $locationCaptured = false;
    public bool $locationDenied = false;

    public ?string $notes = null;
    public $photo = null;

    public function title(): string
    {
        return 'Check-in Kunjungan';
    }

    public function updatingSearch(): void
    {
        $this->customer_id = null;
        $this->customer_name = null;
    }

    public function selectCustomer(int $customerId, string $customerName): void
    {
        $this->customer_id = $customerId;
        $this->customer_name = $customerName;
        $this->search = '';
    }

    public function clearCustomer(): void
    {
        $this->customer_id = null;
        $this->customer_name = null;
    }

    /**
     * Dipanggil dari JS (Alpine) setelah navigator.geolocation berhasil mendapat koordinat.
     */
    public function setLocation(float $lat, float $lng): void
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->locationCaptured = true;
        $this->locationDenied = false;
    }

    public function locationFailed(): void
    {
        $this->locationDenied = true;
        $this->locationCaptured = false;
    }

    public function submit(): void
    {
        $this->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'max:3072'],
        ], [
            'latitude.required' => 'Lokasi GPS wajib diaktifkan sebelum check-in.',
        ]);

        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('sales-visits', 'public');
        }

        $visit = SalesVisit::create([
            'sales_person_id' => $this->salesPerson->id,
            'customer_id' => $this->customer_id,
            'visit_date' => now()->toDateString(),
            'check_in_time' => now(),
            'check_in_latitude' => $this->latitude,
            'check_in_longitude' => $this->longitude,
            'photo' => $photoPath,
            'notes' => $this->notes,
            'created_by' => auth()->id(),
        ]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Check-in berhasil dicatat.', 'title' => 'Berhasil']);

        $this->redirect(route('sales.visits.show', $visit), navigate: true);
    }

    public function render()
    {
        $customers = collect();

        if ($this->search !== '' && ! $this->customer_id) {
            $customers = Customer::active()
                ->where('sales_person_id', $this->salesPerson->id)
                ->search($this->search)
                ->orderBy('store_name')
                ->limit(8)
                ->get();
        }

        return view('livewire.sales.visit-check-in', [
            'customers' => $customers,
        ]);
    }
}
