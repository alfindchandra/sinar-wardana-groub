<div>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-slate-600 dark:text-slate-400 mb-2">
            <span>Gudang</span>
            <span>/</span>
            <span class="text-slate-900 dark:text-slate-100 font-medium">Kartu Stok</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Kartu Stok</h1>
    </x-slot>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <!-- Filters -->
        <div class="flex flex-wrap gap-4 mb-6">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari produk atau catatan..." class="w-full pl-10 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            
            <div class="w-48">
                <select wire:model.live="productFilter" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-48">
                <select wire:model.live="warehouseFilter" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Gudang</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-40">
                <select wire:model.live="typeFilter" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Tipe</option>
                    <option value="in">Masuk</option>
                    <option value="out">Keluar</option>
                    <option value="adjustment">Adjustment</option>
                    <option value="mutation">Mutasi</option>
                    <option value="return">Return</option>
                    <option value="opname">Opname</option>
                </select>
            </div>

            <div class="w-40">
                <input type="date" wire:model.live="dateFrom" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500" title="Dari Tanggal">
            </div>

            <div class="w-40">
                <input type="date" wire:model.live="dateTo" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500" title="Sampai Tanggal">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Tanggal</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Produk</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Gudang</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Tipe</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300 text-right">Qty</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300 text-right">Stok Sebelum</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300 text-right">Stok Sesudah</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Catatan</th>
                        <th class="p-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse ($movements as $movement)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="p-4 text-sm text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                {{ $movement->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-4 text-sm text-slate-800 dark:text-slate-200">
                                {{ $movement->product->name ?? '-' }}
                            </td>
                            <td class="p-4 text-sm text-slate-800 dark:text-slate-200">
                                {{ $movement->warehouse->name ?? '-' }}
                            </td>
                            <td class="p-4 text-sm">
                                @if($movement->type === 'in')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400">Masuk</span>
                                @elseif($movement->type === 'out')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400">Keluar</span>
                                @elseif($movement->type === 'adjustment')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">Adjustment</span>
                                @elseif($movement->type === 'mutation')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400">Mutasi</span>
                                @elseif($movement->type === 'return')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300">Return</span>
                                @elseif($movement->type === 'opname')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">Opname</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300">{{ ucfirst($movement->type) }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-right font-medium whitespace-nowrap">
                                @if($movement->type === 'in' || ($movement->qty > 0 && in_array($movement->type, ['adjustment', 'opname'])))
                                    <span class="text-success-600 dark:text-success-400">+{{ $movement->qty }}</span>
                                @elseif($movement->type === 'out' || ($movement->qty < 0 && in_array($movement->type, ['adjustment', 'opname'])))
                                    <span class="text-danger-600 dark:text-danger-400">{{ $movement->qty }}</span>
                                @else
                                    <span class="text-slate-800 dark:text-slate-200">{{ $movement->qty }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-right text-slate-600 dark:text-slate-400 whitespace-nowrap">
                                {{ $movement->stock_before }}
                            </td>
                            <td class="p-4 text-sm text-right text-slate-800 dark:text-slate-200 font-medium whitespace-nowrap">
                                {{ $movement->stock_after }}
                            </td>
                            <td class="p-4 text-sm text-slate-600 dark:text-slate-400">
                                {{ $movement->notes ?: '-' }}
                            </td>
                            <td class="p-4 text-sm text-slate-600 dark:text-slate-400">
                                {{ $movement->creator->name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-slate-500 dark:text-slate-400">
                                Tidak ada data pergerakan stok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $movements->links() }}
        </div>
    </div>
</div>
