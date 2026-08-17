<div>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 mb-2">
            <span>Gudang</span>
            <span>/</span>
            <span class="font-medium text-slate-900 dark:text-slate-100">Mutasi</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Mutasi Stok</h1>
    </x-slot>

    <x-slot name="actions">
        <button wire:click="create" class="px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors font-medium">
            + Tambah Mutasi
        </button>
    </x-slot>

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
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
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
                                <button wire:click="viewDetail({{ $mutation->id }})" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Lihat Detail">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>

                                @if($mutation->status === 'draft')
                                    <button wire:click="approve({{ $mutation->id }})" class="p-1.5 text-success-500 hover:text-success-600 transition-colors" title="Setujui">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </button>

                                    <button wire:click="edit({{ $mutation->id }})" class="p-1.5 text-slate-400 hover:text-primary-600 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    
                                    <button wire:click="triggerDelete({{ $mutation->id }})" class="p-1.5 text-slate-400 hover:text-danger-600 transition-colors" title="Hapus">
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

    <!-- Modal Form -->
    <div x-data="{ show: @entangle('showModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden p-4">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
        <div x-show="show" x-transition.scale.95 class="relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 my-8">
            <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">
                    {{ $isEdit ? 'Edit Mutasi' : 'Tambah Mutasi' }}
                </h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form wire:submit="save" class="p-6 overflow-y-auto max-h-[calc(100vh-12rem)]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Gudang Asal</label>
                        <select wire:model="from_warehouse_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Pilih Gudang --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        @error('from_warehouse_id') <span class="text-sm text-danger-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Gudang Tujuan</label>
                        <select wire:model="to_warehouse_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Pilih Gudang --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        @error('to_warehouse_id') <span class="text-sm text-danger-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Mutasi</label>
                        <input type="date" wire:model="mutation_date" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                        @error('mutation_date') <span class="text-sm text-danger-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan</label>
                        <textarea wire:model="notes" rows="2" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500"></textarea>
                        @error('notes') <span class="text-sm text-danger-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4 flex items-center justify-between border-t border-slate-200 dark:border-slate-700 pt-6">
                    <h4 class="text-lg font-medium text-slate-900 dark:text-slate-100">Daftar Item</h4>
                    <button type="button" wire:click="addItem" class="px-3 py-1.5 text-sm bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        + Tambah Item
                    </button>
                </div>

                @error('items') <div class="text-sm text-danger-500 mb-2">{{ $message }}</div> @enderror

                <div class="space-y-3">
                    @foreach($items as $index => $item)
                        <div class="flex items-start gap-3">
                            <div class="flex-1">
                                <select wire:model="items.{{ $index }}.product_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                    @endforeach
                                </select>
                                @error('items.'.$index.'.product_id') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-32">
                                <input type="number" wire:model="items.{{ $index }}.qty" min="1" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500" placeholder="Qty">
                                @error('items.'.$index.'.qty') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror
                            </div>
                            <button type="button" wire:click="removeItem({{ $index }})" class="p-2.5 text-slate-400 hover:text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-900/30 rounded-lg transition-colors mt-0.5">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-slate-600 dark:text-slate-300 font-medium hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors font-medium">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-data="{ show: @entangle('showDetailModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
        <div x-show="show" x-transition.scale.95 class="relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Detail Mutasi</h3>
                <button wire:click="$set('showDetailModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="p-6 max-h-[calc(100vh-10rem)] overflow-y-auto">
                @if($detailMutation)
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
                @endif
            </div>
            <div class="p-6 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 rounded-b-2xl flex justify-end">
                <button wire:click="$set('showDetailModal', false)" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors font-medium">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- SweetAlert Confirm Script -->
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
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteConfirm', { id: data[0].id });
                    }
                });
            });
            
            Livewire.on('toast', (data) => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: data[0].type,
                    title: data[0].title,
                    text: data[0].message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        });
    </script>
</div>
