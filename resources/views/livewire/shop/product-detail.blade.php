<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeImage: 0 }">

    <!-- Breadcrumb -->
    <nav class="flex text-sm text-slate-500 dark:text-slate-400 mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li><a href="{{ route('shop.home') }}" wire:navigate class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Beranda</a></li>
            <li class="flex items-center"><svg class="w-4 h-4 mx-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <a href="{{ route('shop.products') }}" wire:navigate class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ $product->category->name ?? 'Produk' }}</a>
            </li>
            <li class="flex items-center"><svg class="w-4 h-4 mx-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> <span class="text-slate-700 dark:text-slate-200 font-medium line-clamp-1">{{ $product->name }}</span></li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        <!-- Galeri Gambar -->
        <div>
            <div class="glass-card overflow-hidden aspect-square">
                @if ($product->images->isNotEmpty())
                    @foreach ($product->images as $index => $image)
                        <img x-show="activeImage === {{ $index }}" src="{{ $image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @endforeach
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 8h16M4 4h16v16H4V4z"></path></svg>
                    </div>
                @endif
            </div>

            @if ($product->images->count() > 1)
                <div class="flex gap-3 mt-3 overflow-x-auto pb-1">
                    @foreach ($product->images as $index => $image)
                        <button type="button" @click="activeImage = {{ $index }}" :class="activeImage === {{ $index }} ? 'border-primary-500' : 'border-transparent'" class="w-16 h-16 rounded-lg overflow-hidden border-2 shrink-0 transition-colors">
                            <img src="{{ $image->url }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Info Produk -->
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-primary-600 dark:text-primary-400">{{ $product->category->name ?? '' }}</p>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $product->name }}</h1>
            <p class="text-sm text-slate-400 mt-1">SKU: {{ $product->sku }} @if($product->brand) &middot; Merek: {{ $product->brand }} @endif</p>

            <div class="mt-5 flex items-baseline gap-2">
                <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($this->price, 0, ',', '.') }}</span>
                <span class="text-sm text-slate-400">/ {{ \App\Enums\ProductUnit::from($product->unit)->label() }}</span>
            </div>
            <p class="text-xs text-slate-400 mt-1">Harga berlaku untuk pembelian per {{ \App\Enums\ProductUnit::from($product->unit)->label() }} (kemasan utama).</p>

            <!-- Breakdown Harga Otomatis (info saja, TIDAK bisa dibeli terpisah) -->
            @if ($product->hasBreakdown())
                <div class="mt-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-4">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Estimasi Harga Pecahan
                    </p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $product->breakdownDescription() }}</p>
                    <p class="text-[11px] text-slate-400 mt-1.5">*Hanya informasi. Pembelian tetap dalam satuan {{ \App\Enums\ProductUnit::from($product->unit)->label() }} (tidak dijual per Bal/Pcs terpisah).</p>
                </div>
            @endif

            <!-- Pilih Varian (wajib jika ada) -->
            @if ($product->hasVariants())
                <div class="mt-6">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Pilih Varian <span class="text-danger-500">*</span>
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($product->variants->where('is_active', true) as $variant)
                            <button
                                type="button"
                                wire:click="selectVariant({{ $variant->id }})"
                                @disabled($variant->stock <= 0)
                                class="px-4 py-2 rounded-xl text-sm font-medium border-2 transition-colors disabled:opacity-40 disabled:cursor-not-allowed
                                    {{ $selectedVariantId === $variant->id
                                        ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400'
                                        : 'border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-primary-300' }}"
                            >
                                {{ $variant->name }}
                                @if ($variant->stock <= 0)
                                    <span class="text-[10px] block text-danger-500">Habis</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                    @if (! $selectedVariantId)
                        <p class="mt-2 text-xs text-warning-600 dark:text-warning-400">Pilih salah satu varian sebelum menambahkan ke keranjang.</p>
                    @endif
                </div>
            @endif

            <div class="mt-5 flex items-center gap-3 text-sm">
                @php $displayStock = $product->hasVariants() ? ($product->variants->firstWhere('id', $selectedVariantId)?->stock) : $product->total_stock; @endphp
                <span class="inline-flex items-center px-2.5 py-1 rounded-full font-medium {{ ($displayStock ?? 0) <= 0 ? 'bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400' : 'bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400' }}">
                    Stok: {{ $displayStock ?? '-' }}
                </span>
                <span class="text-slate-400">Min. pembelian {{ $product->min_purchase }} {{ \App\Enums\ProductUnit::from($product->unit)->label() }}</span>
            </div>

            @if ($product->description)
                <div class="mt-6">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ $product->description }}</p>
                </div>
            @endif

            <!-- Qty & Add to Cart -->
            <div class="mt-8 flex items-center gap-4">
                <div class="flex items-center border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                    <button type="button" wire:click="decrement" class="px-3.5 py-2.5 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    </button>
                    <span class="px-4 py-2.5 text-sm font-semibold text-slate-800 dark:text-slate-200 min-w-[3rem] text-center">{{ $qty }}</span>
                    <button type="button" wire:click="increment" class="px-3.5 py-2.5 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                </div>

                <button type="button" wire:click="addCurrentToCart" @disabled(! $this->canAddToCart) class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 rounded-xl font-semibold text-sm text-white shadow-sm shadow-primary-600/20 hover:bg-primary-700 disabled:bg-slate-300 disabled:cursor-not-allowed dark:disabled:bg-slate-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"></path></svg>
                    @if ($product->hasVariants() && ! $selectedVariantId)
                        Pilih Varian Dulu
                    @elseif (! $this->canAddToCart)
                        Stok Habis
                    @else
                        Tambah ke Keranjang
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if ($related->isNotEmpty())
        <section class="mt-16">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-5">Produk Terkait</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach ($related as $item)
                    <x-shop.product-card :product="$item" wire:key="related-product-{{ $item->id }}" />
                @endforeach
            </div>
        </section>
    @endif
</div>
