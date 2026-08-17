<?php

namespace App\Livewire\Pembelian;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseOrderManager extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $filterSupplier = '';
    public $filterStatus = '';
    public $dateFrom = '';
    public $dateTo = '';

    // Modal state
    public $showModal = false;
    public $showDetailModal = false;
    public $isEdit = false;
    public $poId;
    public $detailPo;

    // Form fields
    public $supplier_id, $warehouse_id, $order_date, $expected_date, $notes;
    public $items = [];

    protected $listeners = ['deleteConfirm' => 'delete'];

    public function mount()
    {
        $this->order_date = now()->toDateString();
    }

    protected function rules()
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterSupplier()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
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
        $po = PurchaseOrder::with('items')->findOrFail($id);

        $this->poId = $po->id;
        $this->supplier_id = $po->supplier_id;
        $this->warehouse_id = $po->warehouse_id;
        $this->order_date = optional($po->order_date)->toDateString();
        $this->expected_date = optional($po->expected_date)->toDateString();
        $this->notes = $po->notes;

        $this->items = $po->items->map(fn ($item) => [
            'product_id' => $item->product_id,
            'qty' => $item->qty,
            'price' => $item->price,
            'discount' => $item->discount,
        ])->toArray();

        if (empty($this->items)) {
            $this->addItem();
        }

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function viewDetail($id)
    {
        $this->detailPo = PurchaseOrder::with(['supplier', 'warehouse', 'items.product', 'creator', 'approver'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function addItem()
    {
        $this->items[] = [
            'product_id' => '',
            'qty' => 1,
            'price' => 0,
            'discount' => 0,
        ];
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

        return max(($item['qty'] ?? 0) * ($item['price'] ?? 0) - ($item['discount'] ?? 0), 0);
    }

    public function getGrandTotalProperty()
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
                $itemSubtotal = max(($item['qty'] * $item['price']) - ($item['discount'] ?? 0), 0);
                $subtotal += $itemSubtotal;
                $itemsData[] = [
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $payload = [
                'supplier_id' => $this->supplier_id,
                'warehouse_id' => $this->warehouse_id,
                'order_date' => $this->order_date,
                'expected_date' => $this->expected_date,
                'notes' => $this->notes,
                'subtotal' => $subtotal,
                'grand_total' => $subtotal,
            ];

            if ($this->isEdit) {
                $po = PurchaseOrder::findOrFail($this->poId);
                $po->update($payload);
                $po->items()->delete();
            } else {
                $payload['created_by'] = Auth::id();
                $payload['status'] = 'draft';
                $po = PurchaseOrder::create($payload);
            }

            foreach ($itemsData as $data) {
                $po->items()->create($data);
            }
        });

        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => $this->isEdit ? 'PO berhasil diperbarui.' : 'PO berhasil dibuat.']);
        $this->showModal = false;
        $this->resetForm();
    }

    public function approve($id)
    {
        $po = PurchaseOrder::findOrFail($id);

        if ($po->status !== 'draft') {
            $this->dispatch('toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Hanya PO berstatus draft yang bisa disetujui.']);
            return;
        }

        $po->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'PO berhasil disetujui.']);
    }

    public function cancel($id)
    {
        $po = PurchaseOrder::findOrFail($id);

        if (in_array($po->status, ['closed', 'cancelled'])) {
            $this->dispatch('toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'PO ini tidak bisa dibatalkan.']);
            return;
        }

        $po->update(['status' => 'cancelled']);
        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'PO berhasil dibatalkan.']);
    }

    public function triggerDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Apakah Anda yakin?',
            'text' => 'PO ini akan dihapus.',
            'id' => $id,
        ]);
    }

    public function delete($id)
    {
        $po = PurchaseOrder::findOrFail($id);

        if ($po->status !== 'draft') {
            $this->dispatch('toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Hanya PO berstatus draft yang bisa dihapus.']);
            return;
        }

        $po->delete();
        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'PO berhasil dihapus.']);
    }

    public function resetForm()
    {
        $this->reset(['supplier_id', 'warehouse_id', 'expected_date', 'notes', 'items', 'poId']);
        $this->order_date = now()->toDateString();
        $this->resetValidation();
    }

    public function render()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'warehouse'])
            ->when($this->search, fn ($q) => $q->where('po_number', 'like', '%' . $this->search . '%'))
            ->when($this->filterSupplier, fn ($q) => $q->where('supplier_id', $this->filterSupplier))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('order_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('order_date', '<=', $this->dateTo))
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.pembelian.purchase-order-manager', [
            'purchaseOrders' => $purchaseOrders,
            'suppliers' => Supplier::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'products' => Product::where('is_active', true)->orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
