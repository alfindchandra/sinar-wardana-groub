<?php

namespace App\Livewire\Sales;

use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use App\Models\SalesVisit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.sales')]
class VisitList extends Component
{
    use EnsuresSalesPerson, WithPagination;

    #[Url]
    public string $filter = 'today';

    public function title(): string
    {
        return 'Kunjungan Saya';
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = SalesVisit::where('sales_person_id', $this->salesPerson->id)
            ->with('customer:id,store_name,owner_name')
            ->orderByDesc('check_in_time');

        match ($this->filter) {
            'today' => $query->today(),
            'week' => $query->whereBetween('visit_date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]),
            'month' => $query->whereMonth('visit_date', now()->month)->whereYear('visit_date', now()->year),
            default => null,
        };

        $visits = $query->paginate(10);

        return view('livewire.sales.visit-list', [
            'visits' => $visits,
        ]);
    }
}
