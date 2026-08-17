<div>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400 mb-2">
            <span>Gudang</span>
            <span>/</span>
            <span class="text-slate-900 dark:text-slate-100 font-medium">Stock Opname</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Stock Opname</h1>
    </x-slot>

    <x-slot name="actions">
        <button wire:click="create" class="bg-primary-600 text-white px-4 py-2 rounded-xl hover:bg-primary-700 font-medium shadow-sm">
            + Buat Opname
        </button>
    </x-slot>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input wire:model.live="search" type="text" class="w-full pl-10 pr-4 py-2 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500" placeholder="Cari no. opname...">
            </div>
            
            <div class="sm:w-48">
                <select wire:model.live="warehouseFilter" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Gudang</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="sm:w-48">
                <select wire:model.live="statusFilter" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">No. Opname</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Gudang</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Tanggal</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Jumlah Item</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Total Selisih</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Status</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($opnames as $opname)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="p-4 text-slate-800 dark:text-slate-200">
                                <span class="bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400 px-2.5 py-1 rounded-lg text-sm font-medium">
                                    {{ $opname->opname_number }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">{{ $opname->warehouse->name ?? '-' }}</td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">{{ \Carbon\Carbon::parse($opname->opname_date)->format('d/m/Y') }}</td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">{{ $opname->items->count() }}</td>
                            <td class="p-4">
                                @php
                                    $totalSelisih = $opname->items->sum('difference');
                                @endphp
                                @if($totalSelisih < 0)
                                    <span class="text-danger-600 dark:text-danger-400 font-medium">{{ $totalSelisih }}</span>
                                @elseif($totalSelisih > 0)
                                    <span class="text-success-600 dark:text-success-400 font-medium">+{{ $totalSelisih }}</span>
                                @else
                                    <span class="text-slate-600 dark:text-slate-400">0</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($opname->status === 'draft')
                                    <span class="bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300 px-2.5 py-1 rounded-lg text-sm font-medium">Draft</span>
                                @elseif($opname->status === 'in_progress')
                                    <span class="bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 px-2.5 py-1 rounded-lg text-sm font-medium">In Progress</span>
                                @elseif($opname->status === 'completed')
                                    <span class="bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400 px-2.5 py-1 rounded-lg text-sm font-medium">Completed</span>
                                @else
                                    <span class="bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400 px-2.5 py-1 rounded-lg text-sm font-medium">Cancelled</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <button wire:click="viewDetail({{ $opname->id }})" class="text-slate-400 hover:text-primary-600" title="Detail">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    @if($opname->status === 'draft')
                                        <button wire:click="edit({{ $opname->id }})" class="text-slate-400 hover:text-primary-600" title="Edit">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="triggerDelete({{ $opname->id }})" class="text-slate-400 hover:text-danger-600" title="Hapus">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif

                                    @if(in_array($opname->status, ['draft', 'in_progress']))
                                        <button wire:click="approve({{ $opname->id }})" class="text-slate-400 hover:text-success-600" title="Setujui (Completed)">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-slate-500 dark:text-slate-400">Tidak ada data stock opname.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $opnames->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <div x-data="{ show: @entangle('showModal') }" 
         x-show="show" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0"
         style="display: none;">
        
        <div x-show="show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden max-h-[90vh] flex flex-col">
            
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                    {{ $isEdit ? 'Edit Stock Opname' : 'Buat Stock Opname' }}
                </h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Gudang</label>
                        <select wire:model="warehouse_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Pilih Gudang</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        @error('warehouse_id') <span class="text-danger-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal</label>
                        <input type="date" wire:model="opname_date" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                        @error('opname_date') <span class="text-danger-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan</label>
                        <textarea wire:model="notes" rows="2" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500"></textarea>
                    </div>
                </div>

                <div class="mt-6 border-t border-slate-200 dark:border-slate-700 pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-bold text-slate-800 dark:text-slate-200">Daftar Item Opname</h4>
                        <button wire:click="addItem" type="button" class="bg-primary-50 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-primary-100 dark:hover:bg-primary-900/50">
                            + Tambah Item
                        </button>
                    </div>
                    @error('items') <div class="text-danger-500 text-sm mb-4">{{ $message }}</div> @enderror

                    <div class="space-y-4">
                        @foreach($items as $index => $item)
                            <div class="p-4 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                                <div class="flex justify-between items-start mb-4">
                                    <h5 class="text-sm font-medium text-slate-700 dark:text-slate-300">Item #{{ $index + 1 }}</h5>
                                    @if(count($items) > 1)
                                        <button wire:click="removeItem({{ $index }})" type="button" class="text-danger-500 hover:text-danger-700">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="sm:col-span-2 md:col-span-1">
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Produk</label>
                                        <select wire:model="items.{{ $index }}.product_id" class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                            <option value="">Pilih Produk</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('items.'.$index.'.product_id') <span class="text-danger-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Stok Sistem</label>
                                        <input type="number" wire:model.live="items.{{ $index }}.system_qty" class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                        @error('items.'.$index.'.system_qty') <span class="text-danger-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Stok Fisik</label>
                                        <input type="number" wire:model.live="items.{{ $index }}.actual_qty" class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                        @error('items.'.$index.'.actual_qty') <span class="text-danger-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Selisih</label>
                                        @php
                                            $sys = is_numeric($item['system_qty']) ? $item['system_qty'] : 0;
                                            $act = is_numeric($item['actual_qty']) ? $item['actual_qty'] : 0;
                                            $diff = $act - $sys;
                                        @endphp
                                        <div class="py-2 text-sm font-semibold {{ $diff < 0 ? 'text-danger-600 dark:text-danger-400' : ($diff > 0 ? 'text-success-600 dark:text-success-400' : 'text-slate-700 dark:text-slate-300') }}">
                                            {{ $diff > 0 ? '+' : '' }}{{ $diff }}
                                        </div>
                                    </div>
                                    <div class="sm:col-span-2 md:col-span-4">
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Catatan Item</label>
                                        <input type="text" wire:model="items.{{ $index }}.notes" class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500" placeholder="Keterangan opsional">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-end space-x-3">
                <button wire:click="$set('showModal', false)" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white font-medium">Batal</button>
                <button wire:click="save" class="bg-primary-600 text-white px-4 py-2 rounded-xl hover:bg-primary-700 font-medium shadow-sm">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-data="{ showDetail: @entangle('showDetailModal') }" 
         x-show="showDetail" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0"
         style="display: none;">
        
        <div x-show="showDetail" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

        <div x-show="showDetail"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden max-h-[90vh] flex flex-col">
            
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                    Detail Stock Opname
                </h3>
                <button wire:click="$set('showDetailModal', false)" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if($opnameDetail)
            <div class="p-6 overflow-y-auto flex-1">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                    <div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">No. Opname</div>
                        <div class="font-semibold text-slate-900 dark:text-white">{{ $opnameDetail->opname_number }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Gudang</div>
                        <div class="font-semibold text-slate-900 dark:text-white">{{ $opnameDetail->warehouse->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Tanggal</div>
                        <div class="font-semibold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($opnameDetail->opname_date)->format('d M Y') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Status</div>
                        <div class="mt-1">
                            @if($opnameDetail->status === 'draft')
                                <span class="bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300 px-2 py-0.5 rounded text-xs font-medium">Draft</span>
                            @elseif($opnameDetail->status === 'in_progress')
                                <span class="bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 px-2 py-0.5 rounded text-xs font-medium">In Progress</span>
                            @elseif($opnameDetail->status === 'completed')
                                <span class="bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400 px-2 py-0.5 rounded text-xs font-medium">Completed</span>
                            @else
                                <span class="bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400 px-2 py-0.5 rounded text-xs font-medium">Cancelled</span>
                            @endif
                        </div>
                    </div>
                    @if($opnameDetail->notes)
                    <div class="col-span-2 md:col-span-4">
                        <div class="text-xs text-slate-500 dark:text-slate-400">Catatan</div>
                        <div class="text-sm text-slate-700 dark:text-slate-300 mt-1">{{ $opnameDetail->notes }}</div>
                    </div>
                    @endif
                </div>

                <h4 class="font-bold text-slate-900 dark:text-white mb-3">Item Opname</h4>
                <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-lg">
                    <table class="w-full text-left border-collapse">
                        <thead class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                            <tr>
                                <th class="p-3 text-xs font-semibold text-slate-600 dark:text-slate-300">Produk</th>
                                <th class="p-3 text-xs font-semibold text-slate-600 dark:text-slate-300 text-right">Sistem</th>
                                <th class="p-3 text-xs font-semibold text-slate-600 dark:text-slate-300 text-right">Fisik</th>
                                <th class="p-3 text-xs font-semibold text-slate-600 dark:text-slate-300 text-right">Selisih</th>
                                <th class="p-3 text-xs font-semibold text-slate-600 dark:text-slate-300">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($opnameDetail->items as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="p-3 text-sm text-slate-800 dark:text-slate-200">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                                    <td class="p-3 text-sm text-slate-600 dark:text-slate-400 text-right">{{ $item->system_qty }}</td>
                                    <td class="p-3 text-sm text-slate-600 dark:text-slate-400 text-right">{{ $item->actual_qty }}</td>
                                    <td class="p-3 text-sm font-medium text-right">
                                        @if($item->difference < 0)
                                            <span class="text-danger-600 dark:text-danger-400">{{ $item->difference }}</span>
                                        @elseif($item->difference > 0)
                                            <span class="text-success-600 dark:text-success-400">+{{ $item->difference }}</span>
                                        @else
                                            <span class="text-slate-500 dark:text-slate-400">0</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-sm text-slate-500 dark:text-slate-400">{{ $item->notes ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-end">
                <button wire:click="$set('showDetailModal', false)" class="bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white px-4 py-2 rounded-xl hover:bg-slate-300 dark:hover:bg-slate-600 font-medium">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal:confirm', (data) => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteConfirm', { id: data[0].id });
                    }
                });
            });

            Livewire.on('toast', (data) => {
                Swal.fire({
                    icon: data[0].type,
                    title: data[0].title,
                    text: data[0].message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        });
    </script>
</div>
