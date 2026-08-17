<?php

namespace App\Livewire\Pembelian;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\SupplierReturn;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierReturnManager extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $filterSupplier = '';
    public $filterStatus = '';

    // Modal state
    public $showModal = false;
    public $showDetailModal = false;
    public $isEdit = false;
    public $returnId;
    public $detailReturn;

    // Form fields
    public $purchase_order_id, $supplier_id, $warehouse_id, $return_date, $reason, $notes;
    public $items = [];

    protected $listeners = ['deleteConfirm' => 'delete'];

    public function mount()
    {
        $this->return_date = now()->toDateString();
    }

    protected function rules()
    {
        return [
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'return_date' => 'required|date',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.reason' => 'nullable|string|max:255',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->addItem();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $return = SupplierReturn::with('items')->findOrFail($id);

        $this->returnId = $return->id;
        $this->purchase_order_id = $return->purchase_order_id;
        $this->supplier_id = $return->supplier_id;
        $this->warehouse_id = $return->warehouse_id;
        $this->return_date = optional($return->return_date)->toDateString();
        $this->reason = $return->reason;
        $this->notes = $return->notes;

        $this->items = $return->items->map(fn ($item) => [
            'product_id' => $item->product_id,
            'qty' => $item->qty,
            'price' => $item->price,
            'reason' => $item->reason,
        ])->toArray();

        if (empty($this->items)) {
            $this->addItem();
        }

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function viewDetail($id)
    {
        $this->detailReturn = SupplierReturn::with(['purchaseOrder', 'supplier', 'warehouse', 'items.product', 'creator', 'approver'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'qty' => 1, 'price' => 0, 'reason' => ''];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function getItemSubtotal($index)
    {
        $item = $this->items[$index] ?? null;
        if (! $item) {
            return 0;
        }

        return max(($item['qty'] ?? 0) * ($item['price'] ?? 0), 0);
    }

    public function getSubtotalProperty()
    {
        $total = 0;
        foreach ($this->items as $index => $item) {
            $total += $this->getItemSubtotal($index);
        }

        return $total;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $subtotal = 0;
            $itemsData = [];

            foreach ($this->items as $item) {
                $itemSubtotal = max($item['qty'] * $item['price'], 0);
                $subtotal += $itemSubtotal;
                $itemsData[] = [
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $itemSubtotal,
                    'reason' => $item['reason'] ?? null,
                ];
            }

            $payload = [
                'purchase_order_id' => $this->purchase_order_id ?: null,
                'supplier_id' => $this->supplier_id,
                'warehouse_id' => $this->warehouse_id,
                'return_date' => $this->return_date,
                'reason' => $this->reason,
                'notes' => $this->notes,
                'subtotal' => $subtotal,
            ];

            if ($this->isEdit) {
                $return = SupplierReturn::findOrFail($this->returnId);
                $return->update($payload);
                $return->items()->delete();
            } else {
                $payload['created_by'] = Auth::id();
                $payload['status'] = 'draft';
                $return = SupplierReturn::create($payload);
            }

            foreach ($itemsData as $data) {
                $return->items()->create($data);
            }
        });

        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => $this->isEdit ? 'Retur berhasil diperbarui.' : 'Retur berhasil dibuat.']);
        $this->showModal = false;
        $this->resetForm();
    }

    public function approve($id)
    {
        $return = SupplierReturn::findOrFail($id);

        if ($return->status !== 'draft') {
            $this->dispatch('toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Hanya retur berstatus draft yang bisa disetujui.']);
            return;
        }

        $return->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Retur berhasil disetujui.']);
    }

    /**
     * Mark the return as completed and deduct stock from the warehouse.
     */
    public function complete($id, StockService $stockService)
    {
        $return = SupplierReturn::with('items')->findOrFail($id);

        if ($return->status !== 'approved') {
            $this->dispatch('toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Hanya retur yang sudah disetujui yang bisa diselesaikan.']);
            return;
        }

        DB::transaction(function () use ($return, $stockService) {
            foreach ($return->items as $item) {
                $stockService->decreaseStock(
                    $item->product_id,
                    $return->warehouse_id,
                    $item->qty,
                    StockMovementType::RETURN,
                    SupplierReturn::class,
                    $return->id,
                    null,
                    'Retur ke supplier ' . $return->return_number
                );
            }

            $return->update(['status' => 'completed']);
        });

        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Retur diselesaikan, stok telah dikurangi.']);
    }

    public function cancel($id)
    {
        $return = SupplierReturn::findOrFail($id);

        if (in_array($return->status, ['completed', 'cancelled'])) {
            $this->dispatch('toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Retur ini tidak bisa dibatalkan.']);
            return;
        }

        $return->update(['status' => 'cancelled']);
        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Retur berhasil dibatalkan.']);
    }

    public function triggerDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Apakah Anda yakin?',
            'text' => 'Retur ini akan dihapus.',
            'id' => $id,
        ]);
    }

    public function delete($id)
    {
        $return = SupplierReturn::findOrFail($id);

        if ($return->status !== 'draft') {
            $this->dispatch('toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Hanya retur berstatus draft yang bisa dihapus.']);
            return;
        }

        $return->delete();
        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Retur berhasil dihapus.']);
    }

    public function resetForm()
    {
        $this->reset(['purchase_order_id', 'supplier_id', 'warehouse_id', 'reason', 'notes', 'items', 'returnId']);
        $this->return_date = now()->toDateString();
        $this->resetValidation();
    }

    public function render()
    {
        $supplierReturns = SupplierReturn::with(['purchaseOrder', 'supplier', 'warehouse'])
            ->when($this->search, fn ($q) => $q->where('return_number', 'like', '%' . $this->search . '%'))
            ->when($this->filterSupplier, fn ($q) => $q->where('supplier_id', $this->filterSupplier))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.pembelian.supplier-return-manager', [
            'supplierReturns' => $supplierReturns,
            'suppliers' => Supplier::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'products' => Product::where('is_active', true)->orderBy('name')->get(),
            'purchaseOrders' => PurchaseOrder::whereIn('status', ['approved', 'received'])->orderByDesc('id')->get(),
        ])->layout('layouts.app');
    }
}
