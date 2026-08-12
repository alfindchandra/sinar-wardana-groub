<?php

namespace App\Livewire\Sales;

use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use App\Models\SalesCommission;
use App\Models\SalesTarget;
use App\Models\SalesVisit;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.sales')]
class Dashboard extends Component
{
    use EnsuresSalesPerson;

    public function title(): string
    {
        return 'Beranda Sales';
    }

    public function render()
    {
        $todayVisits = SalesVisit::where('sales_person_id', $this->salesPerson->id)
            ->today()
            ->with('customer:id,store_name,owner_name')
            ->orderByDesc('check_in_time')
            ->get();

        $target = SalesTarget::where('sales_person_id', $this->salesPerson->id)
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->first();

        $commissionThisMonth = SalesCommission::where('sales_person_id', $this->salesPerson->id)
            ->byPeriod(now()->month, now()->year)
            ->sum('amount');

        $visitsThisMonth = SalesVisit::where('sales_person_id', $this->salesPerson->id)
            ->whereMonth('visit_date', now()->month)
            ->whereYear('visit_date', now()->year)
            ->count();

        return view('livewire.sales.dashboard', [
            'todayVisits' => $todayVisits,
            'target' => $target,
            'commissionThisMonth' => $commissionThisMonth,
            'visitsThisMonth' => $visitsThisMonth,
        ]);
    }
}
