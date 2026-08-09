@props(['product'])

@php
    $stock = $product->total_stock;
    $price = $product->checkoutPrice();
    $image = $product->primaryImage;
    $hasVariants = $product->hasVariants();
@endphp

<div class="glass-card overflow-hidden group flex flex-col h-full">
    <a href="{{ route('shop.products.show', $product) }}" wire:navigate class="block relative aspect-square bg-slate-100 dark:bg-slate-800 overflow-hidden">
        @if ($image)
            <img src="{{ $image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 8h16M4 4h16v16H4V4z"></path></svg>
            </div>
        @endif

        @if ($stock <= 0 && ! $hasVariants)
            <div class="absolute inset-0 bg-slate-900/60 flex items-center justify-center">
                <span class="px-3 py-1 bg-white/90 text-slate-800 text-xs font-bold rounded-full">Stok Habis</span>
            </div>
        @elseif ($stock <= $product->min_stock && ! $hasVariants)
            <span class="absolute top-2 left-2 px-2 py-0.5 bg-warning-500 text-white text-[10px] font-bold rounded-full">Stok Terbatas</span>
        @endif

        @if ($hasVariants)
            <span class="absolute top-2 right-2 px-2 py-0.5 bg-primary-600 text-white text-[10px] font-bold rounded-full">{{ $product->variants->count() }} Varian</span>
        @endif
    </a>

    <div class="p-3.5 flex flex-col flex-1">
        <p class="text-[11px] text-slate-400 uppercase tracking-wide mb-0.5">{{ $product->category->name ?? '' }}</p>
        <a href="{{ route('shop.products.show', $product) }}" wire:navigate class="text-sm font-medium text-slate-800 dark:text-slate-200 line-clamp-2 hover:text-primary-600 dark:hover:text-primary-400 transition-colors min-h-[2.5rem]">
            {{ $product->name }}
        </a>

        <div class="mt-2 flex items-baseline gap-1.5">
            <span class="text-base font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($price, 0, ',', '.') }}</span>
            <span class="text-xs text-slate-400">/ {{ \App\Enums\ProductUnit::from($product->unit)->label() }}</span>
        </div>

        <div class="mt-3 flex items-center justify-between gap-2">
            <span class="text-xs text-slate-400">Stok {{ $stock }}</span>

            @if ($hasVariants)
                {{-- Produk dengan varian: arahkan ke detail untuk memilih varian dulu --}}
                <a href="{{ route('shop.products.show', $product) }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-primary-600 bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors">
                    Pilih Varian
                </a>
            @else
                <button
                    type="button"
                    wire:click="addToCart({{ $product->id }})"
                    @disabled($stock <= 0)
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-primary-600 hover:bg-primary-700 disabled:bg-slate-300 disabled:cursor-not-allowed dark:disabled:bg-slate-700 transition-colors"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"></path></svg>
                    Keranjang
                </button>
            @endif
        </div>
    </div>
</div>
