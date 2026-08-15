<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Orderan Sales</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Manajemen pesanan dari aplikasi sales</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass-card p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Order</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ number_format($totalOrders) }}</p>
            </div>
        </div>
        <div class="glass-card p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Omset</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">Rp {{ number_format($totalOmset, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="glass-card p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Order Hari Ini</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ number_format($ordersToday) }}</p>
            </div>
        </div>
    </div>

    <div class="glass-card p-4 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari No SO, Toko..." class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <select wire:model.live="statusFilter" class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <select wire:model.live="salesFilter" class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Sales</option>
                    @foreach($salesPersons as $sp)
                        <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <input type="date" wire:model.live="dateFrom" class="w-full px-2 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary-500">
                <input type="date" wire:model.live="dateTo" class="w-full px-2 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary-500">
            </div>
        </div>
    </div>

    <div class="glass-card bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-600 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700 uppercase">
                    <tr>
                        <th class="px-6 py-4 font-semibold">No SO</th>
                        <th class="px-6 py-4 font-semibold">Toko</th>
                        <th class="px-6 py-4 font-semibold">Sales</th>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold">Total</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-medium text-primary-600 dark:text-primary-400">{{ $order->so_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $order->customer ? $order->customer->store_name : '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div>{{ $order->salesPerson ? $order->salesPerson->name : '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $order->payment_type }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-900 dark:text-slate-100">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'draft' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300',
                                        'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'processing' => 'bg-warning-100 text-warning-800 dark:bg-warning-900/30 dark:text-warning-400',
                                        'shipped' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                        'completed' => 'bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400',
                                        'cancelled' => 'bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400',
                                    ];
                                    $statusLabel = [
                                        'draft' => 'Draft',
                                        'confirmed' => 'Confirmed',
                                        'processing' => 'Processing',
                                        'shipped' => 'Shipped',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ];
                                    $class = $statusClasses[$order->status] ?? $statusClasses['draft'];
                                    $label = $statusLabel[$order->status] ?? ucfirst($order->status);
                                @endphp
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                    <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-md shadow-lg z-50 border border-slate-200 dark:border-slate-700 overflow-hidden" style="display: none;">
                                        @if($order->status === 'draft')
                                            <a href="{{ route('sales.orders.edit', $order) }}" class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">Edit Order</a>
                                            <button wire:click="updateStatus({{ $order->id }}, 'confirmed')" class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">Tandai Confirmed</button>
                                            <button wire:click="updateStatus({{ $order->id }}, 'cancelled')" class="block w-full text-left px-4 py-2 text-sm text-danger-600 dark:text-danger-400 hover:bg-slate-100 dark:hover:bg-slate-700">Batalkan</button>
                                        @elseif($order->status === 'confirmed')
                                            <button wire:click="updateStatus({{ $order->id }}, 'processing')" class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">Tandai Processing</button>
                                            <button wire:click="updateStatus({{ $order->id }}, 'cancelled')" class="block w-full text-left px-4 py-2 text-sm text-danger-600 dark:text-danger-400 hover:bg-slate-100 dark:hover:bg-slate-700">Batalkan</button>
                                        @elseif($order->status === 'processing')
                                            <button wire:click="updateStatus({{ $order->id }}, 'shipped')" class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">Tandai Shipped</button>
                                        @elseif($order->status === 'shipped')
                                            <button wire:click="updateStatus({{ $order->id }}, 'completed')" class="block w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">Tandai Completed</button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                Tidak ada data order ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
            {{ $orders->links() }}
        </div>
    </div>
</div>
