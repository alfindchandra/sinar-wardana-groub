<div>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-1">
                <a href="#" class="hover:text-primary-600 transition-colors">Master Data</a>
                <span>/</span>
                <a href="{{ route('master-data.products.index') }}" class="hover:text-primary-600 transition-colors">Produk</a>
                <span>/</span>
                <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $isEdit ? 'Edit Produk' : 'Tambah Produk' }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $isEdit ? 'Edit Produk' : 'Tambah Produk Baru' }}</h1>
        </div>
    </x-slot>

    <form wire:submit.prevent="save" class="max-w-5xl">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Main Form Fields -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Info Dasar -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 pb-2 border-b border-slate-100 dark:border-slate-700">Informasi Dasar</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Produk <span class="text-danger-500">*</span></label>
                            <input wire:model="name" type="text" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            @error('name') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kategori <span class="text-danger-500">*</span></label>
                                <select wire:model="category_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Supplier <span class="text-danger-500">*</span></label>
                                <select wire:model="supplier_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Pilih Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">SKU</label>
                                <input wire:model="sku" type="text" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Barcode</label>
                                <input wire:model="barcode" type="text" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Brand/Merk</label>
                                <input wire:model="brand" type="text" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                            <textarea wire:model="description" rows="4" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Harga -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 pb-2 border-b border-slate-100 dark:border-slate-700">Harga</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Harga Modal (HPP) <span class="text-danger-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">Rp</span>
                                <input wire:model="base_cost" type="number" class="w-full pl-10 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            @error('base_cost') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Harga Jual (Umum) <span class="text-danger-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">Rp</span>
                                <input wire:model="sell_price" type="number" class="w-full pl-10 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            @error('sell_price') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Harga Distributor</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">Rp</span>
                                <input wire:model="distributor_price" type="number" class="w-full pl-10 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Harga Agen</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">Rp</span>
                                <input wire:model="agent_price" type="number" class="w-full pl-10 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Harga Toko</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">Rp</span>
                                <input wire:model="store_price" type="number" class="w-full pl-10 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Right Column: Settings & Image -->
            <div class="space-y-6">
                
                <!-- Stok & Satuan -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 pb-2 border-b border-slate-100 dark:border-slate-700">Stok & Satuan</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Satuan Dasar <span class="text-danger-500">*</span></label>
                            <select wire:model="unit" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Pilih Satuan</option>
                                @foreach($units as $unitEnum)
                                    <option value="{{ is_object($unitEnum) ? $unitEnum->value : $unitEnum }}">{{ is_object($unitEnum) ? $unitEnum->name : $unitEnum }}</option>
                                @endforeach
                            </select>
                            @error('unit') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Batas Stok Minimum <span class="text-danger-500">*</span></label>
                            <input wire:model="min_stock" type="number" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                            @error('min_stock') <span class="text-xs text-danger-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Minimum Pembelian</label>
                            <input wire:model="min_purchase" type="number" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Berat (Gram)</label>
                            <input wire:model="weight" type="number" step="0.01" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Isi Per Satuan (Opsional)</label>
                            <input wire:model="content_per_unit" type="number" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                </div>

                <!-- Gambar -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 pb-2 border-b border-slate-100 dark:border-slate-700">Gambar</h2>
                    
                    <div class="space-y-4">
                        <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-6 text-center cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <input wire:model="newImages" type="file" multiple class="hidden" id="product-images" accept="image/*">
                            <label for="product-images" class="cursor-pointer">
                                <svg class="w-10 h-10 mx-auto text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-sm text-slate-600 dark:text-slate-300 font-medium block">Klik untuk upload gambar</span>
                                <span class="text-xs text-slate-500 block mt-1">PNG, JPG up to 2MB</span>
                            </label>
                        </div>
                        @error('newImages.*') <span class="text-xs text-danger-500">{{ $message }}</span> @enderror

                        @if($newImages)
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($newImages as $image)
                                    <div class="relative rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 aspect-square">
                                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="is_active" type="checkbox" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500 w-5 h-5">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Produk Aktif</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <a href="{{ route('master-data.products.index') }}" class="px-6 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl transition-colors">Batal</a>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-colors shadow-lg shadow-primary-600/20">
                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Produk' }}
            </button>
        </div>
    </form>
</div>
