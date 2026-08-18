<div class="mx-auto max-w-5xl px-3 sm:px-6 lg:px-8 py-4 sm:py-8 pb-28 lg:pb-8">

    <!-- Breadcrumb -->
    <nav class="flex text-xs sm:text-sm text-slate-500 dark:text-slate-400 mb-3 sm:mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 sm:space-x-2">
            <li>
                <a href="{{ route('shop.home') }}" wire:navigate class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Beranda</a>
            </li>
            <li class="flex items-center">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mx-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-slate-700 dark:text-slate-200 font-medium">Keranjang</span>
            </li>
        </ol>
    </nav>

    <!-- Header Title -->
    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Keranjang Belanja</h1>
        @if ($items->isNotEmpty())
            <span class="text-xs sm:text-sm font-medium px-2.5 py-1 rounded-full bg-primary-50 dark:bg-primary-950/50 text-primary-600 dark:text-primary-400 border border-primary-100 dark:border-primary-900/40">
                {{ $items->sum('qty') }} Barang
            </span>
        @endif
    </div>

    @if ($items->isEmpty())
        <!-- Empty State -->
        <div class="glass-card rounded-2xl p-8 sm:p-16 text-center border border-slate-200/70 dark:border-slate-800 bg-white/70 dark:bg-slate-900/70 backdrop-blur-md shadow-sm">
            <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-4 rounded-full bg-slate-100 dark:bg-slate-800/80 flex items-center justify-center text-slate-400 dark:text-slate-600">
                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100">Keranjang Anda masih kosong</h3>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 mb-6 max-w-sm mx-auto">Yuk mulai lengkapi kebutuhan dan produk pilihan terbaik untuk toko Anda.</p>
            <a href="{{ route('shop.products') }}" wire:navigate class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-700 active:scale-95 rounded-xl font-semibold text-sm text-white shadow-lg shadow-primary-600/25 transition-all">
                Mulai Belanja Sekarang
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-6 items-start">

            <!-- Cart Items List -->
            <div class="lg:col-span-2 space-y-3 sm:space-y-4">
                @foreach ($items as $item)
                    @php 
                        $variantId = $item['variant']?->id ?? 0; 
                        $unitLabel = \App\Enums\ProductUnit::tryFrom($item['product']->unit)?->label() ?? ($item['product']->unit ?? 'Pcs');
                    @endphp

                    <div 
                        wire:key="cart-item-{{ $item['product']->id }}-{{ $variantId }}"
                        class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow"
                    >
                        <div class="flex gap-3 sm:gap-4 items-start">
                            
                            <!-- Product Image -->
                            <a href="{{ route('shop.products.show', $item['product']) }}" wire:navigate class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800/80 shrink-0 border border-slate-100 dark:border-slate-800">
                                @if ($item['product']->primaryImage)
                                    <img src="{{ $item['product']->primaryImage->url }}" alt="{{ $item['product']->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 8h16M4 4h16v16H4V4z"></path></svg>
                                    </div>
                                @endif
                            </a>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0 flex flex-col justify-between self-stretch">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <a href="{{ route('shop.products.show', $item['product']) }}" wire:navigate class="text-sm font-semibold text-slate-900 dark:text-slate-100 hover:text-primary-600 dark:hover:text-primary-400 line-clamp-2 leading-snug">
                                            {{ $item['product']->name }}
                                        </a>
                                        
                                        <!-- Tombol Hapus (Desktop & Tablet) -->
                                        <button 
                                            type="button" 
                                            wire:click="removeItem({{ $item['product']->id }}, {{ $variantId }})" 
                                            wire:confirm="Hapus produk ini dari keranjang?" 
                                            class="hidden sm:inline-flex p-1.5 text-slate-400 hover:text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-900/20 rounded-lg transition-colors"
                                            title="Hapus Item"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>

                                    @if ($item['variant'])
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200/50 dark:border-slate-700/50">
                                                {{ $item['variant']->name }}
                                            </span>
                                        </div>
                                    @endif

                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                        Rp {{ number_format($item['price'], 0, ',', '.') }} <span class="text-[10px]">/ {{ $unitLabel }}</span>
                                    </p>
                                </div>

                                <!-- Subtotal, Quantity Input & Mobile Delete -->
                                <div class="flex flex-wrap items-end justify-between gap-2 mt-3 pt-2 border-t border-slate-100 dark:border-slate-800/80 sm:border-0 sm:pt-0">
                                    <div>
                                        <span class="text-[10px] uppercase tracking-wider font-semibold text-slate-400 block sm:hidden">Subtotal</span>
                                        <p class="text-sm sm:text-base font-bold text-primary-600 dark:text-primary-400">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <!-- Tombol Hapus (Mobile) -->
                                        <button 
                                            type="button" 
                                            wire:click="removeItem({{ $item['product']->id }}, {{ $variantId }})" 
                                            wire:confirm="Hapus produk ini dari keranjang?" 
                                            class="sm:hidden p-1.5 text-slate-400 hover:text-danger-600 active:bg-danger-50 dark:active:bg-danger-900/20 rounded-lg transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>

                                        <!-- Quantity Stepper dengan Input Teks Manual -->
                                        <div class="flex items-center border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-xl overflow-hidden p-0.5">
                                            <button 
                                                type="button" 
                                                wire:click="updateQty({{ $item['product']->id }}, {{ $variantId }}, {{ $item['qty'] - 1 }})" 
                                                class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 shadow-sm active:scale-90 transition-transform shrink-0"
                                                aria-label="Kurang"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"></path></svg>
                                            </button>

                                            <input 
                                                type="number" 
                                                min="1" 
                                                value="{{ $item['qty'] }}" 
                                                wire:change="updateQty({{ $item['product']->id }}, {{ $variantId }}, $event.target.value)"
                                                class="w-12 sm:w-14 text-center bg-transparent border-0 p-0 text-xs sm:text-sm font-bold text-slate-900 dark:text-slate-100 focus:ring-0 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            >

                                            <button 
                                                type="button" 
                                                wire:click="updateQty({{ $item['product']->id }}, {{ $variantId }}, {{ $item['qty'] + 1 }})" 
                                                class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 shadow-sm active:scale-90 transition-transform shrink-0"
                                                aria-label="Tambah"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop Summary Sidebar -->
            <div class="hidden lg:block glass-card p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md shadow-sm sticky top-24">
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Ringkasan Belanja</h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-slate-600 dark:text-slate-400">
                        <span>Total Kuantitas</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $items->sum('qty') }} item</span>
                    </div>
                    <div class="flex justify-between text-slate-600 dark:text-slate-400">
                        <span>Total Harga</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 mt-5 pt-4 flex justify-between items-baseline">
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">Grand Total</span>
                    <span class="text-2xl font-black text-primary-600 dark:text-primary-400 tracking-tight">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <a href="{{ route('shop.checkout') }}" wire:navigate class="mt-6 w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 bg-primary-600 hover:bg-primary-700 active:scale-[0.98] rounded-xl font-semibold text-sm text-white shadow-lg shadow-primary-600/25 transition-all">
                    <span>Lanjut ke Checkout</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>

                <a href="{{ route('shop.products') }}" wire:navigate class="mt-3 w-full inline-flex items-center justify-center px-4 py-2.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    ← Lanjut Pilih Produk Lain
                </a>
            </div>

            <!-- Mobile Sticky Bottom Checkout Bar -->
            <div class="fixed lg:hidden bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200/80 dark:border-slate-800 px-4 py-3 shadow-[0_-8px_20px_rgba(0,0,0,0.06)]">
                <div class="max-w-5xl mx-auto flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 block">Total Pembayaran</span>
                        <div class="text-lg font-black text-primary-600 dark:text-primary-400 truncate tracking-tight">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                    <a href="{{ route('shop.checkout') }}" wire:navigate class="shrink-0 inline-flex items-center justify-center gap-1.5 px-6 py-3 bg-primary-600 active:bg-primary-700 active:scale-95 rounded-xl font-bold text-sm text-white shadow-md shadow-primary-600/30 transition-all">
                        <span>Checkout</span>
                        <span class="text-xs opacity-80">({{ $items->sum('qty') }})</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

        </div>
    @endif
</div>