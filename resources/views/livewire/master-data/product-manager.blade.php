<div>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-1">
                <a href="#" class="hover:text-primary-600 transition-colors">Master Data</a>
                <span>/</span>
                <span class="text-slate-800 dark:text-slate-200 font-medium">Produk</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Daftar Produk</h1>
        </div>
    </x-slot>

    <x-slot name="actions">
        <a href="{{ route('master-data.products.create') }}" class="flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Produk
        </a>
    </x-slot>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Cari nama produk, SKU, barcode...">
            </div>
            
            <div class="w-full md:w-48">
                <select wire:model.live="categoryFilter" class="block w-full py-2 px-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-48">
                <select wire:model.live="statusFilter" class="block w-full py-2 px-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                <!-- Image -->
                <div class="h-48 bg-slate-100 dark:bg-slate-900 relative">
                    @if($product->primaryImage)
                        <img src="{{ Storage::url($product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    
                    <div class="absolute top-2 right-2">
                        @if($product->is_active)
                            <span class="px-2 py-1 text-[10px] font-bold bg-success-500 text-white rounded-md shadow-sm">Aktif</span>
                        @else
                            <span class="px-2 py-1 text-[10px] font-bold bg-danger-500 text-white rounded-md shadow-sm">Nonaktif</span>
                        @endif
                    </div>
                </div>
                
                <div class="p-4 flex-1 flex flex-col">
                    <div class="text-xs text-primary-600 dark:text-primary-400 font-medium mb-1 flex justify-between">
                        <span>{{ $product->category?->name ?? 'Uncategorized' }}</span>
                        <span class="text-slate-500">{{ $product->sku }}</span>
                    </div>
                    
                    <h3 class="font-bold text-slate-800 dark:text-slate-100 mb-2 line-clamp-2" title="{{ $product->name }}">{{ $product->name }}</h3>
                    
                    <div class="mt-auto pt-4 border-t border-slate-100 dark:border-slate-700/50 flex justify-between items-end">
                        <div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-0.5">Harga Jual</div>
                            <div class="font-bold text-lg text-slate-800 dark:text-slate-100">{{ $product->formatted_sell_price }}</div>
                        </div>
                        
                        <div class="text-right">
                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-0.5">Stok</div>
                            @php
                                $stockStatus = $product->stock_status;
                                $stockColor = $stockStatus === 'In Stock' ? 'text-success-600 dark:text-success-400' : ($stockStatus === 'Low Stock' ? 'text-warning-600 dark:text-warning-400' : 'text-danger-600 dark:text-danger-400');
                            @endphp
                            <div class="font-semibold {{ $stockColor }}">{{ $product->total_stock }} {{ $product->unit }}</div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/50 flex justify-end gap-2">
                        <a href="{{ route('master-data.products.edit', $product->id) }}" class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <button wire:click="triggerDelete({{ $product->id }})" class="p-2 text-slate-500 hover:bg-danger-50 hover:text-danger-600 dark:hover:bg-danger-900/30 dark:hover:text-danger-400 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                <svg class="w-12 h-12 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <p class="text-lg font-medium">Tidak ada produk ditemukan</p>
                <p class="text-sm mt-1">Coba sesuaikan filter atau pencarian Anda.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $products->links() }}
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
