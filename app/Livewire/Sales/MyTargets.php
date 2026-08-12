<?php

namespace App\Livewire\Sales;

use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use App\Models\SalesCommission;
use App\Models\SalesTarget;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.sales')]
class MyTargets extends Component
{
    use EnsuresSalesPerson;

    public function title(): string
    {
        return 'Target & Komisi';
    }

    public function render()
    {
        $targets = SalesTarget::where('sales_person_id', $this->salesPerson->id)
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->take(12)
            ->get();

        $currentTarget = $targets->first(fn ($t) => $t->month === now()->month && $t->year === now()->year);

        $commissions = SalesCommission::where('sales_person_id', $this->salesPerson->id)
            ->orderByDesc('period_year')
            ->orderByDesc('period_month')
            ->take(10)
            ->get();

        return view('livewire.sales.my-targets', [
            'targets' => $targets,
            'currentTarget' => $currentTarget,
            'commissions' => $commissions,
        ]);
    }
}
