<div>

 
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 space-y-14">

        <!-- Promo -->
        @if ($promos->isNotEmpty())
            <section>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach ($promos as $promo)
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-warning-500 to-danger-500 p-5 text-white shadow-lg">
                            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-white/10 rounded-full"></div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-white/20 text-[10px] font-bold mb-2">PROMO</span>
                            <h3 class="font-bold text-lg leading-snug">{{ $promo->title }}</h3>
                            <p class="text-xs text-white/80 mt-1 line-clamp-2">{{ $promo->description }}</p>
                            <p class="text-[11px] text-white/70 mt-3">Berlaku s.d. {{ $promo->end_date->translatedFormat('d M Y') }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Kategori -->
        @if ($categories->isNotEmpty())
            <section>
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Belanja per Kategori</h2>
                    <a href="{{ route('shop.products') }}" wire:navigate class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">Lihat Semua &rarr;</a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4">
                    @foreach ($categories as $cat)
                        <a href="{{ route('shop.products', ['category' => $cat->id]) }}" wire:navigate class="glass-card p-4 flex flex-col items-center text-center gap-2 hover:-translate-y-1 transition-all duration-300">
                            <div class="w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center font-bold text-sm">
                                {{ $cat->icon}}
                            </div>
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300 line-clamp-1">{{ $cat->name }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Produk Terbaru -->
        <section>
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Produk Terbaru</h2>
                <a href="{{ route('shop.products') }}" wire:navigate class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">Lihat Semua &rarr;</a>
            </div>

            @if ($newestProducts->isEmpty())
                <div class="glass-card p-10 text-center text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Belum ada produk yang tersedia saat ini.
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach ($newestProducts as $product)
                        <x-shop.product-card :product="$product" wire:key="home-product-{{ $product->id }}" />
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Trust badges -->
        <section class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="glass-card p-5 flex items-start gap-4">
                <div class="p-2.5 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-800 dark:text-white text-sm">Pengiriman Cepat</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pesanan diproses &amp; dikirim di hari yang sama.</p>
                </div>
            </div>
            <div class="glass-card p-5 flex items-start gap-4">
                <div class="p-2.5 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-800 dark:text-white text-sm">Harga Bersaing</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Harga khusus untuk toko, agen, dan distributor.</p>
                </div>
            </div>
            <div class="glass-card p-5 flex items-start gap-4">
                <div class="p-2.5 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-xl shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-800 dark:text-white text-sm">Support Ramah</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Tim kami siap bantu kapan saja Anda butuhkan.</p>
                </div>
            </div>
        </section>
    </div>
</div>
