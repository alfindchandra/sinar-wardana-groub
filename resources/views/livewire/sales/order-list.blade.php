<div>
    <!-- Header Actions -->
    <div class="mb-4 space-y-3">
        <div class="relative">
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari SO atau Toko..." 
                class="w-full pl-10 pr-4 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-800 dark:text-white">
        </div>
        
        <div class="flex gap-2 overflow-x-auto pb-1 hide-scrollbar">
            <button wire:click="$set('statusFilter', '')" class="px-3 py-1.5 text-xs font-medium rounded-full whitespace-nowrap {{ $statusFilter === '' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">Semua</button>
            <button wire:click="$set('statusFilter', 'draft')" class="px-3 py-1.5 text-xs font-medium rounded-full whitespace-nowrap {{ $statusFilter === 'draft' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">Draft</button>
            <button wire:click="$set('statusFilter', 'confirmed')" class="px-3 py-1.5 text-xs font-medium rounded-full whitespace-nowrap {{ $statusFilter === 'confirmed' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">Confirmed</button>
            <button wire:click="$set('statusFilter', 'processing')" class="px-3 py-1.5 text-xs font-medium rounded-full whitespace-nowrap {{ $statusFilter === 'processing' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">Processing</button>
            <button wire:click="$set('statusFilter', 'shipped')" class="px-3 py-1.5 text-xs font-medium rounded-full whitespace-nowrap {{ $statusFilter === 'shipped' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">Shipped</button>
            <button wire:click="$set('statusFilter', 'completed')" class="px-3 py-1.5 text-xs font-medium rounded-full whitespace-nowrap {{ $statusFilter === 'completed' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">Completed</button>
            <button wire:click="$set('statusFilter', 'cancelled')" class="px-3 py-1.5 text-xs font-medium rounded-full whitespace-nowrap {{ $statusFilter === 'cancelled' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">Cancelled</button>
        </div>
    </div>

    <!-- Orders List -->
    <div class="space-y-3 pb-24">
        @forelse($orders as $order)
            <a href="{{ route('sales.orders.show', $order->id) }}" wire:navigate class="block glass-card p-4 hover:border-primary-300 transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <div class="text-xs text-slate-500 font-medium mb-0.5">{{ $order->so_number }}</div>
                        <h3 class="font-semibold text-sm text-slate-900 dark:text-white">{{ $order->customer?->store_name ?? 'Pelanggan' }}</h3>
                    </div>
                    @php
                        $badgeClasses = match($order->status) {
                            'draft' => 'bg-slate-100 text-slate-600',
                            'confirmed' => 'bg-blue-100 text-blue-700',
                            'processing' => 'bg-warning-100 text-warning-700',
                            'shipped' => 'bg-purple-100 text-purple-700',
                            'completed' => 'bg-success-100 text-success-700',
                            'cancelled' => 'bg-danger-100 text-danger-700',
                            default => 'bg-slate-100 text-slate-600'
                        };
                    @endphp
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider {{ $badgeClasses }}">
                        {{ $order->status }}
                    </span>
                </div>
                
                <div class="flex justify-between items-end mt-3 pt-3 border-t border-slate-100 dark:border-slate-700">
                    <div class="text-xs text-slate-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"></path></svg>
                        {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                    </div>
                    <div class="font-bold text-primary-600 dark:text-primary-400">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </div>
                </div>
            </a>
        @empty
            <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-1">Tidak ada order</h3>
                <p class="text-xs text-slate-500 mb-4">Belum ada data pesanan yang sesuai dengan pencarian Anda.</p>
                <a href="{{ route('sales.orders.create') }}" wire:navigate class="text-sm text-primary-600 font-medium hover:underline">
                    Buat Order Baru
                </a>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $orders->links('livewire::simple-tailwind') }}
        </div>
    </div>
</div>
