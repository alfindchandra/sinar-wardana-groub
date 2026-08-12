<div class="space-y-6" x-data="{ activeTab: 'utama' }">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex text-sm text-slate-500 dark:text-slate-400 mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a wire:navigate href="{{ route('dashboard') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Home</a></li>
                    <li class="flex items-center"><svg class="w-4 h-4 mx-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <a wire:navigate href="{{ route('master-data.products.index') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Produk</a>
                    </li>
                    <li class="flex items-center"><svg class="w-4 h-4 mx-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> <span class="text-slate-700 dark:text-slate-200 font-medium">{{ $isEdit ? 'Edit' : 'Tambah' }}</span></li>
                </ol>
            </nav>
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white leading-tight">{{ $isEdit ? 'Edit Produk' : 'Tambah Produk Baru' }}</h2>
        </div>
        <a wire:navigate href="{{ route('master-data.products.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-medium text-sm text-slate-700 dark:text-slate-300 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <form wire:submit="save" class="space-y-6">

        <!-- Tabs -->
        <div class="glass-card p-1.5 inline-flex gap-1 flex-wrap">
            <button type="button" @click="activeTab = 'utama'" :class="activeTab === 'utama' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">Data Utama</button>
            <button type="button" @click="activeTab = 'harga'" :class="activeTab === 'harga' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">Harga &amp; Breakdown</button>
            <button type="button" @click="activeTab = 'varian'" :class="activeTab === 'varian' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Varian
                @if (count($variants) > 0)
                    <span class="ml-1 inline-flex items-center justify-center w-4 h-4 rounded-full bg-white/20 text-[10px]">{{ count($variants) }}</span>
                @endif
            </button>
            <button type="button" @click="activeTab = 'gambar'" :class="activeTab === 'gambar' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">Gambar</button>
            <button type="button" @click="activeTab = 'stok'" :class="activeTab === 'stok' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">Stok Gudang</button>
        </div>

        <!-- Tab: Data Utama -->
        <div x-show="activeTab === 'utama'" x-cloak class="glass-card p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Produk <span class="text-danger-500">*</span></label>
                    <input type="text" wire:model="name" placeholder="Contoh: TP Buncis" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                    @error('name') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">SKU</label>
                    <input type="text" wire:model="sku" placeholder="Kosongkan untuk otomatis" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                    @error('sku') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Barcode</label>
                    <input type="text" wire:model="barcode" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                    @error('barcode') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Kategori <span class="text-danger-500">*</span></label>
                    <select wire:model="category_id" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Supplier</label>
                    <select wire:model="supplier_id" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach ($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Merek</label>
                    <input type="text" wire:model="brand" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                    @error('brand') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Satuan Checkout <span class="text-danger-500">*</span></label>
                    <select wire:model.live="unit" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                        @foreach ($units as $u)
                            <option value="{{ $u->value }}">{{ $u->label() }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-xs text-slate-400">Satuan utama yang bisa dibeli pelanggan (mis. Sak/Dus).</p>
                    @error('unit') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Berat (Kg)</label>
                    <input type="number" step="0.01" wire:model="weight" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                    @error('weight') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Minimal Pembelian</label>
                    <input type="number" wire:model="min_purchase" min="1" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                    @error('min_purchase') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
                    <textarea wire:model="description" rows="3" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm"></textarea>
                    <p class="mt-1.5 text-xs text-slate-400">Breakdown harga per Bal/Pcs otomatis ditambahkan di halaman produk — tidak perlu ditulis manual di sini.</p>
                    @error('description') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-3 cursor-pointer sm:col-span-2">
                    <input type="checkbox" wire:model="is_active" class="rounded border-slate-300 dark:border-slate-600 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Produk aktif (ditampilkan di penjualan & portal)</span>
                </label>
            </div>
        </div>

        <!-- Tab: Harga & Breakdown -->
        <div x-show="activeTab === 'harga'" x-cloak class="space-y-6">
            <div class="glass-card p-6 space-y-5">
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Harga Checkout</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Satu-satunya harga yang bisa masuk ke keranjang/checkout pelanggan.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Harga Modal (HPP) <span class="text-danger-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">Rp</span>
                            <input type="number" step="0.01" wire:model="base_cost" class="block w-full pl-9 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                        </div>
                        @error('base_cost') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Harga Jual (Umum) <span class="text-danger-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">Rp</span>
                            <input type="number" step="0.01" wire:model.live="sell_price" class="block w-full pl-9 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                        </div>
                        <p class="mt-1.5 text-xs text-slate-400">Berlaku per {{ \App\Enums\ProductUnit::from($unit)->label() }} — ini yang tampil di halaman produk &amp; checkout.</p>
                        @error('sell_price') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Breakdown Otomatis -->
            <div class="glass-card p-6 space-y-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white">Breakdown Harga Otomatis (Info Saja)</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Tambahkan level breakdown sesuai kebutuhan — pilih sendiri satuan (Dus/Bal/Pcs/dst) &amp; jumlahnya per level, berurutan dari atas ke bawah.
                            Pelanggan tetap checkout per {{ \App\Enums\ProductUnit::from($unit)->label() }} — level di bawah ini <strong>tidak bisa dibeli terpisah</strong>.
                        </p>
                    </div>
                    <button type="button" wire:click="addBreakdown" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-primary-600 bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors shrink-0">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Level
                    </button>
                </div>

                @if (count($priceBreakdowns) === 0)
                    <div class="text-center py-8 text-slate-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3.75V15m-3 4.25V17M4 7h16M4 7v10a2 2 0 002 2h12a2 2 0 002-2V7M4 7l2-3h12l2 3"></path></svg>
                        <p class="text-sm">Belum ada breakdown. Klik "Tambah Level" kalau ingin menampilkan estimasi harga per Bal/Pcs/dll.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($priceBreakdowns as $index => $row)
                            <div class="grid grid-cols-2 sm:grid-cols-[1fr_1fr_auto] gap-3 items-end p-3 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700" wire:key="breakdown-{{ $index }}">
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">
                                        Satuan {{ $index === 0 ? '(level ke-1)' : '(di dalam ' . (\App\Enums\BreakdownUnit::tryFrom($priceBreakdowns[$index - 1]['unit'] ?? '')?->label() ?? 'level sebelumnya') . ')' }}
                                    </label>
                                    <select wire:model.live="priceBreakdowns.{{ $index }}.unit" class="block w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach ($breakdownUnits as $bu)
                                            <option value="{{ $bu->value }}">{{ $bu->label() }}</option>
                                        @endforeach
                                    </select>
                                    @error("priceBreakdowns.{$index}.unit") <p class="mt-1 text-[11px] text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Jumlah</label>
                                    <input type="number" min="1" wire:model.live="priceBreakdowns.{{ $index }}.qty" placeholder="Contoh: 8" class="block w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                    @error("priceBreakdowns.{$index}.qty") <p class="mt-1 text-[11px] text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <button type="button" wire:click="removeBreakdown({{ $index }})" class="p-2 text-slate-400 hover:text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-900/20 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($this->breakdownPreview)
                    <div class="rounded-xl border border-primary-100 dark:border-primary-900/40 bg-primary-50/60 dark:bg-primary-900/10 p-4">
                        <p class="text-xs font-semibold text-primary-700 dark:text-primary-400 mb-1">Preview di Halaman Produk</p>
                        <p class="text-sm text-slate-700 dark:text-slate-300">{{ $this->breakdownPreview }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tab: Varian -->
        <div x-show="activeTab === 'varian'" x-cloak class="glass-card p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Varian Wajib Pilih (Warna/Rasa)</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Jika diisi, pelanggan WAJIB memilih salah satu varian sebelum bisa menambahkan ke keranjang.
                        Kosongkan semua jika produk tidak punya varian.
                    </p>
                </div>
                <button type="button" wire:click="addVariant" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-primary-600 bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors shrink-0">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Varian
                </button>
            </div>

            @if (count($variants) === 0)
                <div class="text-center py-8 text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.486"></path></svg>
                    <p class="text-sm">Belum ada varian. Produk ini akan langsung bisa ditambahkan ke keranjang tanpa perlu memilih varian.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($variants as $index => $variant)
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 items-end p-3 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700" wire:key="variant-{{ $index }}">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Nama Varian</label>
                                <input type="text" wire:model="variants.{{ $index }}.name" placeholder="Contoh: Ungu" class="block w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                @error("variants.{$index}.name") <p class="mt-1 text-[11px] text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Selisih Harga</label>
                                <input type="number" step="0.01" wire:model="variants.{{ $index }}.extra_price" placeholder="0" class="block w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Stok</label>
                                <input type="number" min="0" wire:model="variants.{{ $index }}.stock" class="block w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <label class="flex items-center gap-1.5 cursor-pointer">
                                    <input type="checkbox" wire:model="variants.{{ $index }}.is_active" class="rounded border-slate-300 dark:border-slate-600 text-primary-600 focus:ring-primary-500 w-3.5 h-3.5">
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Aktif</span>
                                </label>
                                <button type="button" wire:click="removeVariant({{ $index }})" class="p-1.5 text-slate-400 hover:text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-900/20 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Tab: Gambar -->
        <div x-show="activeTab === 'gambar'" x-cloak class="glass-card p-6 space-y-5">
            <div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white mb-1">Foto Produk</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Format JPG/PNG, maksimal 3MB per gambar. Gambar akan otomatis di-resize ke 800x800px.</p>
            </div>

            @if ($isEdit && count($existingImages) > 0)
                <div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Gambar Tersimpan</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach ($existingImages as $image)
                            <div class="relative group rounded-xl overflow-hidden border-2 {{ $image->is_primary ? 'border-primary-500' : 'border-slate-200 dark:border-slate-700' }}">
                                <img src="{{ $image->url }}" class="w-full h-28 object-cover">
                                @if ($image->is_primary)
                                    <span class="absolute top-1.5 left-1.5 bg-primary-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Utama</span>
                                @endif
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    @if (! $image->is_primary)
                                        <button type="button" wire:click="setPrimaryImage({{ $image->id }})" title="Jadikan Utama" class="p-1.5 bg-white rounded-full text-primary-600 hover:bg-primary-50">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                        </button>
                                    @endif
                                    <button type="button" wire:click="deleteExistingImage({{ $image->id }})" wire:confirm="Hapus gambar ini?" title="Hapus" class="p-1.5 bg-white rounded-full text-danger-600 hover:bg-danger-50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ $isEdit ? 'Tambah Gambar Baru' : 'Upload Gambar' }}</p>
                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <span class="text-sm text-slate-500 dark:text-slate-400">Klik atau seret gambar ke sini</span>
                    <input type="file" wire:model="newImages" multiple accept="image/*" class="hidden">
                </label>
                <div wire:loading wire:target="newImages" class="text-xs text-primary-600 mt-2">Mengunggah gambar...</div>
                @error('newImages.*') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror

                @if (count($newImages) > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4">
                        @foreach ($newImages as $index => $img)
                            <div class="relative group rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700" wire:key="new-image-{{ $index }}">
                                <img src="{{ $img->temporaryUrl() }}" class="w-full h-28 object-cover">
                                <button type="button" wire:click="removeNewImage({{ $index }})" class="absolute top-1.5 right-1.5 p-1 bg-black/60 rounded-full text-white hover:bg-danger-600 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Tab: Stok Gudang -->
        <div x-show="activeTab === 'stok'" x-cloak class="glass-card p-6 space-y-5">
            <div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white mb-1">
                    {{ $isEdit ? 'Stok per Gudang' : 'Stok Awal per Gudang' }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    @if ($isEdit)
                        Perubahan jumlah stok dilakukan lewat modul Gudang/Stok (Barang Masuk, Keluar, Mutasi). Di sini Anda hanya bisa mengubah ambang batas stok minimum per gudang.
                    @else
                        Isi stok awal produk di masing-masing gudang saat produk pertama kali ditambahkan (opsional).
                    @endif
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse ($warehouseStocks as $index => $wh)
                    <div class="flex items-center justify-between gap-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700" wire:key="wh-stock-{{ $wh['warehouse_id'] }}">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate">{{ $wh['warehouse_name'] }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <div>
                                <label class="block text-[10px] text-slate-400 mb-0.5">Stok</label>
                                <input type="number" min="0" wire:model="warehouseStocks.{{ $index }}.stock" {{ $isEdit ? 'disabled' : '' }} class="w-20 rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs disabled:opacity-60 disabled:bg-slate-100 dark:disabled:bg-slate-800 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 mb-0.5">Min. Stok</label>
                                <input type="number" min="0" wire:model="warehouseStocks.{{ $index }}.min_stock" class="w-20 rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 sm:col-span-2">Belum ada gudang aktif. Tambahkan gudang terlebih dahulu di menu Master Data &rarr; Gudang.</p>
                @endforelse
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Ambang Batas Stok Minimum (Global)</label>
                <input type="number" min="0" wire:model="min_stock" class="block w-full max-w-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                <p class="mt-1.5 text-xs text-slate-400">Dipakai untuk badge "Stok Menipis" di daftar produk & dashboard.</p>
                @error('min_stock') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end gap-3 sticky bottom-4">
            <div class="glass-card p-3 flex items-center gap-3 shadow-lg">
                <a wire:navigate href="{{ route('master-data.products.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Batal
                </a>
                <button type="submit" wire:loading.attr="disabled" wire:target="save" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 shadow-sm shadow-primary-600/20 transition-colors disabled:opacity-60">
                    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Produk' }}
                </button>
            </div>
        </div>
    </form>
</div>
