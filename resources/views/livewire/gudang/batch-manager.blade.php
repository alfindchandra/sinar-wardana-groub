<div>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-slate-600 dark:text-slate-400 mb-2">
            <span>Gudang</span>
            <span>/</span>
            <span class="font-medium text-slate-900 dark:text-slate-100">Batch</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Manajemen Batch</h1>
    </x-slot>

    <x-slot name="actions">
        <button wire:click="create" class="bg-primary-600 text-white px-4 py-2 rounded-xl hover:bg-primary-700 font-medium transition-colors">
            + Tambah Batch
        </button>
    </x-slot>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari batch / produk..." class="pl-10 w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="w-full md:w-64">
                <select wire:model.live="warehouseFilter" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Gudang</option>
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">No. Batch</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Produk</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Gudang</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Supplier</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Tgl Terima</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Qty Awal</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Qty Sisa</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Expiry</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($batches as $batch)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="p-4 text-sm text-slate-800 dark:text-slate-200">
                                <span class="px-2 py-1 bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400 rounded-md font-medium">
                                    {{ $batch->batch_number }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-slate-800 dark:text-slate-200">{{ $batch->product?->name ?? '-' }}</td>
                            <td class="p-4 text-sm text-slate-800 dark:text-slate-200">{{ $batch->warehouse?->name ?? '-' }}</td>
                            <td class="p-4 text-sm text-slate-800 dark:text-slate-200">{{ $batch->supplier?->name ?? '-' }}</td>
                            <td class="p-4 text-sm text-slate-600 dark:text-slate-400">{{ $batch->received_date ? \Carbon\Carbon::parse($batch->received_date)->format('d/m/Y') : '-' }}</td>
                            <td class="p-4 text-sm text-slate-800 dark:text-slate-200">{{ $batch->initial_qty }}</td>
                            <td class="p-4 text-sm">
                                @if($batch->remaining_qty <= 0)
                                    <span class="px-2 py-1 bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400 rounded-md text-xs font-medium">Habis</span>
                                @else
                                    <span class="text-slate-800 dark:text-slate-200">{{ $batch->remaining_qty }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm">
                                @if($batch->expiry_date)
                                    @php
                                        $expiryDate = \Carbon\Carbon::parse($batch->expiry_date);
                                        $daysToExpiry = now()->startOfDay()->diffInDays($expiryDate->startOfDay(), false);
                                    @endphp
                                    @if($daysToExpiry < 0)
                                        <span class="text-danger-600 dark:text-danger-400 font-medium">{{ $expiryDate->format('d/m/Y') }}</span>
                                    @elseif($daysToExpiry <= 30)
                                        <span class="text-amber-600 dark:text-amber-400 font-medium">{{ $expiryDate->format('d/m/Y') }}</span>
                                    @else
                                        <span class="text-slate-600 dark:text-slate-400">{{ $expiryDate->format('d/m/Y') }}</span>
                                    @endif
                                @else
                                    <span class="text-slate-600 dark:text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-center">
                                <div class="flex justify-center space-x-3">
                                    <button wire:click="edit({{ $batch->id }})" class="text-slate-400 hover:text-primary-600 transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="triggerDelete({{ $batch->id }})" class="text-slate-400 hover:text-danger-600 transition-colors" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-slate-500 dark:text-slate-400">Tidak ada data batch.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $batches->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <div x-data="{ show: @entangle('showModal') }"
         x-show="show"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-slate-900/75 backdrop-blur-sm"
                 @click="show = false">
            </div>

            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-2xl text-left align-bottom transition-all transform bg-white dark:bg-slate-800 rounded-xl shadow-xl sm:my-8 sm:align-middle">
                
                <form wire:submit.prevent="save">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                            {{ $isEdit ? 'Edit Batch' : 'Tambah Batch' }}
                        </h3>
                    </div>

                    <div class="px-6 py-5 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Produk</label>
                                <select wire:model="product_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Gudang</label>
                                <select wire:model="warehouse_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Pilih Gudang</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">No. Batch</label>
                                <input wire:model="batch_number" type="text" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                @error('batch_number') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Supplier (Opsional)</label>
                                <select wire:model="supplier_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Pilih Supplier</option>
                                    @foreach($suppliers as $sup)
                                        <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tgl Terima</label>
                                <input wire:model="received_date" type="date" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                @error('received_date') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tgl Kadaluarsa (Opsional)</label>
                                <input wire:model="expiry_date" type="date" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                @error('expiry_date') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Qty Awal</label>
                                <input wire:model="initial_qty" type="number" min="1" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                @error('initial_qty') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Qty Sisa</label>
                                <input wire:model="remaining_qty" type="number" min="0" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500" {{ !$isEdit ? 'placeholder="Sama dengan Qty Awal jika kosong"' : '' }}>
                                @error('remaining_qty') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan</label>
                            <textarea wire:model="notes" rows="3" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500"></textarea>
                            @error('notes') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/50 rounded-b-xl flex justify-end space-x-3">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors font-medium">
                            Batal
                        </button>
                        <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-xl hover:bg-primary-700 font-medium transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal:confirm', (event) => {
                Swal.fire({
                    title: event[0].title,
                    text: event[0].text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteConfirm', { id: event[0].id });
                    }
                });
            });

            Livewire.on('toast', (event) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
                
                Toast.fire({
                    icon: event[0].type,
                    title: event[0].title,
                    text: event[0].message
                });
            });
        });
    </script>
    @endpush
</div>
