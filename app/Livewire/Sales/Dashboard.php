<?php

namespace App\Livewire\Sales;

use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
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
    $validStatuses = ['draft', 'confirmed', 'processing', 'shipped', 'completed'];

    // 1. Ambil orderan hari ini (cek rentang 00:00:00 sampai 23:59:59)
    $todayOrders = SalesOrder::where('sales_person_id', $this->salesPerson->id)
        ->where(function($q) {
            $q->whereDate('order_date', today())
              ->orWhereDate('created_at', today());
        })
        ->with('customer:id,store_name,store_photo')
        ->orderByDesc('created_at')
        ->get();

    // 2. Hitung omset hari ini dari orderan yang berstatus valid
    $omsetToday = SalesOrder::where('sales_person_id', $this->salesPerson->id)
        ->where(function($q) {
            $q->whereDate('order_date', today())
              ->orWhereDate('created_at', today());
        })
        ->whereIn('status', $validStatuses)
        ->sum('grand_total');

    $dailyTarget = 12000000;

    $target = SalesTarget::where('sales_person_id', $this->salesPerson->id)
        ->where('month', now()->month)
        ->where('year', now()->year)
        ->first();

    // 3. Omset bulan ini
    $omsetThisMonth = SalesOrder::where('sales_person_id', $this->salesPerson->id)
        ->whereMonth('order_date', now()->month)
        ->whereYear('order_date', now()->year)
        ->whereIn('status', $validStatuses)
        ->sum('grand_total');

    $ordersThisMonth = SalesOrder::where('sales_person_id', $this->salesPerson->id)
        ->whereMonth('order_date', now()->month)
        ->whereYear('order_date', now()->year)
        ->count();

    $totalStores = $this->salesPerson->customers()->where('is_active', true)->count();

    return view('livewire.sales.dashboard', [
        'todayOrders' => $todayOrders,
        'omsetToday' => (float) $omsetToday,
        'dailyTarget' => $dailyTarget,
        'target' => $target,
        'omsetThisMonth' => (float) $omsetThisMonth,
        'ordersThisMonth' => $ordersThisMonth,
        'totalStores' => $totalStores,
    ]);
}
}