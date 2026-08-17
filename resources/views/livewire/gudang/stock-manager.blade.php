<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex text-sm text-slate-500 dark:text-slate-400 mb-2">
                    <ol class="flex items-center space-x-2">
                        <li><a href="#" class="hover:text-slate-800 dark:hover:text-slate-200">Gudang</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li class="font-semibold text-slate-800 dark:text-slate-200">Stok</li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Daftar Stok Gudang</h1>
            </div>
            <x-slot name="actions">
                <!-- No action button (read-only) -->
            </x-slot>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari produk..." class="w-full pl-10 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div class="w-full sm:w-48">
                <select wire:model.live="warehouseFilter" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Gudang</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-48">
                <select wire:model.live="stockFilter" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Status</option>
                    <option value="low">Stok Rendah</option>
                    <option value="out">Stok Habis</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Produk</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Kategori</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Gudang</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Total Stok</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Min Stok</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($products as $product)
                        @php
                            $totalStock = $product->warehouses->sum('pivot.stock');
                            $minStock = $product->min_stock ?? 0;
                            
                            if ($totalStock == 0) {
                                $statusClass = 'bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400';
                                $statusText = 'Out of Stock';
                            } elseif ($totalStock <= $minStock) {
                                $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';
                                $statusText = 'Low Stock';
                            } else {
                                $statusClass = 'bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400';
                                $statusText = 'In Stock';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="p-4 text-slate-800 dark:text-slate-200">
                                <div class="font-medium">{{ $product->name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $product->sku }}</div>
                            </td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">
                                {{ $product->category->name ?? '-' }}
                            </td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">
                                <div class="space-y-1">
                                    @forelse($product->warehouses as $wh)
                                        <div class="text-sm">
                                            <span class="font-medium">{{ $wh->name }}:</span> 
                                            {{ $wh->pivot->stock ?? 0 }} {{ $product->unit ?? '' }}
                                        </div>
                                    @empty
                                        <span class="text-xs italic text-slate-500">Belum ada stok</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="p-4 font-semibold text-slate-800 dark:text-slate-200">
                                {{ $totalStock }} {{ $product->unit ?? '' }}
                            </td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">
                                {{ $minStock }} {{ $product->unit ?? '' }}
                            </td>
                            <td class="p-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-slate-500 dark:text-slate-400">
                                Tidak ada data stok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
