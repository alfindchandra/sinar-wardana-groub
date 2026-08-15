<div class="min-h-screen bg-slate-50/50 dark:bg-slate-950/50">
    <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8 py-5 sm:py-10 space-y-8 sm:space-y-12">

        <!-- Kategori Pilihan -->
        @if ($categories->isNotEmpty())
            <section class="space-y-3 sm:space-y-4">
                <div class="flex items-center justify-between px-0.5 sm:px-1">
                    <div>
                        <h2 class="text-base sm:text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                            Belanja per Kategori
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 hidden sm:block mt-0.5">
                            Pilih produk berdasarkan kategori kebutuhan Anda
                        </p>
                    </div>
                    <a href="{{ route('shop.products') }}" 
                       wire:navigate 
                       class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 transition-colors group">
                        <span>Lihat Semua</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <!-- MOBILE (HP): Bar Kategori Geser ke Samping (Horizontal Scroll) -->
                <div class="sm:hidden -mx-3 px-3 overflow-x-auto scrollbar-none flex items-center gap-2.5 pb-2">
                    @foreach ($categories as $cat)
                        <a href="{{ route('shop.products', ['category' => $cat->id]) }}" 
                           wire:navigate 
                           class="shrink-0 flex items-center gap-2 px-3 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm active:scale-95 transition-transform">
                            
                            <div class="w-7 h-7 rounded-lg bg-primary-50 dark:bg-primary-950/50 text-primary-600 dark:text-primary-400 flex items-center justify-center p-1 shrink-0 overflow-hidden">
                                @if ($cat->icon && (str_starts_with($cat->icon, 'categories/') || str_ends_with($cat->icon, '.svg')))
                                    <img src="{{ asset('storage/' . $cat->icon) }}" alt="{{ $cat->name }}" class="w-full h-full object-contain">
                                @elseif ($cat->icon && str_contains($cat->icon, 'fa-'))
                                    <i class="{{ $cat->icon }} text-xs"></i>
                                @elseif ($cat->icon)
                                    <span class="text-xs font-bold">{{ $cat->icon }}</span>
                                @else
                                    <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                @endif
                            </div>

                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $cat->name }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- TABLET & DESKTOP: Grid Kategori Bersih -->
                <div class="hidden sm:grid sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3 sm:gap-3.5">
                    @foreach ($categories as $cat)
                        <a href="{{ route('shop.products', ['category' => $cat->id]) }}" 
                           wire:navigate 
                           class="group relative flex flex-col items-center justify-center p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200/70 dark:border-slate-800/80 shadow-sm hover:shadow-md hover:border-primary-500/30 hover:-translate-y-1 active:scale-95 transition-all duration-200 text-center">
                            
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-primary-50 dark:bg-primary-950/50 text-primary-600 dark:text-primary-400 flex items-center justify-center p-2.5 mb-2 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-200 overflow-hidden">
                                @if ($cat->icon && (str_starts_with($cat->icon, 'categories/') || str_ends_with($cat->icon, '.svg')))
                                    <img src="{{ asset('storage/' . $cat->icon) }}" alt="{{ $cat->name }}" class="w-full h-full object-contain group-hover:brightness-0 group-hover:invert transition-all">
                                @elseif ($cat->icon && str_contains($cat->icon, 'fa-'))
                                    <i class="{{ $cat->icon }} text-lg sm:text-xl"></i>
                                @elseif ($cat->icon)
                                    <span class="text-lg sm:text-xl font-bold">{{ $cat->icon }}</span>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                @endif
                            </div>
                            
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300 group-hover:text-primary-600 dark:group-hover:text-primary-400 truncate w-full transition-colors">
                                {{ $cat->name }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Produk Terbaru -->
        <section class="space-y-3 sm:space-y-4">
            <div class="flex items-center justify-between px-0.5 sm:px-1">
                <div>
                    <h2 class="text-base sm:text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Produk Terbaru
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 hidden sm:block mt-0.5">
                        Koleksi produk terbaru yang siap dipesan
                    </p>
                </div>
                <a href="{{ route('shop.products') }}" 
                   wire:navigate 
                   class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 transition-colors group">
                    <span>Semua Produk</span>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if ($newestProducts->isEmpty())
                <div class="p-10 sm:p-14 text-center rounded-3xl border border-dashed border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 mx-auto mb-3 rounded-2xl bg-slate-100 dark:bg-slate-800/80 flex items-center justify-center text-slate-400">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <p class="text-sm sm:text-base font-semibold text-slate-700 dark:text-slate-300">Belum ada produk</p>
                    <p class="text-xs sm:text-sm text-slate-400 dark:text-slate-500 mt-0.5">Katalog produk terbaru akan segera diunggah.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2.5 sm:gap-4">
                    @foreach ($newestProducts as $product)
                        <x-shop.product-card :product="$product" wire:key="home-product-{{ $product->id }}" />
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Layanan / Keunggulan Toko -->
        <section class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 sm:gap-4 pt-2">
            <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200/70 dark:border-slate-800/80 shadow-sm flex items-center sm:items-start gap-3.5">
                <div class="p-2.5 bg-primary-50 dark:bg-primary-950/60 text-primary-600 dark:text-primary-400 rounded-xl shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-slate-800 dark:text-white text-xs sm:text-sm">Pengiriman Cepat</p>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pesanan diproses &amp; dikirim di hari yang sama.</p>
                </div>
            </div>

            <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200/70 dark:border-slate-800/80 shadow-sm flex items-center sm:items-start gap-3.5">
                <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-slate-800 dark:text-white text-xs sm:text-sm">Harga Bersaing</p>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5">Harga spesial untuk toko, agen, dan grosir.</p>
                </div>
            </div>

            <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200/70 dark:border-slate-800/80 shadow-sm flex items-center sm:items-start gap-3.5">
                <div class="p-2.5 bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 rounded-xl shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-slate-800 dark:text-white text-xs sm:text-sm">Bantuan Ramah</p>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5">Layanan bantuan siap menjawab pertanyaan Anda.</p>
                </div>
            </div>
        </section>

    </div>
</div>