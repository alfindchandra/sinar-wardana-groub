<?php

namespace App\Livewire\Admin;

use App\Models\SalesPerson;
use App\Models\SalesTarget;
use App\Models\SalesOrder;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Target Omset Sales')]
class SalesTargetManager extends Component
{
    public $selectedMonth;
    public $selectedYear;

    public $showModal = false;
    public $editSalesPersonId;
    public $editTargetAmount;

    public function mount()
    {
        $this->selectedMonth = date('n');
        $this->selectedYear = date('Y');
    }

    public function openModal($salesPersonId, $currentTarget = 0)
    {
        $this->editSalesPersonId = $salesPersonId;
        $this->editTargetAmount = $currentTarget;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editSalesPersonId = null;
        $this->editTargetAmount = 0;
    }

    public function saveTarget()
    {
        $this->validate([
            'editSalesPersonId' => 'required|exists:sales_persons,id',
            'editTargetAmount' => 'required|numeric|min:0',
        ]);

        $target = SalesTarget::firstOrNew([
            'sales_person_id' => $this->editSalesPersonId,
            'month' => $this->selectedMonth,
            'year' => $this->selectedYear,
        ]);

        $target->target_amount = $this->editTargetAmount;
        $target->save();

        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Sukses',
            'message' => 'Target omset berhasil disimpan.',
        ]);

        $this->closeModal();
    }

    public function render()
    {
        $salesPersons = SalesPerson::active()->get();
        $targets = SalesTarget::where('month', $this->selectedMonth)
            ->where('year', $this->selectedYear)
            ->get()
            ->keyBy('sales_person_id');

        $achievements = [];
        foreach ($salesPersons as $sp) {
            $achievements[$sp->id] = SalesOrder::where('sales_person_id', $sp->id)
                ->whereMonth('order_date', $this->selectedMonth)
                ->whereYear('order_date', $this->selectedYear)
                ->whereIn('status', ['confirmed', 'processing', 'shipped', 'completed'])
                ->sum('grand_total');
        }

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('livewire.admin.sales-target-manager', [
            'salesPersons' => $salesPersons,
            'targets' => $targets,
            'achievements' => $achievements,
            'months' => $months,
        ]);
    }
}
