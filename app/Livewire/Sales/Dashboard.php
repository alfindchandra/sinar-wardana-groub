<?php

namespace App\Livewire\Sales;

use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use App\Models\SalesCommission;
use App\Models\SalesOrder;
use App\Models\SalesTarget;
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
        $todayOrders = SalesOrder::where('sales_person_id', $this->salesPerson->id)
            ->whereDate('order_date', now()->toDateString())
            ->with('customer:id,store_name,store_photo')
            ->orderByDesc('created_at')
            ->get();

        $target = SalesTarget::where('sales_person_id', $this->salesPerson->id)
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->first();

        $commissionThisMonth = SalesCommission::where('sales_person_id', $this->salesPerson->id)
            ->byPeriod(now()->month, now()->year)
            ->sum('amount');

        $omsetThisMonth = SalesOrder::where('sales_person_id', $this->salesPerson->id)
            ->whereMonth('order_date', now()->month)
            ->whereYear('order_date', now()->year)
            ->whereIn('status', ['confirmed', 'processing', 'shipped', 'completed'])
            ->sum('grand_total');

        $ordersThisMonth = SalesOrder::where('sales_person_id', $this->salesPerson->id)
            ->whereMonth('order_date', now()->month)
            ->whereYear('order_date', now()->year)
            ->count();

        $totalStores = $this->salesPerson->customers()->active()->count();

        return view('livewire.sales.dashboard', [
            'todayOrders' => $todayOrders,
            'target' => $target,
            'commissionThisMonth' => $commissionThisMonth,
            'omsetThisMonth' => $omsetThisMonth,
            'ordersThisMonth' => $ordersThisMonth,
            'totalStores' => $totalStores,
        ]);
    }
}
