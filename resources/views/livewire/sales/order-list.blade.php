<div>
    <!-- Header Actions -->
    <div class="mb-4 space-y-3">
        <div class="relative">
            <x-heroicon-o-magnifying-glass class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" />
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
                        <x-heroicon-o-calendar class="w-3.5 h-3.5" />
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
                    <x-heroicon-o-document-text class="w-8 h-8 text-slate-400" />
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
