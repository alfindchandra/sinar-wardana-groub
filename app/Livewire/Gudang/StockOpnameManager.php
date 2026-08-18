<?php

namespace App\Livewire\Gudang;

use App\Models\Product;
use App\Models\StockOpname;
use App\Models\Warehouse;
use Livewire\Component;
use Livewire\WithPagination;

class StockOpnameManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $warehouseFilter = '';
    public $showModal = false;
    public $isEdit = false;
    public $showDetailModal = false;

    public $opnameId = null;
    public $warehouse_id = '';
    public $opname_date = '';
    public $notes = '';
    public $status = 'draft';

    public $items = [];
    public $opnameDetail = null;

    protected $listeners = ['deleteConfirm' => 'delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
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
            'warehouse_id' => 'required',
            'opname_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required',
            'items.*.system_qty' => 'required|numeric|min:0',
            'items.*.actual_qty' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ];
    }

    public function addItem()
    {
        $this->items[] = [
            'product_id' => '',
            'system_qty' => 0,
            'actual_qty' => 0,
            'notes' => ''
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    /**
     * Memilih produk dan otomatis mengisi stok sistem dari database
     */
    public function selectProduct($index, $productId)
    {
        $product = Product::find($productId);

        if ($product) {
            $this->items[$index]['product_id'] = (string) $product->id;
            // Mengambil stok sistem dari database
            $this->items[$index]['system_qty'] = (int) ($product->stock ?? 0);
            $this->items[$index]['actual_qty'] = (int) ($product->stock ?? 0); // Default set actual sama dengan sistem
        }
    }

    /**
     * Reset pilihan produk pada baris tertentu
     */
    public function clearProduct($index)
    {
        $this->items[$index]['product_id'] = '';
        $this->items[$index]['system_qty'] = 0;
        $this->items[$index]['actual_qty'] = 0;
    }

    public function create()
    {
        $this->resetForm();
        $this->opname_date = date('Y-m-d');
        $this->addItem();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->isEdit = true;
        $this->opnameId = $id;

        $opname = StockOpname::with('items.product')->findOrFail($id);
        $this->warehouse_id = $opname->warehouse_id;
        $this->opname_date = $opname->opname_date;
        $this->notes = $opname->notes;
        $this->status = $opname->status;

        foreach ($opname->items as $item) {
            $this->items[] = [
                'product_id' => (string) $item->product_id,
                'system_qty' => (int) $item->system_qty,
                'actual_qty' => (int) $item->actual_qty,
                'notes' => $item->notes
            ];
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $opnameNumber = 'SO-' . date('Ymd') . '-' . rand(1000, 9999);
        if ($this->isEdit && $this->opnameId) {
            $existing = StockOpname::find($this->opnameId);
            if ($existing) {
                $opnameNumber = $existing->opname_number;
            }
        }

        $opname = StockOpname::updateOrCreate(
            ['id' => $this->opnameId],
            [
                'warehouse_id' => $this->warehouse_id,
                'opname_date' => $this->opname_date,
                'notes' => $this->notes,
                'status' => $this->status ?: 'draft',
                'created_by' => auth()->id(),
                'opname_number' => $opnameNumber,
            ]
        );

        $opname->items()->delete();

        foreach ($this->items as $item) {
            $systemQty = (int) $item['system_qty'];
            $actualQty = (int) $item['actual_qty'];
            $difference = $actualQty - $systemQty;

            $opname->items()->create([
                'product_id' => $item['product_id'],
                'system_qty' => $systemQty,
                'actual_qty' => $actualQty,
                'difference' => $difference,
                'notes' => $item['notes'] ?? null,
            ]);
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Stock Opname berhasil disimpan']);
    }

    public function viewDetail($id)
    {
        $this->opnameDetail = StockOpname::with(['warehouse', 'items.product', 'creator'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function approve($id)
    {
        $opname = StockOpname::findOrFail($id);
        $opname->update([
            'status' => 'completed',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Stock Opname berhasil disetujui']);
    }

    public function triggerDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Hapus Data?',
            'text' => 'Apakah Anda yakin ingin menghapus opname ini?',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $opname = StockOpname::findOrFail($id);
        $opname->items()->delete();
        $opname->delete();

        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Stock Opname berhasil dihapus']);
    }

    public function resetForm()
    {
        $this->reset(['opnameId', 'warehouse_id', 'opname_date', 'notes', 'status', 'items', 'isEdit']);
        $this->resetValidation();
    }

    public function render()
    {
        $query = StockOpname::with(['warehouse', 'items.product', 'creator'])
            ->when($this->search, function ($q) {
                $q->where('opname_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->warehouseFilter, function ($q) {
                $q->where('warehouse_id', $this->warehouseFilter);
            })
            ->latest();

        return view('livewire.gudang.stock-opname-manager', [
            'opnames' => $query->paginate(10),
            'warehouses' => Warehouse::where('is_active', true)->get(),
            'products' => Product::where('is_active', true)->get(),
        ])->layout('layouts.app');
    }
}