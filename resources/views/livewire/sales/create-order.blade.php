<div>
    <div class="space-y-4 pb-20">
        <!-- Customer Section -->
        <div class="glass-card p-4">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Toko / Pelanggan</h2>
            
            @if($customer_id)
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        @if($customer_photo)
                            <img src="{{ Storage::url($customer_photo) }}" alt="Toko" class="w-12 h-12 rounded-lg object-cover">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                <x-heroicon-o-storefront class="w-6 h-6 text-slate-400" />
                            </div>
                        @endif
                        <div>
                            <h3 class="font-medium text-slate-900 dark:text-white">{{ $customer_name }}</h3>
                        </div>
                    </div>
                    <button wire:click="clearCustomer" class="text-sm text-danger-600 hover:text-danger-700 p-1">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>
                
                @if($customer_lat && $customer_lng)
                <div class="mt-3 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 h-32" wire:ignore>
                    <div id="customer-map" class="w-full h-full"></div>
                </div>
                <script>
                    document.addEventListener('livewire:initialized', () => {
                        const initMap = () => {
                            if (!document.getElementById('customer-map')) return;
                            const map = L.map('customer-map', {
                                zoomControl: false,
                                dragging: false,
                                scrollWheelZoom: false
                            }).setView([{{ $customer_lat }}, {{ $customer_lng }}], 15);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                            L.marker([{{ $customer_lat }}, {{ $customer_lng }}]).addTo(map);
                        };
                        setTimeout(initMap, 100);
                    });
                </script>
                @endif
            @else
                <div class="relative">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" />
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama toko..." 
                        class="w-full pl-10 pr-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-800 dark:text-white">
                </div>
                
                @if(strlen($search) >= 2)
                <div class="mt-2 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden divide-y divide-slate-100 dark:divide-slate-700 max-h-48 overflow-y-auto">
                    @forelse($this->customers as $cust)
                        <button wire:click="selectCustomer({{ $cust->id }})" class="w-full text-left px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-800/50 flex justify-between items-center">
                            <div>
                                <div class="font-medium text-slate-900 dark:text-white text-sm">{{ $cust->store_name }}</div>
                                <div class="text-xs text-slate-500">{{ $cust->code }}</div>
                            </div>
                            <x-heroicon-o-chevron-right class="w-4 h-4 text-slate-400" />
                        </button>
                    @empty
                        <div class="px-4 py-3 text-sm text-center text-slate-500">Tidak ditemukan</div>
                    @endforelse
                </div>
                @endif
            @endif
        </div>

        <!-- Order Detail Section -->
        <div class="glass-card p-4">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Detail Order</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Gudang</label>
                    <select wire:model="warehouse_id" class="w-full border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-800 dark:text-white">
                        <option value="">Pilih Gudang...</option>
                        @foreach($this->warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </select>
                    @error('warehouse_id') <span class="text-xs text-danger-600">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Tipe Pembayaran</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" wire:model="payment_type" value="cash" class="text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Tunai (Cash)</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" wire:model="payment_type" value="tempo" class="text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Kredit (Tempo)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Section -->
        <div class="glass-card p-4">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Produk</h2>
            
            <div class="relative mb-4">
                <x-heroicon-o-magnifying-glass class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" />
                <input type="text" wire:model.live.debounce.300ms="productSearch" placeholder="Cari produk..." 
                    class="w-full pl-10 pr-4 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-800 dark:text-white">
                
                @if(strlen($productSearch) >= 2)
                <div class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg overflow-hidden divide-y divide-slate-100 dark:divide-slate-700 max-h-48 overflow-y-auto">
                    @forelse($this->products as $prod)
                        <button wire:click="addProduct({{ $prod->id }})" class="w-full text-left px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-800 flex justify-between items-center">
                            <div>
                                <div class="font-medium text-slate-900 dark:text-white text-sm">{{ $prod->name }}</div>
                                <div class="text-xs text-primary-600 font-medium">Rp {{ number_format($prod->sell_price, 0, ',', '.') }}</div>
                            </div>
                            <x-heroicon-o-plus-circle class="w-5 h-5 text-primary-500" />
                        </button>
                    @empty
                        <div class="px-4 py-3 text-sm text-center text-slate-500">Produk tidak ditemukan</div>
                    @endforelse
                </div>
                @endif
            </div>

            <div class="space-y-3">
                @foreach($items as $index => $item)
                    <div class="flex flex-col gap-2 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-100 dark:border-slate-700">
                        <div class="flex justify-between items-start">
                            <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $item['product_name'] }}</div>
                            <button wire:click="removeProduct({{ $index }})" class="text-danger-500 p-0.5 hover:bg-danger-50 rounded">
                                <x-heroicon-o-trash class="w-4 h-4" />
                            </button>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <div class="flex items-center gap-2">
                                <input type="number" wire:model.lazy="items.{{ $index }}.qty" wire:change="updateQty({{ $index }}, $event.target.value)" 
                                    class="w-16 text-center text-sm border-slate-200 dark:border-slate-600 rounded-md py-1 px-2 dark:bg-slate-700 dark:text-white" min="0.1" step="0.1">
                                <span class="text-xs text-slate-500">x Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                            </div>
                            <div class="text-sm font-semibold text-slate-900 dark:text-white">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endforeach
                @if(count($items) === 0)
                    <div class="text-center py-4 text-sm text-slate-500">Belum ada produk ditambahkan</div>
                @endif
            </div>
            @error('items') <span class="text-xs text-danger-600 block mt-2">{{ $message }}</span> @enderror
        </div>

        <!-- Notes -->
        <div class="glass-card p-4">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Catatan Tambahan</h2>
            <textarea wire:model="notes" rows="2" class="w-full text-sm border-slate-200 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-800 dark:text-white" placeholder="Catatan pesanan (opsional)..."></textarea>
        </div>
    </div>

    <!-- Bottom Fixed Actions -->
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 max-w-lg mx-auto z-40">
        <div class="flex justify-between items-center mb-3">
            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Pembayaran</span>
            <span class="text-lg font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
        </div>
        <button wire:click="submit" class="w-full flex justify-center items-center gap-2 bg-primary-600 text-white font-medium py-3 rounded-xl hover:bg-primary-700 transition shadow-lg shadow-primary-600/30">
            <x-heroicon-o-check-circle class="w-5 h-5" />
            Simpan Order
        </button>
    </div>
</div>
