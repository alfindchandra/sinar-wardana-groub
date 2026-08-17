<div>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-1">
                <span>Pembelian</span>
                <span>/</span>
                <span class="text-slate-800 dark:text-slate-200 font-medium">Retur Supplier</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Retur Supplier</h1>
        </div>
    </x-slot>

    <x-slot name="actions">
        <button wire:click="create" class="flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Retur
        </button>
    </x-slot>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Cari No. Retur...">
            </div>
            <select wire:model.live="filterSupplier" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterStatus" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="approved">Disetujui</option>
                <option value="sent">Dikirim</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">No. Retur</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">No. PO</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Supplier</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Gudang</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Tanggal</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300 text-right">Subtotal</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Status</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($supplierReturns as $return)
                        <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="p-4 font-medium text-primary-600 dark:text-primary-400">
                                <button wire:click="viewDetail({{ $return->id }})" class="hover:underline">{{ $return->return_number }}</button>
                            </td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">{{ $return->purchaseOrder?->po_number ?? '-' }}</td>
                            <td class="p-4 text-slate-800 dark:text-slate-200">{{ $return->supplier?->name ?? '-' }}</td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">{{ $return->warehouse?->name ?? '-' }}</td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">{{ optional($return->return_date)->format('d/m/Y') }}</td>
                            <td class="p-4 text-right text-slate-800 dark:text-slate-200 font-medium">Rp {{ number_format($return->subtotal, 0, ',', '.') }}</td>
                            <td class="p-4">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300',
                                        'approved' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'sent' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                        'completed' => 'bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400',
                                        'cancelled' => 'bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400',
                                    ];
                                    $statusLabels = ['draft' => 'Draft', 'approved' => 'Disetujui', 'sent' => 'Dikirim', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$return->status] ?? '' }}">{{ $statusLabels[$return->status] ?? $return->status }}</span>
                            </td>
                            <td class="p-4 text-right whitespace-nowrap">
                                @if ($return->status === 'draft')
                                    <button wire:click="approve({{ $return->id }})" class="p-2 text-slate-400 hover:text-success-600 transition-colors" title="Setujui">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                    <button wire:click="edit({{ $return->id }})" class="p-2 text-slate-400 hover:text-primary-600 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button wire:click="triggerDelete({{ $return->id }})" class="p-2 text-slate-400 hover:text-danger-600 transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @elseif ($return->status === 'approved')
                                    <button wire:click="complete({{ $return->id }})" wire:confirm="Selesaikan retur ini? Stok gudang akan dikurangi." class="p-2 text-slate-400 hover:text-success-600 transition-colors" title="Selesaikan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </button>
                                    <button wire:click="cancel({{ $return->id }})" class="p-2 text-slate-400 hover:text-danger-600 transition-colors" title="Batalkan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                @endif
                                <button wire:click="viewDetail({{ $return->id }})" class="p-2 text-slate-400 hover:text-primary-600 transition-colors" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-500 dark:text-slate-400">Tidak ada data retur supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $supplierReturns->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <div x-data="{ show: @entangle('showModal') }" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="show = false"></div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-4xl z-10 border border-slate-200 dark:border-slate-700 flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $isEdit ? 'Edit Retur Supplier' : 'Buat Retur Supplier' }}</h3>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <form wire:submit.prevent="save" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">PO Terkait (opsional)</label>
                        <select wire:model="purchase_order_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Tanpa PO --</option>
                            @foreach ($purchaseOrders as $po)
                                <option value="{{ $po->id }}">{{ $po->po_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Supplier <span class="text-danger-500">*</span></label>
                        <select wire:model="supplier_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Gudang Asal <span class="text-danger-500">*</span></label>
                        <select wire:model="warehouse_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Pilih Gudang --</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        @error('warehouse_id') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Retur <span class="text-danger-500">*</span></label>
                        <input wire:model="return_date" type="date" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                        @error('return_date') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alasan Umum</label>
                    <input wire:model="reason" type="text" placeholder="cth. Barang rusak / tidak sesuai pesanan" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Item Retur <span class="text-danger-500">*</span></label>
                        <button type="button" wire:click="addItem" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Item
                        </button>
                    </div>
                    @error('items') <span class="text-xs text-danger-500 mt-1 block mb-2">{{ $message }}</span> @enderror

                    <div class="space-y-3">
                        @foreach ($items as $index => $item)
                            <div class="grid grid-cols-12 gap-2 items-start bg-slate-50 dark:bg-slate-900/50 p-3 rounded-lg">
                                <div class="col-span-3">
                                    <select wire:model="items.{{ $index }}.product_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-primary-500 focus:border-primary-500">
                                        <option value="">-- Produk --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error("items.$index.product_id") <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-2">
                                    <input wire:model="items.{{ $index }}.qty" type="number" min="1" placeholder="Qty" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-primary-500 focus:border-primary-500">
                                    @error("items.$index.qty") <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-2">
                                    <input wire:model="items.{{ $index }}.price" type="number" min="0" step="0.01" placeholder="Harga" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-primary-500 focus:border-primary-500">
                                    @error("items.$index.price") <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-3">
                                    <input wire:model="items.{{ $index }}.reason" type="text" placeholder="Alasan item ini" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div class="col-span-1 pt-2 text-xs text-slate-600 dark:text-slate-400 text-right whitespace-nowrap">
                                    {{ number_format($this->getItemSubtotal($index), 0, ',', '.') }}
                                </div>
                                <div class="col-span-1 text-right">
                                    <button type="button" wire:click="removeItem({{ $index }})" class="p-1.5 text-slate-400 hover:text-danger-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end mt-3 text-sm font-semibold text-slate-800 dark:text-slate-100">
                        Subtotal: Rp {{ number_format($this->subtotal, 0, ',', '.') }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan</label>
                    <textarea wire:model="notes" rows="2" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>
            </form>

            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 bg-slate-50 dark:bg-slate-800/50 rounded-b-xl">
                <button type="button" @click="show = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">Batal</button>
                <button wire:click="save" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-lg shadow-primary-600/20">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-data="{ show: @entangle('showDetailModal') }" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="show = false"></div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-2xl z-10 border border-slate-200 dark:border-slate-700 flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Detail Retur {{ $detailReturn?->return_number }}</h3>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            @if ($detailReturn)
                <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar text-sm">
                    <div class="grid grid-cols-2 gap-3">
                        <div><span class="text-slate-500">Supplier:</span> <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $detailReturn->supplier?->name }}</span></div>
                        <div><span class="text-slate-500">Gudang:</span> <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $detailReturn->warehouse?->name }}</span></div>
                        <div><span class="text-slate-500">PO Terkait:</span> <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $detailReturn->purchaseOrder?->po_number ?? '-' }}</span></div>
                        <div><span class="text-slate-500">Tanggal:</span> <span class="text-slate-800 dark:text-slate-200 font-medium">{{ optional($detailReturn->return_date)->format('d/m/Y') }}</span></div>
                    </div>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700">
                                <th class="py-2 text-xs font-semibold text-slate-500">Produk</th>
                                <th class="py-2 text-xs font-semibold text-slate-500 text-right">Qty</th>
                                <th class="py-2 text-xs font-semibold text-slate-500 text-right">Harga</th>
                                <th class="py-2 text-xs font-semibold text-slate-500 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailReturn->items as $item)
                                <tr class="border-b border-slate-100 dark:border-slate-800">
                                    <td class="py-2 text-slate-800 dark:text-slate-200">{{ $item->product?->name }}</td>
                                    <td class="py-2 text-right text-slate-600 dark:text-slate-400">{{ $item->qty }}</td>
                                    <td class="py-2 text-right text-slate-600 dark:text-slate-400">{{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="py-2 text-right text-slate-800 dark:text-slate-200 font-medium">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="flex justify-end font-semibold text-slate-800 dark:text-slate-100">
                        Subtotal: Rp {{ number_format($detailReturn->subtotal, 0, ',', '.') }}
                    </div>
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
