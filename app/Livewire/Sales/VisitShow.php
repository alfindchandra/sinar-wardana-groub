<?php

namespace App\Livewire\Sales;

use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use App\Models\SalesVisit;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.sales', ['hideFab' => true])]
class VisitShow extends Component
{
    use EnsuresSalesPerson;

    public SalesVisit $visit;

    public function mount(SalesVisit $visit): void
    {
        abort_unless($visit->sales_person_id === $this->salesPerson->id, 403);

        $this->visit = $visit->load('customer');
    }

    public function title(): string
    {
        return 'Detail Kunjungan';
    }

    public function checkOut(float $lat, float $lng): void
    {
        if ($this->visit->check_out_time) {
            return;
        }

        $this->visit->update([
            'check_out_time' => now(),
            'check_out_latitude' => $lat,
            'check_out_longitude' => $lng,
        ]);

        $this->visit->refresh();
        $this->dispatch('toast', type: 'success', message: 'Check-out berhasil dicatat.', title: 'Berhasil');
    }

    public function checkOutFailed(): void
    {
        $this->dispatch('toast', type: 'error', message: 'Gagal mendapatkan lokasi GPS. Coba lagi.');
    }

    public function render()
    {
        return view('livewire.sales.visit-show');
    }
}
