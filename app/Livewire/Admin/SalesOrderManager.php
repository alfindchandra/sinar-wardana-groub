<?php

namespace App\Livewire\Admin;

use App\Models\SalesOrder;
use App\Models\SalesPerson;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Orderan Sales')]
class SalesOrderManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $salesFilter = '';
    public $dateFrom = '';
    public $dateTo = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'salesFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updating($field)
    {
        if (in_array($field, ['search', 'statusFilter', 'salesFilter', 'dateFrom', 'dateTo'])) {
            $this->resetPage();
        }
    }

    public function updateStatus($orderId, $newStatus)
    {
        $order = SalesOrder::findOrFail($orderId);
        $order->status = $newStatus;
        $order->save();

        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Sukses',
            'message' => 'Status order berhasil diperbarui.',
        ]);
    }

    public function render()
    {
        $query = SalesOrder::with(['customer', 'salesPerson'])->latest()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('so_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($c) {
                            $c->where('store_name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->salesFilter, function ($q) {
                $q->where('sales_person_id', $this->salesFilter);
            })
            ->when($this->dateFrom, function ($q) {
                $q->whereDate('order_date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                $q->whereDate('order_date', '<=', $this->dateTo);
            });

        // For summary stats
        $statsQuery = clone $query;
        $totalOrders = $statsQuery->count();
        $totalOmset = $statsQuery->whereNotIn('status', ['draft', 'cancelled'])->sum('grand_total');
        $ordersToday = SalesOrder::whereDate('order_date', today())->count();

        $salesPersons = SalesPerson::active()->get();

        return view('livewire.admin.sales-order-manager', [
            'orders' => $query->paginate(15),
            'salesPersons' => $salesPersons,
            'totalOrders' => $totalOrders,
            'totalOmset' => $totalOmset,
            'ordersToday' => $ordersToday,
        ]);
    }
}
