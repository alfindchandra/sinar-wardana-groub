<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('online-orders.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400 transition-colors mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Pesanan Online
            </a>
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white leading-tight">{{ $order->order_number }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Dipesan pada {{ $order->order_date->translatedFormat('d F Y') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-{{ $statusEnum->color() }}-100 text-{{ $statusEnum->color() }}-800 dark:bg-{{ $statusEnum->color() }}-900/30 dark:text-{{ $statusEnum->color() }}-400">
                {{ $statusEnum->label() }}
            </span>
        </div>
    </div>

    <!-- Aksi Status -->
    @can('update', $order)
        @if ($statusEnum->nextActionLabel() || $statusEnum->canBeCancelled())
            <div class="glass-card p-4 flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-slate-600 dark:text-slate-300 mr-1">Aksi:</span>
                @if ($statusEnum->nextActionLabel())
                    <button wire:click="advanceStatus" wire:confirm="{{ $statusEnum->nextActionLabel() }}?" wire:loading.attr="disabled" wire:target="advanceStatus" type="button" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 disabled:opacity-60 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ $statusEnum->nextActionLabel() }}
                    </button>
                @endif
                @if ($statusEnum->canBeCancelled())
                    <button wire:click="cancelOrder" wire:confirm="Batalkan pesanan ini? Tindakan ini tidak dapat dibatalkan." wire:loading.attr="disabled" wire:target="cancelOrder" type="button" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium text-danger-600 bg-danger-50 dark:bg-danger-900/20 hover:bg-danger-100 dark:hover:bg-danger-900/40 disabled:opacity-60 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Batalkan Pesanan
                    </button>
                @endif
            </div>
        @endif
    @endcan

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card overflow-hidden">
                <div class="p-4 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Item Pesanan</h3>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @foreach ($order->items as $item)
                        <div class="flex items-center gap-4 p-4">
                            <div class="w-14 h-14 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
                                @if ($item->product?->primaryImage)
                                    <img src="{{ $item->product->primaryImage->url }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200 line-clamp-1">{{ $item->product->name ?? 'Produk telah dihapus' }}</p>
                                @if ($item->product_variant_name)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 mt-0.5">{{ $item->product_variant_name }}</span>
                                @endif
                                <p class="text-xs text-slate-400">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200 shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-t border-slate-100 dark:border-slate-700 space-y-1.5">
                    <div class="flex justify-between text-sm text-slate-500 dark:text-slate-400">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($order->discount > 0)
                        <div class="flex justify-between text-sm text-slate-500 dark:text-slate-400">
                            <span>Diskon</span>
                            <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-baseline pt-1.5">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Total</span>
                        <span class="text-lg font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if ($order->notes)
                <div class="glass-card p-5">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-2">Catatan Pelanggan</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Customer Info -->
        <div class="glass-card p-5 h-fit">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-3">Info Pelanggan</h3>
            <div class="space-y-2 text-sm">
                <div>
                    <p class="text-xs text-slate-400">Nama Toko</p>
                    <p class="font-medium text-slate-800 dark:text-slate-200">{{ $order->customer->store_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Pemilik</p>
                    <p class="text-slate-700 dark:text-slate-300">{{ $order->customer->owner_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Telepon</p>
                    <p class="text-slate-700 dark:text-slate-300">{{ $order->customer->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Alamat</p>
                    <p class="text-slate-700 dark:text-slate-300">{{ $order->customer->address ?? '-' }}, {{ $order->customer->city ?? '' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
