<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <nav class="flex text-sm text-slate-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li><a href="{{ route('shop.home') }}" wire:navigate class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Beranda</a></li>
            <li class="flex items-center"><svg class="w-4 h-4 mx-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> <span class="text-slate-700 dark:text-slate-200 font-medium">Semua Produk</span></li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row gap-6">

        <!-- Sidebar Kategori -->
        <aside class="lg:w-64 shrink-0">
            <div class="glass-card p-4 sticky top-24">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-3">Kategori</h3>
                <div class="space-y-1">
                    <button type="button" wire:click="selectCategory('')" class="w-full text-left px-3 py-2 rounded-lg text-sm transition-colors {{ $categoryFilter === '' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 font-medium' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        Semua Kategori
                    </button>
                    @foreach ($categories as $cat)
                        <button type="button" wire:click="selectCategory('{{ $cat->id }}')" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ (string) $categoryFilter === (string) $cat->id ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 font-medium' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            <span class="truncate">{{ $cat->name }}</span>
                            <span class="text-xs text-slate-400">{{ $cat->products_count }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 min-w-0">

            <!-- Toolbar -->
            <div class="glass-card p-4 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4.5 w-4.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari produk di kategori ini..." class="block w-full pl-10 pr-4 py-2.5 border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 text-sm">
                </div>
                <select wire:model.live="sort" class="rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                    <option value="terbaru">Terbaru</option>
                    <option value="harga_rendah">Harga Terendah</option>
                    <option value="harga_tinggi">Harga Tertinggi</option>
                    <option value="nama">Nama A-Z</option>
                </select>
            </div>

            @if ($products->isEmpty())
                <div class="glass-card p-16 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <p class="font-medium">Produk tidak ditemukan</p>
                    <p class="text-sm mt-1">Coba kata kunci atau kategori lain.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($products as $product)
                        <x-shop.product-card :product="$product" wire:key="catalog-product-{{ $product->id }}" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
