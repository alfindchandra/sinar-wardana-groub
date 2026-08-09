<div>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-1">
                <a href="#" class="hover:text-primary-600 transition-colors">Master Data</a>
                <span>/</span>
                <span class="text-slate-800 dark:text-slate-200 font-medium">Gudang</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Manajemen Gudang</h1>
        </div>
    </x-slot>

    <x-slot name="actions">
        <button wire:click="create" class="flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Gudang
        </button>
    </x-slot>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Cari gudang...">
            </div>
            <div class="w-full sm:w-48">
                <select wire:model.live="typeFilter" class="block w-full py-2 px-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    <option value="">Semua Tipe</option>
                    @foreach($types as $typeEnum)
                        <option value="{{ is_object($typeEnum) ? $typeEnum->value : $typeEnum }}">{{ is_object($typeEnum) ? $typeEnum->name : $typeEnum }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Kode</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300 cursor-pointer" wire:click="sortBy('name')">Nama Gudang</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Tipe</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">PIC</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Telepon</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Status</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouses as $warehouse)
                    <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="p-4 font-medium text-primary-600 dark:text-primary-400">{{ $warehouse->code }}</td>
                        <td class="p-4 text-slate-800 dark:text-slate-200 font-medium">{{ $warehouse->name }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                {{ is_object($warehouse->type) ? $warehouse->type->name : $warehouse->type }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-600 dark:text-slate-400">{{ $warehouse->pic ?: '-' }}</td>
                        <td class="p-4 text-slate-600 dark:text-slate-400">{{ $warehouse->phone ?: '-' }}</td>
                        <td class="p-4">
                            @if($warehouse->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400">Nonaktif</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <button wire:click="edit({{ $warehouse->id }})" class="p-2 text-slate-400 hover:text-primary-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button wire:click="triggerDelete({{ $warehouse->id }})" class="p-2 text-slate-400 hover:text-danger-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500 dark:text-slate-400">Tidak ada data gudang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $warehouses->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    <div x-data="{ show: @entangle('showModal') }" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="show = false"></div>
        
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-lg z-10 border border-slate-200 dark:border-slate-700 flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $isEdit ? 'Edit Gudang' : 'Tambah Gudang' }}</h3>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            
            <form wire:submit.prevent="save" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Gudang <span class="text-danger-500">*</span></label>
                        <input wire:model="name" type="text" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                        @error('name') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kode Gudang</label>
                        <input wire:model="code" type="text" disabled placeholder="Otomatis" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 text-slate-500 cursor-not-allowed">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tipe Gudang <span class="text-danger-500">*</span></label>
                    <select wire:model="type" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Pilih Tipe</option>
                        @foreach($types as $typeEnum)
                            <option value="{{ is_object($typeEnum) ? $typeEnum->value : $typeEnum }}">{{ is_object($typeEnum) ? $typeEnum->name : $typeEnum }}</option>
                        @endforeach
                    </select>
                    @error('type') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">PIC (Penanggung Jawab)</label>
                    <input wire:model="pic" type="text" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">No. Telepon</label>
                    <input wire:model="phone" type="text" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alamat Lengkap</label>
                    <textarea wire:model="address" rows="3" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="is_active" type="checkbox" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Aktif</span>
                    </label>
                </div>
            </form>
            
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 bg-slate-50 dark:bg-slate-800/50 rounded-b-xl">
                <button type="button" @click="show = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">Batal</button>
                <button wire:click="save" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-lg shadow-primary-600/20">Simpan</button>
            </div>
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
