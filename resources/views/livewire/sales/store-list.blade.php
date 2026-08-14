<div class="max-w-lg mx-auto pb-24">
    <div class="px-4 py-4 sticky top-0 z-10 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-300 dark:border-slate-700 rounded-xl leading-5 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out" placeholder="Cari nama toko, pemilik, area...">
        </div>
    </div>

    <div class="px-4 py-4 space-y-4">
        @forelse($stores as $store)
            <div class="glass-card rounded-2xl overflow-hidden transition duration-200 hover:shadow-md">
                <div class="p-4">
                    <div class="flex items-start space-x-4 cursor-pointer" wire:click="$navigate('{{ route('sales.stores.show', $store->id) }}')">
                        <!-- Image -->
                        <div class="flex-shrink-0 w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center overflow-hidden border border-slate-200 dark:border-slate-700">
                            @if($store->store_photo)
                                <img src="{{ Storage::url($store->store_photo) }}" alt="{{ $store->store_name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-slate-900 dark:text-white truncate">
                                    {{ $store->store_name }}
                                </h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $store->customer_type == 'distributor' ? 'bg-purple-100 text-purple-800' : ($store->customer_type == 'agen' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-800') }}">
                                    {{ ucfirst($store->customer_type) }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400 truncate mt-1">
                                {{ $store->owner_name }} &bull; {{ $store->phone }}
                            </p>
                            <div class="flex items-center mt-2 text-xs text-slate-500 dark:text-slate-400">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $store->area ?: ($store->city ?: 'Area tidak ditentukan') }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button wire:click="$navigate('{{ route('sales.orders.create', ['customer' => $store->id]) }}')" class="w-full flex items-center justify-center py-2 px-4 border border-transparent rounded-xl text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Buat Order
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 glass-card rounded-2xl">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">Tidak ada toko</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    @if($search)
                        Tidak ada toko yang cocok dengan pencarian "{{ $search }}".
                    @else
                        Anda belum mendaftarkan toko satupun.
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('sales.stores.create') }}" wire:navigate class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Daftarkan Toko Baru
                    </a>
                </div>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $stores->links() }}
        </div>
    </div>
</div>
