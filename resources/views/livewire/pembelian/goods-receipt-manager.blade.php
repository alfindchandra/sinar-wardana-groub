<div>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-1">
                <span>Pembelian</span>
                <span>/</span>
                <span class="text-slate-800 dark:text-slate-200 font-medium">Penerimaan Barang</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Penerimaan Barang</h1>
        </div>
        <div>
            <!-- Button Tambah / Terima Barang -->
            <button 
                type="button" 
                wire:click="create" 
                class="flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/20"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Terima Barang</span>
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Cari No. GR...">
            </div>
            <select wire:model.live="filterStatus" class="w-full sm:w-48 rounded-lg border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">No. GR</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">No. PO</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Gudang</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Tgl Terima</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Status</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($goodsReceipts as $gr)
                        <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="p-4 font-medium text-primary-600 dark:text-primary-400">
                                <button type="button" wire:click="viewDetail({{ $gr->id }})" class="hover:underline">{{ $gr->gr_number }}</button>
                            </td>
                            <td class="p-4 text-slate-800 dark:text-slate-200">{{ $gr->purchaseOrder?->po_number ?? '-' }}</td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">{{ $gr->warehouse?->name ?? '-' }}</td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">{{ optional($gr->received_date)->format('d/m/Y') }}</td>
                            <td class="p-4">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300',
                                        'completed' => 'bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400',
                                        'cancelled' => 'bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400',
                                    ];
                                    $statusLabels = ['draft' => 'Draft', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$gr->status] ?? '' }}">{{ $statusLabels[$gr->status] ?? $gr->status }}</span>
                            </td>
                            <td class="p-4 text-right whitespace-nowrap">
                                @if ($gr->status === 'draft')
                                    <button type="button" wire:click="complete({{ $gr->id }})" wire:confirm="Konfirmasi penerimaan barang ini? Stok gudang akan diperbarui." class="p-2 text-slate-400 hover:text-success-600 transition-colors" title="Selesaikan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                    <button type="button" wire:click="triggerDelete({{ $gr->id }})" class="p-2 text-slate-400 hover:text-danger-600 transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @endif
                                <button type="button" wire:click="viewDetail({{ $gr->id }})" class="p-2 text-slate-400 hover:text-primary-600 transition-colors" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500 dark:text-slate-400">Tidak ada data penerimaan barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $goodsReceipts->links() }}
        </div>
    </div>

    <!-- Modal Form Tambah / Edit -->
    <div x-data="{ show: @entangle('showModal').live }" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="show = false"></div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-3xl z-10 border border-slate-200 dark:border-slate-700 flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Terima Barang dari PO</h3>
                <button type="button" @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form wire:submit="save" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Purchase Order <span class="text-danger-500">*</span></label>
                        <select wire:model.live="purchase_order_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Pilih PO --</option>
                            @foreach ($purchaseOrders as $po)
                                <option value="{{ $po->id }}">{{ $po->po_number }} - {{ $po->supplier?->name }}</option>
                            @endforeach
                        </select>
                        @error('purchase_order_id') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Terima <span class="text-danger-500">*</span></label>
                        <input wire:model="received_date" type="date" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                        @error('received_date') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if (!empty($items) && count($items) > 0)
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Item Penerimaan</label>
                        @error('items') <span class="text-xs text-danger-500 mt-1 block mb-2">{{ $message }}</span> @enderror
                        <div class="space-y-3">
                            @foreach ($items as $index => $item)
                                <div class="grid grid-cols-12 gap-2 items-start bg-slate-50 dark:bg-slate-900/50 p-3 rounded-lg">
                                    <div class="col-span-4 pt-2 text-sm text-slate-800 dark:text-slate-200 font-medium">
                                        {{ $item['product_name'] ?? '-' }}
                                        <div class="text-xs text-slate-500">Dipesan: {{ $item['qty_ordered'] ?? 0 }}</div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-xs text-slate-500">Qty Diterima</label>
                                        <input wire:model="items.{{ $index }}.qty_received" type="number" min="0" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-primary-500 focus:border-primary-500">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="text-xs text-slate-500">No. Batch</label>
                                        <input wire:model="items.{{ $index }}.batch_number" type="text" placeholder="Otomatis jika kosong" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-primary-500 focus:border-primary-500">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="text-xs text-slate-500">Expired</label>
                                        <input wire:model="items.{{ $index }}.expiry_date" type="date" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-primary-500 focus:border-primary-500">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan</label>
                    <textarea wire:model="notes" rows="2" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3">
                    <button type="button" @click="show = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-lg shadow-primary-600/20">Simpan sebagai Draft</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-data="{ show: @entangle('showDetailModal').live }" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="show = false"></div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-2xl z-10 border border-slate-200 dark:border-slate-700 flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Detail GR {{ $detailGr?->gr_number }}</h3>
                <button type="button" @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            @if ($detailGr)
                <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar text-sm">
                    <div class="grid grid-cols-2 gap-3">
                        <div><span class="text-slate-500">PO:</span> <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $detailGr->purchaseOrder?->po_number }}</span></div>
                        <div><span class="text-slate-500">Gudang:</span> <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $detailGr->warehouse?->name }}</span></div>
                        <div><span class="text-slate-500">Diterima oleh:</span> <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $detailGr->receiver?->name ?? '-' }}</span></div>
                        <div><span class="text-slate-500">Tanggal:</span> <span class="text-slate-800 dark:text-slate-200 font-medium">{{ optional($detailGr->received_date)->format('d/m/Y') }}</span></div>
                    </div>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700">
                                <th class="py-2 text-xs font-semibold text-slate-500">Produk</th>
                                <th class="py-2 text-xs font-semibold text-slate-500 text-right">Dipesan</th>
                                <th class="py-2 text-xs font-semibold text-slate-500 text-right">Diterima</th>
                                <th class="py-2 text-xs font-semibold text-slate-500">Batch</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailGr->items as $item)
                                <tr class="border-b border-slate-100 dark:border-slate-800">
                                    <td class="py-2 text-slate-800 dark:text-slate-200">{{ $item->product?->name }}</td>
                                    <td class="py-2 text-right text-slate-600 dark:text-slate-400">{{ $item->qty_ordered }}</td>
                                    <td class="py-2 text-right text-slate-800 dark:text-slate-200 font-medium">{{ $item->qty_received }}</td>
                                    <td class="py-2 text-slate-600 dark:text-slate-400">{{ $item->batch_number ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal:confirm', (event) => {
                if (confirm(event[0].title + '\n' + event[0].text)) {
                    @this.dispatch('deleteConfirm', { id: event[0].id });
                }
            });
        });
    </script>
</div>