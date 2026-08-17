<?php

namespace App\Livewire\Pembelian;

use App\Enums\StockMovementType;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\PurchaseOrder;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class GoodsReceiptManager extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $filterStatus = '';

    // Modal state
    public $showModal = false;
    public $showDetailModal = false;
    public $grId;
    public $detailGr;

    // Form fields
    public $purchase_order_id, $warehouse_id, $received_date, $notes;
    public $items = [];

    protected $listeners = ['deleteConfirm' => 'delete'];

    public function mount()
    {
        $this->received_date = now()->toDateString();
    }

    protected function rules()
    {
        return [
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'received_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty_ordered' => 'required|integer|min:0',
            'items.*.qty_received' => 'required|integer|min:0',
            'items.*.batch_number' => 'nullable|string|max:100',
            'items.*.expiry_date' => 'nullable|date',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function updatedPurchaseOrderId()
    {
        $this->items = [];

        if (! $this->purchase_order_id) {
            return;
        }

        $po = PurchaseOrder::with('items.product')->find($this->purchase_order_id);

        if (! $po) {
            return;
        }

        $this->warehouse_id = $po->warehouse_id;

        foreach ($po->items as $item) {
            // qty already received across previous goods receipts for this PO item
            $alreadyReceived = GoodsReceiptItem::where('purchase_order_item_id', $item->id)
                ->whereHas('goodsReceipt', fn ($q) => $q->where('status', 'completed'))
                ->sum('qty_received');

            $remaining = max($item->qty - $alreadyReceived, 0);

            $this->items[] = [
                'purchase_order_item_id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product?->name,
                'qty_ordered' => $remaining,
                'qty_received' => $remaining,
                'batch_number' => '',
                'expiry_date' => '',
            ];
        }
    }

    public function viewDetail($id)
    {
        $this->detailGr = GoodsReceipt::with(['purchaseOrder', 'warehouse', 'items.product', 'receiver'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $gr = GoodsReceipt::create([
                'purchase_order_id' => $this->purchase_order_id,
                'warehouse_id' => $this->warehouse_id,
                'received_date' => $this->received_date,
                'notes' => $this->notes,
                'status' => 'draft',
                'received_by' => Auth::id(),
            ]);

            foreach ($this->items as $item) {
                if ((int) $item['qty_received'] <= 0) {
                    continue;
                }

                $gr->items()->create([
                    'purchase_order_item_id' => $item['purchase_order_item_id'],
                    'product_id' => $item['product_id'],
                    'qty_ordered' => $item['qty_ordered'],
                    'qty_received' => $item['qty_received'],
                    'batch_number' => $item['batch_number'] ?: null,
                    'expiry_date' => $item['expiry_date'] ?: null,
                ]);
            }

            $this->grId = $gr->id;
        });

        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Penerimaan barang berhasil dibuat sebagai draft.']);
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Confirm/complete the goods receipt: adds stock, creates batches, and updates PO status.
     */
    public function complete($id, StockService $stockService)
    {
        $gr = GoodsReceipt::with('items')->findOrFail($id);

        if ($gr->status !== 'draft') {
            $this->dispatch('toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Hanya penerimaan berstatus draft yang bisa diselesaikan.']);
            return;
        }

        DB::transaction(function () use ($gr, $stockService) {
            foreach ($gr->items as $item) {
                if ($item->qty_received <= 0) {
                    continue;
                }

                $batch = $stockService->createBatch(
                    $item->product_id,
                    $gr->warehouse_id,
                    $item->batch_number,
                    $item->qty_received,
                    $gr->received_date,
                    $gr->purchaseOrder?->supplier_id,
                    $item->expiry_date
                );

                $stockService->increaseStock(
                    $item->product_id,
                    $gr->warehouse_id,
                    $item->qty_received,
                    StockMovementType::IN,
                    GoodsReceipt::class,
                    $gr->id,
                    $batch->id,
                    'Penerimaan barang ' . $gr->gr_number
                );
            }

            $gr->update(['status' => 'completed']);

            // Update PO status based on total received vs ordered across all completed GRs
            $po = $gr->purchaseOrder;
            if ($po) {
                $totalOrdered = $po->items()->sum('qty');
                $totalReceived = GoodsReceiptItem::whereHas('goodsReceipt', function ($q) use ($po) {
                    $q->where('purchase_order_id', $po->id)->where('status', 'completed');
                })->sum('qty_received');

                $po->update(['status' => $totalReceived >= $totalOrdered ? 'received' : 'approved']);
            }
        });

        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Penerimaan barang diselesaikan, stok telah diperbarui.']);
    }

    public function triggerDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Apakah Anda yakin?',
            'text' => 'Penerimaan barang ini akan dihapus.',
            'id' => $id,
        ]);
    }

    public function delete($id)
    {
        $gr = GoodsReceipt::findOrFail($id);

        if ($gr->status !== 'draft') {
            $this->dispatch('toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Hanya draft yang bisa dihapus.']);
            return;
        }

        $gr->delete();
        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Penerimaan barang berhasil dihapus.']);
    }

    public function resetForm()
    {
        $this->reset(['purchase_order_id', 'warehouse_id', 'notes', 'items', 'grId']);
        $this->received_date = now()->toDateString();
        $this->resetValidation();
    }

    public function render()
    {
        $goodsReceipts = GoodsReceipt::with(['purchaseOrder', 'warehouse'])
            ->when($this->search, fn ($q) => $q->where('gr_number', 'like', '%' . $this->search . '%'))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.pembelian.goods-receipt-manager', [
            'goodsReceipts' => $goodsReceipts,
            'purchaseOrders' => PurchaseOrder::whereIn('status', ['approved', 'received'])->orderByDesc('id')->get(),
        ])->layout('layouts.app');
    }
}
