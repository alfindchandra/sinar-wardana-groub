<div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8 py-4 sm:py-8">

    <!-- Breadcrumb -->
    <nav class="flex text-xs sm:text-sm text-slate-500 dark:text-slate-400 mb-4 overflow-x-auto whitespace-nowrap pb-1" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1.5">
            <li>
                <a href="{{ route('shop.home') }}" wire:navigate class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Beranda</a>
            </li>
            <li class="flex items-center">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mx-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg> 
                <span class="text-slate-700 dark:text-slate-200 font-medium truncate">Semua Produk</span>
            </li>
        </ol>
    </nav>

    <!-- Mobile Horizontal Category Chips (Hanya muncul di Layar HP/Tablet) -->
    <div class="lg:hidden mb-4 -mx-3 px-3 overflow-x-auto scrollbar-none flex items-center gap-2 pb-1">
        <button 
            type="button" 
            wire:click="selectCategory('')" 
            class="shrink-0 px-3.5 py-1.5 rounded-full text-xs font-medium transition-all shadow-sm {{ $categoryFilter === '' ? 'bg-primary-600 text-white shadow-primary-500/25 ring-2 ring-primary-600/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}"
        >
            Semua
        </button>
        @foreach ($categories as $cat)
            <button 
                type="button" 
                wire:click="selectCategory('{{ $cat->id }}')" 
                class="shrink-0 flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-medium transition-all shadow-sm {{ (string) $categoryFilter === (string) $cat->id ? 'bg-primary-600 text-white shadow-primary-500/25 ring-2 ring-primary-600/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}"
            >
                <span>{{ $cat->name }}</span>
               
            </button>
        @endforeach
    </div>

    <div class="flex flex-col lg:flex-row gap-6">

        <!-- Sidebar Kategori Desktop (Hanya muncul di Desktop) -->
        <aside class="hidden lg:block lg:w-64 shrink-0">
            <div class="glass-card p-4 sticky top-24 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 bg-white/70 dark:bg-slate-900/70 backdrop-blur-md shadow-sm">
                <div class="flex items-center justify-between mb-3 px-1">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white tracking-wide">Kategori</h3>
                    <span class="text-xs text-slate-400">{{ count($categories) }} total</span>
                </div>
                <div class="space-y-1">
                    <button 
                        type="button" 
                        wire:click="selectCategory('')" 
                        class="w-full text-left px-3.5 py-2.5 rounded-xl text-sm transition-all flex items-center justify-between {{ $categoryFilter === '' ? 'bg-primary-500/10 text-primary-600 dark:text-primary-400 font-semibold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                    >
                        <span>Semua Kategori</span>
                    </button>
                    @foreach ($categories as $cat)
                        <button 
                            type="button" 
                            wire:click="selectCategory('{{ $cat->id }}')" 
                            class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all {{ (string) $categoryFilter === (string) $cat->id ? 'bg-primary-500/10 text-primary-600 dark:text-primary-400 font-semibold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                        >
                            <span class="truncate pr-2">{{ $cat->name }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-md font-medium {{ (string) $categoryFilter === (string) $cat->id ? 'bg-primary-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}">
                                {{ $cat->products_count }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- Main Product Section -->
        <div class="flex-1 min-w-0">

            <!-- Search & Filter Toolbar -->
            <div class="p-3 sm:p-4 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 sm:gap-3 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 bg-white/70 dark:bg-slate-900/70 backdrop-blur-md shadow-sm">
                
                <!-- Search Input -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-4 w-4 sm:h-4.5 sm:w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.400ms="search" 
                        placeholder="Cari nama produk..." 
                        class="block w-full pl-10 pr-4 py-2 sm:py-2.5 text-xs sm:text-sm border-slate-200 dark:border-slate-700/80 rounded-xl bg-slate-50/80 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-shadow"
                    >
                </div>

                <!-- Sort Dropdown -->
                <div class="flex items-center gap-2">
                    <label class="text-xs text-slate-400 shrink-0 hidden sm:inline">Urutkan:</label>
                    <select 
                        wire:model.live="sort" 
                        class="w-full sm:w-auto py-2 sm:py-2.5 pl-3 pr-8 text-xs sm:text-sm rounded-xl border-slate-200 dark:border-slate-700/80 bg-slate-50/80 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500"
                    >
                        <option value="terbaru">Terbaru</option>
                        <option value="harga_rendah">Harga Terendah</option>
                        <option value="harga_tinggi">Harga Tertinggi</option>
                        <option value="nama">Nama A-Z</option>
                    </select>
                </div>
            </div>

            <!-- Product Grid / Empty State -->
            @if ($products->isEmpty())
                <div class="p-10 sm:p-16 text-center text-slate-400 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 bg-white/40 dark:bg-slate-900/40">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-500">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <p class="font-semibold text-slate-700 dark:text-slate-300 text-sm sm:text-base">Produk tidak ditemukan</p>
                    <p class="text-xs sm:text-sm mt-1 text-slate-500 dark:text-slate-400">Coba gunakan kata kunci lain atau pilih kategori berbeda.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-2.5 sm:gap-4">
                    @foreach ($products as $product)
                        <x-shop.product-card :product="$product" wire:key="catalog-product-{{ $product->id }}" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6 sm:mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>