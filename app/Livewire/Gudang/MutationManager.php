<?php

namespace App\Livewire\Gudang;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockMutation;
use App\Models\StockMutationItem;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class MutationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $showModal = false;
    public $isEdit = false;
    public $showDetailModal = false;

    public $mutationId;
    public $from_warehouse_id;
    public $to_warehouse_id;
    public $mutation_date;
    public $notes;
    public $status = 'draft';
    
    public $items = [];

    public $detailMutation = null;

    protected $listeners = ['deleteConfirm' => 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'qty' => 1];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function create()
    {
        $this->resetForm();
        $this->mutation_date = date('Y-m-d');
        $this->addItem();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->isEdit = true;
        
        $mutation = StockMutation::with('items')->findOrFail($id);
        
        $this->mutationId = $mutation->id;
        $this->from_warehouse_id = $mutation->from_warehouse_id;
        $this->to_warehouse_id = $mutation->to_warehouse_id;
        $this->mutation_date = $mutation->mutation_date;
        $this->notes = $mutation->notes;
        $this->status = $mutation->status;
        
        $this->items = [];
        foreach ($mutation->items as $item) {
            $this->items[] = [
                'product_id' => $item->product_id,
                'qty' => $item->qty
            ];
        }
        
        $this->showModal = true;
    }

    public function viewDetail($id)
    {
        $this->detailMutation = StockMutation::with(['fromWarehouse', 'toWarehouse', 'items.product', 'creator'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function save()
    {
        $this->validate([
            'from_warehouse_id' => 'required',
            'to_warehouse_id' => 'required|different:from_warehouse_id',
            'mutation_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required',
            'items.*.qty' => 'required|integer|min:1',
        ], [
            'to_warehouse_id.different' => 'Gudang tujuan harus berbeda dengan gudang asal.',
            'items.required' => 'Minimal harus ada 1 item.',
            'items.min' => 'Minimal harus ada 1 item.',
            'items.*.product_id.required' => 'Produk harus dipilih.',
            'items.*.qty.required' => 'Jumlah harus diisi.',
            'items.*.qty.min' => 'Jumlah minimal 1.',
        ]);

        try {
            DB::beginTransaction();

            if ($this->isEdit) {
                $mutation = StockMutation::findOrFail($this->mutationId);
                $mutation->update([
                    'from_warehouse_id' => $this->from_warehouse_id,
                    'to_warehouse_id' => $this->to_warehouse_id,
                    'mutation_date' => $this->mutation_date,
                    'notes' => $this->notes,
                ]);

                // Delete old items
                StockMutationItem::where('stock_mutation_id', $mutation->id)->delete();
            } else {
                $mutation = StockMutation::create([
                    'mutation_number' => $this->generateMutationNumber(),
                    'from_warehouse_id' => $this->from_warehouse_id,
                    'to_warehouse_id' => $this->to_warehouse_id,
                    'mutation_date' => $this->mutation_date,
                    'status' => 'draft',
                    'notes' => $this->notes,
                    'created_by' => auth()->id(),
                ]);
            }

            // Insert new items
            foreach ($this->items as $item) {
                StockMutationItem::create([
                    'stock_mutation_id' => $mutation->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                ]);
            }

            DB::commit();

            $this->showModal = false;
            $this->dispatch('toast', [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => $this->isEdit ? 'Mutasi berhasil diperbarui.' : 'Mutasi berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', [
                'type' => 'error',
                'title' => 'Gagal',
                'message' => 'Terjadi kesalahan saat menyimpan data.'
            ]);
        }
    }

    public function triggerDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Hapus Mutasi?',
            'text' => 'Mutasi yang berstatus draft dapat dihapus. Anda yakin?',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $mutation = StockMutation::findOrFail($id);
        
        if ($mutation->status === 'draft') {
            $mutation->delete();
            $this->dispatch('toast', [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Mutasi berhasil dihapus.'
            ]);
        } else {
            $this->dispatch('toast', [
                'type' => 'error',
                'title' => 'Gagal',
                'message' => 'Hanya mutasi berstatus draft yang dapat dihapus.'
            ]);
        }
    }

    public function approve($id)
    {
        $mutation = StockMutation::findOrFail($id);
        
        if ($mutation->status === 'draft') {
            $mutation->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
            ]);
            $this->dispatch('toast', [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Mutasi berhasil disetujui.'
            ]);
        }
    }

    public function resetForm()
    {
        $this->isEdit = false;
        $this->mutationId = null;
        $this->from_warehouse_id = '';
        $this->to_warehouse_id = '';
        $this->mutation_date = '';
        $this->notes = '';
        $this->status = 'draft';
        $this->items = [];
        $this->resetErrorBag();
    }

    private function generateMutationNumber()
    {
        $prefix = 'MUT-' . date('Ym');
        $lastMutation = StockMutation::where('mutation_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastMutation) {
            $lastNumber = (int) substr($lastMutation->mutation_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        $query = StockMutation::with(['fromWarehouse', 'toWarehouse', 'items', 'creator']);

        if ($this->search) {
            $query->where('mutation_number', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.gudang.mutation-manager', [
            'mutations' => $query->latest()->paginate(10),
            'warehouses' => Warehouse::where('is_active', true)->get(),
            'products' => Product::where('is_active', true)->get(),
        ])->layout('layouts.app');
    }
}
