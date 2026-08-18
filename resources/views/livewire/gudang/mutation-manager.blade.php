<div>
    <!-- Header & Tombol Aksi -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 mb-1">
                <span>Gudang</span>
                <span>/</span>
                <span class="font-medium text-slate-900 dark:text-slate-100">Mutasi</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Mutasi Stok</h1>
        </div>
        <div>
            <button type="button" wire:click="create" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl transition-colors font-medium cursor-pointer shadow-sm">
                + Tambah Mutasi
            </button>
        </div>
    </div>

    <!-- Filter & Pencarian Tabel -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6 flex flex-col md:flex-row gap-4">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input wire:model.live="search" type="text" class="w-full pl-10 pr-4 py-2 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500" placeholder="Cari no. mutasi...">
        </div>
        <div class="w-full md:w-64">
            <select wire:model.live="statusFilter" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="approved">Approved</option>
                <option value="in_transit">In Transit</option>
                <option value="received">Received</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    <!-- Tabel Data Mutasi -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">No. Mutasi</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Dari Gudang</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Ke Gudang</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Tanggal</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Jumlah Item</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Status</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($mutations as $mutation)
                    <tr wire:key="mutation-row-{{ $mutation->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="p-4">
                            <span class="font-medium text-primary-600 dark:text-primary-400">{{ $mutation->mutation_number }}</span>
                        </td>
                        <td class="p-4 text-slate-800 dark:text-slate-200">{{ $mutation->fromWarehouse->name ?? '-' }}</td>
                        <td class="p-4 text-slate-800 dark:text-slate-200">{{ $mutation->toWarehouse->name ?? '-' }}</td>
                        <td class="p-4 text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($mutation->mutation_date)->format('d M Y') }}</td>
                        <td class="p-4 text-slate-800 dark:text-slate-200">{{ $mutation->items->count() }} item</td>
                        <td class="p-4">
                            @if($mutation->status === 'draft')
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-800 dark:bg-slate-900/30 dark:text-slate-400">Draft</span>
                            @elseif($mutation->status === 'approved')
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400">Approved</span>
                            @elseif($mutation->status === 'in_transit')
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">In Transit</span>
                            @elseif($mutation->status === 'received')
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400">Received</span>
                            @elseif($mutation->status === 'cancelled')
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400">Cancelled</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="viewDetail({{ $mutation->id }})" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors cursor-pointer" title="Lihat Detail">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>

                                @if($mutation->status === 'draft')
                                    <button type="button" wire:click="approve({{ $mutation->id }})" class="p-1.5 text-success-500 hover:text-success-600 transition-colors cursor-pointer" title="Setujui">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </button>

                                    <button type="button" wire:click="edit({{ $mutation->id }})" class="p-1.5 text-slate-400 hover:text-primary-600 transition-colors cursor-pointer" title="Edit">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    
                                    <button type="button" wire:click="triggerDelete({{ $mutation->id }})" class="p-1.5 text-slate-400 hover:text-danger-600 transition-colors cursor-pointer" title="Hapus">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500 dark:text-slate-400">
                            Tidak ada data mutasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $mutations->links() }}
        </div>
    </div>

    {{-- MODAL FORM MUTASI (Pencarian Langsung) --}}
    @if($showModal)
    @php
        $productListJson = $products->map(fn($p) => [
            'id' => (string) $p->id,
            'name' => $p->name,
            'code' => $p->code ?? $p->sku ?? ''
        ])->values()->toJson();
    @endphp

    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-3xl bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 z-10 flex flex-col max-h-[90vh]">
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 rounded-t-2xl">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">
                        {{ $isEdit ? 'Edit Mutasi' : 'Tambah Mutasi' }}
                    </h3>
                    <button type="button" wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form wire:submit="save" class="p-6 overflow-y-auto flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Gudang Asal</label>
                            <select wire:model="from_warehouse_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                @endforeach
                            </select>
                            @error('from_warehouse_id') <span class="text-sm text-danger-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Gudang Tujuan</label>
                            <select wire:model="to_warehouse_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                @endforeach
                            </select>
                            @error('to_warehouse_id') <span class="text-sm text-danger-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Mutasi</label>
                            <input type="date" wire:model="mutation_date" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            @error('mutation_date') <span class="text-sm text-danger-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan</label>
                            <textarea wire:model="notes" rows="2" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500"></textarea>
                            @error('notes') <span class="text-sm text-danger-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-4 flex items-center justify-between border-t border-slate-200 dark:border-slate-700 pt-6">
                        <h4 class="text-lg font-medium text-slate-900 dark:text-slate-100">Daftar Item</h4>
                        <button type="button" wire:click="addItem" class="px-3 py-1.5 text-sm bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors cursor-pointer">
                            + Tambah Item
                        </button>
                    </div>

                    @error('items') <div class="text-sm text-danger-500 mb-2">{{ $message }}</div> @enderror

                    <!-- PENCARIAN PRODUK LANGSUNG TANPA DROPDOWN -->
                    <div class="space-y-4">
                        @foreach($items as $index => $item)
                            <div wire:key="mutation-item-{{ $index }}" 
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
                                         ).slice(0, 6);
                                     },
                                     get selectedItem() {
                                         return this.allProducts.find(p => String(p.id) === String(this.selectedId));
                                     },
                                     selectProduct(p) {
                                         this.selectedId = p.id;
                                         this.keyword = '';
                                     },
                                     clearSelection() {
                                         this.selectedId = '';
                                         this.keyword = '';
                                     }
                                 }"
                                 class="p-4 bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-slate-200 dark:border-slate-700/60">
                                
                                <div class="flex items-start gap-3">
                                    <!-- Area Pencarian / Nama Terpilih -->
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">
                                            Item #{{ $index + 1 }} - Produk
                                        </label>

                                        <!-- Saat Produk Sudah Dipilih -->
                                        <template x-if="selectedId && selectedItem">
                                            <div class="flex items-center justify-between p-2.5 bg-white dark:bg-slate-800 rounded-lg border border-primary-500/40 dark:border-primary-500/30">
                                                <div class="flex items-center gap-2 overflow-hidden">
                                                    <span class="w-2 h-2 rounded-full bg-primary-500 shrink-0"></span>
                                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate" x-text="selectedItem.name"></span>
                                                    <span x-show="selectedItem.code" class="text-xs text-slate-400" x-text="'(' + selectedItem.code + ')'"></span>
                                                </div>
                                                <button type="button" @click="clearSelection()" class="text-xs text-red-500 hover:text-red-700 font-medium px-2 py-0.5 rounded hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors">
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
                                                           placeholder="Ketik langsung nama atau kode produk..." 
                                                           class="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                </div>

                                                <!-- Hasil Pencarian Langsung -->
                                                <div x-show="keyword.trim().length > 0" class="mt-2 space-y-1.5">
                                                    <template x-for="p in filteredList" :key="p.id">
                                                        <div @click="selectProduct(p)" 
                                                             class="flex items-center justify-between p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-primary-500 hover:bg-primary-50/50 dark:hover:bg-primary-950/20 cursor-pointer transition-colors">
                                                            <div class="text-sm font-medium text-slate-800 dark:text-slate-200" x-text="p.name"></div>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-xs text-slate-400" x-text="p.code"></span>
                                                                <span class="text-xs text-primary-600 dark:text-primary-400 font-semibold">+ Pilih</span>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div x-show="filteredList.length === 0" class="p-2 text-xs text-center text-slate-400 bg-white dark:bg-slate-800 rounded-lg border border-dashed border-slate-200 dark:border-slate-700">
                                                        Produk tidak ditemukan dengan kata kunci tersebut.
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        @error('items.'.$index.'.product_id') <span class="text-xs text-danger-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Input Qty -->
                                    <div class="w-28 sm:w-32">
                                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Jumlah</label>
                                        <input type="number" wire:model="items.{{ $index }}.qty" min="1" class="w-full px-3 py-2 text-sm rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500" placeholder="Qty">
                                        @error('items.'.$index.'.qty') <span class="text-xs text-danger-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Hapus Item -->
                                    <div class="pt-6">
                                        <button type="button" wire:click="removeItem({{ $index }})" class="p-2 text-slate-400 hover:text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-900/30 rounded-lg transition-colors cursor-pointer" title="Hapus Baris">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-slate-600 dark:text-slate-300 font-medium hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors font-medium cursor-pointer shadow-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL DETAIL MUTASI --}}
    @if($showDetailModal && $detailMutation)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showDetailModal', false)"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 z-10 flex flex-col max-h-[90vh]">
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 rounded-t-2xl">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Detail Mutasi</h3>
                    <button type="button" wire:click="$set('showDetailModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="p-6 overflow-y-auto flex-1">
                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div>
                            <p class="text-slate-500 dark:text-slate-400 mb-1">No. Mutasi</p>
                            <p class="font-medium text-slate-900 dark:text-slate-100">{{ $detailMutation->mutation_number }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400 mb-1">Tanggal</p>
                            <p class="font-medium text-slate-900 dark:text-slate-100">{{ \Carbon\Carbon::parse($detailMutation->mutation_date)->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400 mb-1">Dari Gudang</p>
                            <p class="font-medium text-slate-900 dark:text-slate-100">{{ $detailMutation->fromWarehouse->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400 mb-1">Ke Gudang</p>
                            <p class="font-medium text-slate-900 dark:text-slate-100">{{ $detailMutation->toWarehouse->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400 mb-1">Status</p>
                            <div class="mt-1">
                                @if($detailMutation->status === 'draft')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-800 dark:bg-slate-900/30 dark:text-slate-400">Draft</span>
                                @elseif($detailMutation->status === 'approved')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400">Approved</span>
                                @elseif($detailMutation->status === 'in_transit')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">In Transit</span>
                                @elseif($detailMutation->status === 'received')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400">Received</span>
                                @elseif($detailMutation->status === 'cancelled')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400">Cancelled</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400 mb-1">Dibuat Oleh</p>
                            <p class="font-medium text-slate-900 dark:text-slate-100">{{ $detailMutation->creator->name ?? '-' }}</p>
                        </div>
                        @if($detailMutation->notes)
                            <div class="col-span-2">
                                <p class="text-slate-500 dark:text-slate-400 mb-1">Catatan</p>
                                <p class="font-medium text-slate-900 dark:text-slate-100 whitespace-pre-line">{{ $detailMutation->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-slate-200 dark:border-slate-700 pt-6 mt-6">
                        <h4 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">Daftar Item</h4>
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                                    <th class="p-3 text-sm font-semibold text-slate-600 dark:text-slate-300">Produk</th>
                                    <th class="p-3 text-sm font-semibold text-slate-600 dark:text-slate-300 text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($detailMutation->items as $item)
                                    <tr>
                                        <td class="p-3 text-slate-800 dark:text-slate-200">{{ $item->product->name ?? '-' }}</td>
                                        <td class="p-3 text-slate-800 dark:text-slate-200 text-right">{{ $item->qty }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 rounded-b-2xl flex justify-end">
                    <button type="button" wire:click="$set('showDetailModal', false)" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors font-medium cursor-pointer">
                        Tutup
                    </button>
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
                confirmButtonText: 'Ya, hapus!',
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
                toast: true,
                position: 'top-end',
                icon: eventData.type,
                title: eventData.title,
                text: eventData.message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        });
    </script>
    @endscript
</div>