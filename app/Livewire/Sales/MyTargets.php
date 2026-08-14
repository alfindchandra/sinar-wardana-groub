<?php

namespace App\Livewire\Sales;

use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use App\Models\SalesCommission;
use App\Models\SalesOrder;
use App\Models\SalesTarget;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.sales')]
class MyTargets extends Component
{
    use EnsuresSalesPerson;

    public function title(): string
    {
        return 'Target Omset & Komisi';
    }

    public function render()
    {
        $targets = SalesTarget::where('sales_person_id', $this->salesPerson->id)
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->take(12)
            ->get();

        // Recalculate achieved_amount for current month from actual orders
        $currentTarget = $targets->first(fn ($t) => $t->month === now()->month && $t->year === now()->year);

        if ($currentTarget) {
            $currentTarget->achieved_amount = SalesOrder::where('sales_person_id', $this->salesPerson->id)
                ->whereMonth('order_date', now()->month)
                ->whereYear('order_date', now()->year)
                ->whereIn('status', ['confirmed', 'processing', 'shipped', 'completed'])
                ->sum('grand_total');
        }

        $commissions = SalesCommission::where('sales_person_id', $this->salesPerson->id)
            ->orderByDesc('period_year')
            ->orderByDesc('period_month')
            ->take(10)
            ->get();

        // Monthly omset summary (last 6 months)
        $omsetSummary = collect();
        for ($i = 0; $i < 6; $i++) {
            $date = now()->subMonths($i);
            $omset = SalesOrder::where('sales_person_id', $this->salesPerson->id)
                ->whereMonth('order_date', $date->month)
                ->whereYear('order_date', $date->year)
                ->whereIn('status', ['confirmed', 'processing', 'shipped', 'completed'])
                ->sum('grand_total');

            $orderCount = SalesOrder::where('sales_person_id', $this->salesPerson->id)
                ->whereMonth('order_date', $date->month)
                ->whereYear('order_date', $date->year)
                ->count();

            $omsetSummary->push([
                'label' => $date->translatedFormat('M Y'),
                'omset' => $omset,
                'orders' => $orderCount,
            ]);
        }

        return view('livewire.sales.my-targets', [
            'targets' => $targets,
            'currentTarget' => $currentTarget,
            'commissions' => $commissions,
            'omsetSummary' => $omsetSummary,
        ]);
    }
}
