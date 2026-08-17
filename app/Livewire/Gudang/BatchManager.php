<?php

namespace App\Livewire\Gudang;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockBatch;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;

class BatchManager extends Component
{
    use WithPagination;

    public $search = '';
    public $warehouseFilter = '';
    public $showModal = false;
    public $isEdit = false;

    public $batchId;
    public $product_id;
    public $warehouse_id;
    public $batch_number;
    public $supplier_id;
    public $received_date;
    public $initial_qty;
    public $remaining_qty;
    public $expiry_date;
    public $notes;

    protected $listeners = ['deleteConfirm' => 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingWarehouseFilter()
    {
        $this->resetPage();
    }

    public function rules()
    {
        return [
            'product_id' => 'required',
            'warehouse_id' => 'required',
            'batch_number' => 'required|string',
            'received_date' => 'required|date',
            'initial_qty' => 'required|integer|min:1',
            'remaining_qty' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
            'supplier_id' => 'nullable',
            'notes' => 'nullable|string',
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->received_date = Carbon::today()->format('Y-m-d');
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->isEdit = true;
        $this->batchId = $id;

        $batch = StockBatch::findOrFail($id);
        $this->product_id = $batch->product_id;
        $this->warehouse_id = $batch->warehouse_id;
        $this->batch_number = $batch->batch_number;
        $this->supplier_id = $batch->supplier_id;
        $this->received_date = $batch->received_date ? Carbon::parse($batch->received_date)->format('Y-m-d') : null;
        $this->initial_qty = $batch->initial_qty;
        $this->remaining_qty = $batch->remaining_qty;
        $this->expiry_date = $batch->expiry_date ? Carbon::parse($batch->expiry_date)->format('Y-m-d') : null;
        $this->notes = $batch->notes;

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'product_id' => $this->product_id,
            'warehouse_id' => $this->warehouse_id,
            'batch_number' => $this->batch_number,
            'supplier_id' => $this->supplier_id ?: null,
            'received_date' => $this->received_date,
            'initial_qty' => $this->initial_qty,
            'remaining_qty' => $this->remaining_qty !== '' && $this->remaining_qty !== null ? $this->remaining_qty : $this->initial_qty,
            'expiry_date' => $this->expiry_date ?: null,
            'notes' => $this->notes,
        ];

        if ($this->isEdit) {
            StockBatch::findOrFail($this->batchId)->update($data);
            $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Batch berhasil diperbarui.']);
        } else {
            StockBatch::create($data);
            $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Batch berhasil ditambahkan.']);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function triggerDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Hapus Batch?',
            'text' => 'Data batch yang dihapus tidak dapat dikembalikan.',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        StockBatch::findOrFail($id)->delete();
        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Batch berhasil dihapus.']);
    }

    public function resetForm()
    {
        $this->isEdit = false;
        $this->batchId = null;
        $this->product_id = null;
        $this->warehouse_id = null;
        $this->batch_number = null;
        $this->supplier_id = null;
        $this->received_date = null;
        $this->initial_qty = null;
        $this->remaining_qty = null;
        $this->expiry_date = null;
        $this->notes = null;
        $this->resetValidation();
    }

    public function render()
    {
        $query = StockBatch::with(['product', 'warehouse', 'supplier'])
            ->when($this->search, function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('batch_number', 'like', '%' . $this->search . '%')
                         ->orWhereHas('product', function ($qProduct) {
                             $qProduct->where('name', 'like', '%' . $this->search . '%');
                         });
                });
            })
            ->when($this->warehouseFilter, function ($q) {
                $q->where('warehouse_id', $this->warehouseFilter);
            })
            ->latest();

        $batches = $query->paginate(10);
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('livewire.gudang.batch-manager', [
            'batches' => $batches,
            'warehouses' => $warehouses,
            'products' => $products,
            'suppliers' => $suppliers,
        ])->layout('layouts.app');
    }
}
