<div>
    <!-- Header & Aksi -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400 mb-1">
                <span>Gudang</span>
                <span>/</span>
                <span class="text-slate-900 dark:text-slate-100 font-medium">Stock Opname</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Stock Opname</h1>
        </div>
        <div>
            <button type="button" wire:click="create" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl font-medium shadow-sm cursor-pointer">
                + Buat Opname
            </button>
        </div>
    </div>

    <!-- Filter & Pencarian Tabel -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <input wire:model.live="search" type="text" class="w-full pl-4 pr-4 py-2 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500" placeholder="Cari no. opname...">
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
    </div>

    <!-- Tabel Data -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 overflow-hidden">
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
                        <tr wire:key="opname-{{ $opname->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
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
                                    <span class="text-red-600 font-medium">{{ $totalSelisih }}</span>
                                @elseif($totalSelisih > 0)
                                    <span class="text-green-600 font-medium">+{{ $totalSelisih }}</span>
                                @else
                                    <span class="text-slate-600 dark:text-slate-400">0</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-lg text-sm font-medium
                                    {{ $opname->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                    {{ $opname->status === 'in_progress' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                    {{ $opname->status === 'draft' ? 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300' : '' }}
                                    {{ $opname->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $opname->status)) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <button type="button" wire:click="viewDetail({{ $opname->id }})" class="text-slate-400 hover:text-primary-600 cursor-pointer" title="Detail">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>

                                    @if($opname->status === 'draft')
                                        <button type="button" wire:click="edit({{ $opname->id }})" class="text-slate-400 hover:text-primary-600 cursor-pointer" title="Edit">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </button>
                                        <button type="button" wire:click="triggerDelete({{ $opname->id }})" class="text-slate-400 hover:text-red-600 cursor-pointer" title="Hapus">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    @endif

                                    @if(in_array($opname->status, ['draft', 'in_progress']))
                                        <button type="button" wire:click="approve({{ $opname->id }})" class="text-slate-400 hover:text-green-600 cursor-pointer" title="Setujui (Completed)">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
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

    {{-- MODAL FORM --}}
    @if($showModal)
    @php
        $productListJson = $products->map(fn($p) => [
            'id' => (string) $p->id,
            'name' => $p->name,
            'code' => $p->sku ?? $p->barcode ?? '',
        ])->values()->toJson();
    @endphp

    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden z-10 flex flex-col max-h-[90vh]">
                
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                        {{ $isEdit ? 'Edit Stock Opname' : 'Buat Stock Opname' }}
                    </h3>
                    <button type="button" wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Gudang</label>
                            <select wire:model.live="warehouse_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Pilih Gudang</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                            @error('warehouse_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal</label>
                            <input type="date" wire:model="opname_date" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            @error('opname_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan</label>
                            <textarea wire:model="notes" rows="2" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-slate-200 dark:border-slate-700 pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-md font-bold text-slate-800 dark:text-slate-200">Daftar Item Opname</h4>
                            <button wire:click="addItem" type="button" class="bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-blue-100 cursor-pointer">
                                + Tambah Item
                            </button>
                        </div>
                        @error('items') <div class="text-red-500 text-sm mb-4">{{ $message }}</div> @enderror

                        <div class="space-y-4">
                            @foreach($items as $index => $item)
                                <div wire:key="opname-item-{{ $index }}" 
                                     x-data="{
                                         keyword: '',
                                         selectedId: @entangle('items.' . $index . '.product_id'),
                                         allProducts: {{ $productListJson }},
                                         get filteredList() {
                                             if (!this.keyword.trim()) return [];
                                             const kw = this.keyword.toLowerCase();
                                             return this.allProducts.filter(p => 
                                                 p.name.toLowerCase().includes(kw) || 
                                                 (p.code && p.code.toLowerCase().includes(kw))
                                             ).slice(0, 5);
                                         },
                                         get selectedItem() {
                                             return this.allProducts.find(p => String(p.id) === String(this.selectedId));
                                         }
                                     }"
                                     class="p-4 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                                    
                                    <div class="flex justify-between items-start mb-3">
                                        <h5 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Item #{{ $index + 1 }}</h5>
                                        @if(count($items) > 1)
                                            <button wire:click="removeItem({{ $index }})" type="button" class="text-red-500 hover:text-red-700 cursor-pointer">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-start">
                                        <!-- Area Pencarian Produk Langsung (5 Cols) -->
                                        <div class="md:col-span-5">
                                            <label class="block text-xs font-medium text-slate-500 mb-1">Produk</label>
                                            
                                            <!-- Tampilan Produk yang Terpilih -->
                                            <template x-if="selectedId && selectedItem">
                                                <div class="flex items-center justify-between p-2 bg-white dark:bg-slate-900 rounded-lg border border-primary-500/40">
                                                    <div class="flex items-center gap-2 truncate">
                                                        <span class="w-2 h-2 rounded-full bg-primary-500 shrink-0"></span>
                                                        <span class="text-xs font-medium text-slate-900 dark:text-slate-100 truncate" x-text="selectedItem.name"></span>
                                                        <span x-show="selectedItem.code" class="text-[10px] text-slate-400" x-text="'(' + selectedItem.code + ')'"></span>
                                                    </div>
                                                    <button type="button" 
                                                            @click="$wire.clearProduct({{ $index }}); keyword = '';" 
                                                            class="text-xs text-red-500 hover:text-red-700 font-semibold ml-2 shrink-0">
                                                        Ganti
                                                    </button>
                                                </div>
                                            </template>

                                            <!-- Input Ketik Langsung Saat Belum Dipilih -->
                                            <template x-if="!selectedId">
                                                <div>
                                                    <div class="relative">
                                                        <input type="text" 
                                                               x-model="keyword" 
                                                               placeholder="Ketik nama / kode produk..." 
                                                               class="w-full pl-8 pr-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                        </svg>
                                                    </div>

                                                    <!-- Hasil Pencarian Langsung -->
                                                    <div x-show="keyword.trim().length > 0" class="mt-2 space-y-1">
                                                        <template x-for="p in filteredList" :key="p.id">
                                                            <div @click="$wire.selectProduct({{ $index }}, p.id); keyword = '';" 
                                                                 class="flex items-center justify-between p-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-primary-500 hover:bg-primary-50/50 dark:hover:bg-primary-950/20 cursor-pointer transition-colors">
                                                                <div>
                                                                    <div class="text-xs font-medium text-slate-800 dark:text-slate-200" x-text="p.name"></div>
                                                                    <div class="text-[10px] text-slate-400" x-text="p.code"></div>
                                                                </div>
                                                                <span class="text-xs text-primary-600 dark:text-primary-400 font-semibold">+ Pilih</span>
                                                            </div>
                                                        </template>
                                                        <div x-show="filteredList.length === 0" class="p-2 text-xs text-center text-slate-400 bg-white dark:bg-slate-900 rounded-lg border border-dashed border-slate-200 dark:border-slate-700">
                                                            Produk tidak ditemukan.
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            @error('items.'.$index.'.product_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Stok Sistem (2 Cols) Readonly/Auto -->
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-medium text-slate-500 mb-1">Stok Sistem</label>
                                            <input type="number" wire:model="items.{{ $index }}.system_qty" readonly class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 py-2 cursor-not-allowed font-medium">
                                            @error('items.'.$index.'.system_qty') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Stok Fisik (2 Cols) -->
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-medium text-slate-500 mb-1">Stok Fisik</label>
                                            <input type="number" wire:model.live="items.{{ $index }}.actual_qty" min="0" class="w-full text-xs rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 py-2 focus:ring-primary-500 focus:border-primary-500 font-medium">
                                            @error('items.'.$index.'.actual_qty') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Selisih (1 Col) -->
                                        <div class="md:col-span-1 text-center">
                                            <label class="block text-xs font-medium text-slate-500 mb-1">Selisih</label>
                                            @php
                                                $sys = (int)($item['system_qty'] ?? 0);
                                                $act = (int)($item['actual_qty'] ?? 0);
                                                $diff = $act - $sys;
                                            @endphp
                                            <div class="py-2 text-xs font-bold {{ $diff < 0 ? 'text-red-600' : ($diff > 0 ? 'text-green-600' : 'text-slate-500') }}">
                                                {{ $diff > 0 ? '+' : '' }}{{ $diff }}
                                            </div>
                                        </div>

                                        <!-- Catatan Item (2 Cols) -->
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-medium text-slate-500 mb-1">Catatan</label>
                                            <input type="text" wire:model="items.{{ $index }}.notes" class="w-full text-xs rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 py-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Keterangan">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-end space-x-3">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:text-slate-800 font-medium cursor-pointer">Batal</button>
                    <button type="button" wire:click="save" class="bg-primary-600 text-white px-4 py-2 rounded-xl hover:bg-primary-700 font-medium shadow-sm cursor-pointer">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL DETAIL --}}
    @if($showDetailModal && $opnameDetail)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showDetailModal', false)"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden z-10 flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Detail Stock Opname</h3>
                    <button type="button" wire:click="$set('showDetailModal', false)" class="text-slate-400 hover:text-slate-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto flex-1">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                        <div>
                            <div class="text-xs text-slate-500">No. Opname</div>
                            <div class="font-semibold text-slate-900 dark:text-white">{{ $opnameDetail->opname_number }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Gudang</div>
                            <div class="font-semibold text-slate-900 dark:text-white">{{ $opnameDetail->warehouse->name ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Tanggal</div>
                            <div class="font-semibold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($opnameDetail->opname_date)->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Status</div>
                            <div class="font-semibold text-slate-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $opnameDetail->status)) }}</div>
                        </div>
                        @if($opnameDetail->notes)
                            <div class="col-span-2 md:col-span-4">
                                <div class="text-xs text-slate-500">Catatan</div>
                                <div class="text-sm text-slate-700 dark:text-slate-300 mt-1">{{ $opnameDetail->notes }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-lg">
                        <table class="w-full text-left border-collapse">
                            <thead class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                                <tr>
                                    <th class="p-3 text-xs font-semibold text-slate-600 dark:text-slate-300">Produk</th>
                                    <th class="p-3 text-xs font-semibold text-slate-600 dark:text-slate-300 text-right">Sistem</th>
                                    <th class="p-3 text-xs font-semibold text-slate-600 dark:text-slate-300 text-right">Fisik</th>
                                    <th class="p-3 text-xs font-semibold text-slate-600 dark:text-slate-300 text-right">Selisih</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($opnameDetail->items as $item)
                                    <tr>
                                        <td class="p-3 text-sm text-slate-800 dark:text-slate-200">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                                        <td class="p-3 text-sm text-slate-600 dark:text-slate-400 text-right">{{ $item->system_qty }}</td>
                                        <td class="p-3 text-sm text-slate-600 dark:text-slate-400 text-right">{{ $item->actual_qty }}</td>
                                        <td class="p-3 text-sm font-medium text-right">
                                            @if($item->difference < 0)
                                                <span class="text-red-600">{{ $item->difference }}</span>
                                            @elseif($item->difference > 0)
                                                <span class="text-green-600">+{{ $item->difference }}</span>
                                            @else
                                                <span class="text-slate-500">0</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-end">
                    <button type="button" wire:click="$set('showDetailModal', false)" class="bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white px-4 py-2 rounded-xl cursor-pointer">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- SweetAlert Scripts --}}
    @script
    <script>
        $wire.on('swal:confirm', (data) => {
            const eventData = Array.isArray(data) ? data[0] : data;
            Swal.fire({
                title: eventData.title,
                text: eventData.text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('deleteConfirm', { id: eventData.id });
                }
            });
        });

        $wire.on('toast', (data) => {
            const eventData = Array.isArray(data) ? data[0] : data;
            Swal.fire({
                icon: eventData.type,
                title: eventData.title,
                text: eventData.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        });
    </script>
    @endscript
</div>