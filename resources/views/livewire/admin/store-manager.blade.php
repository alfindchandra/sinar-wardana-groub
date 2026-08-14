<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Kelola Toko</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Manajemen data pelanggan dan toko</p>
        </div>
        <a href="{{ route('admin.stores.map') }}" wire:navigate class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
            </svg>
            Peta Toko
        </a>
    </div>

    <div class="glass-card p-4 rounded-xl flex flex-col md:flex-row gap-4 bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama toko, kode, pemilik..." class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="w-full md:w-48">
            <select wire:model.live="areaFilter" class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Area</option>
                @foreach($areas as $area)
                    <option value="{{ $area }}">{{ $area }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-48">
            <select wire:model.live="statusFilter" class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Status</option>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
        </div>
    </div>

    <div class="glass-card bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-600 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 uppercase">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Foto</th>
                        <th class="px-6 py-4 font-semibold">Kode</th>
                        <th class="px-6 py-4 font-semibold">Toko & Pemilik</th>
                        <th class="px-6 py-4 font-semibold">Alamat</th>
                        <th class="px-6 py-4 font-semibold">Area / Sales</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($stores as $store)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4">
                                @if($store->store_photo)
                                    <img src="{{ Storage::url($store->store_photo) }}" alt="{{ $store->store_name }}" class="w-12 h-12 rounded-lg object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-900 dark:text-slate-100">{{ $store->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $store->store_name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $store->owner_name }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate" title="{{ $store->address }}">
                                {{ $store->address ?: '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div>{{ $store->area ?: '-' }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">Sales: {{ $store->salesPerson ? $store->salesPerson->name : '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($store->is_active)
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-400">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="toggleStatus({{ $store->id }})" class="text-sm font-medium {{ $store->is_active ? 'text-danger-600 hover:text-danger-900 dark:text-danger-400 dark:hover:text-danger-300' : 'text-success-600 hover:text-success-900 dark:text-success-400 dark:hover:text-success-300' }}">
                                    {{ $store->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                Tidak ada data toko ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
            {{ $stores->links() }}
        </div>
    </div>
</div>
