<div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-8">

    <nav class="flex text-sm text-slate-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li><a href="{{ route('shop.home') }}" wire:navigate class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Beranda</a></li>
            <li class="flex items-center"><svg class="w-4 h-4 mx-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> <span class="text-slate-700 dark:text-slate-200 font-medium">Keranjang Belanja</span></li>
        </ol>
    </nav>

    <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">Keranjang Belanja</h1>

    @if ($items->isEmpty())
        <div class="glass-card p-16 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <p class="font-medium text-slate-600 dark:text-slate-300">Keranjang Anda masih kosong</p>
            <p class="text-sm text-slate-400 mt-1 mb-5">Yuk mulai belanja produk kebutuhan toko Anda.</p>
            <a href="{{ route('shop.products') }}" wire:navigate class="inline-flex items-center px-5 py-2.5 bg-primary-600 rounded-xl font-medium text-sm text-white shadow-sm shadow-primary-600/20 hover:bg-primary-700 transition-all">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Items -->
            <div class="lg:col-span-2 glass-card overflow-hidden divide-y divide-slate-100 dark:divide-slate-700/50">
                @foreach ($items as $item)
                    <div class="flex items-center gap-4 p-4" wire:key="cart-item-{{ $item['product']->id }}">
                        <a href="{{ route('shop.products.show', $item['product']) }}" wire:navigate class="w-16 h-16 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
                            @if ($item['product']->primaryImage)
                                <img src="{{ $item['product']->primaryImage->url }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 8h16M4 4h16v16H4V4z"></path></svg>
                                </div>
                            @endif
                        </a>

                        <div class="flex-1 min-w-0">
                            <a href="{{ route('shop.products.show', $item['product']) }}" wire:navigate class="text-sm font-medium text-slate-800 dark:text-slate-200 hover:text-primary-600 dark:hover:text-primary-400 line-clamp-1">
                                {{ $item['product']->name }}
                            </a>
                            <p class="text-xs text-slate-400 mt-0.5">Rp {{ number_format($item['price'], 0, ',', '.') }} / {{ \App\Enums\ProductUnit::from($item['product']->unit)->label() }}</p>
                        </div>

                        <div class="flex items-center border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden shrink-0">
                            <button type="button" wire:click="updateQty({{ $item['product']->id }}, {{ $item['qty'] - 1 }})" class="px-2.5 py-1.5 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            </button>
                            <span class="px-3 py-1.5 text-sm font-medium text-slate-800 dark:text-slate-200 min-w-[2.5rem] text-center">{{ $item['qty'] }}</span>
                            <button type="button" wire:click="updateQty({{ $item['product']->id }}, {{ $item['qty'] + 1 }})" class="px-2.5 py-1.5 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>

                        <div class="w-28 text-right shrink-0">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        </div>

                        <button type="button" wire:click="removeItem({{ $item['product']->id }})" wire:confirm="Hapus produk ini dari keranjang?" class="p-2 text-slate-400 hover:text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-900/20 rounded-lg transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                @endforeach
            </div>

            <!-- Summary -->
            <div class="glass-card p-5 h-fit lg:sticky lg:top-24">
                <h3 class="text-base font-bold text-slate-800 dark:text-white mb-4">Ringkasan Belanja</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-slate-500 dark:text-slate-400">
                        <span>Subtotal ({{ $items->sum('qty') }} item)</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="border-t border-slate-100 dark:border-slate-700 mt-4 pt-4 flex justify-between items-baseline">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Total</span>
                    <span class="text-xl font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <a href="{{ route('shop.checkout') }}" wire:navigate class="mt-5 w-full inline-flex items-center justify-center px-5 py-3 bg-primary-600 rounded-xl font-semibold text-sm text-white shadow-sm shadow-primary-600/20 hover:bg-primary-700 transition-all">
                    Checkout Sekarang
                </a>
                <a href="{{ route('shop.products') }}" wire:navigate class="mt-2.5 w-full inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    Lanjut Belanja
                </a>
            </div>
        </div>
    @endif
</div>
