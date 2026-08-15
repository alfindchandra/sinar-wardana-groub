<div>
    <div class="space-y-4 pb-24">
        <!-- Customer Section -->
        <div class="glass-card p-4">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Toko / Pelanggan</h2>
            
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    @if($customer_photo)
                        <img src="{{ Storage::url($customer_photo) }}" alt="Toko" class="w-12 h-12 rounded-lg object-cover">
                    @else
                        <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"></path></svg>
                        </div>
                    @endif
                    <div>
                        <h3 class="font-medium text-slate-900 dark:text-white">{{ $customer_name }}</h3>
                    </div>
                </div>
            </div>
            
            @if($customer_lat && $customer_lng)
                <!-- Container Leaflet Map berbasis Alpine.js -->
                <div class="mt-3 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 h-36 relative z-0" 
                     wire:ignore 
                     x-data="{
                         map: null,
                         lat: {{ (float) $customer_lat }},
                         lng: {{ (float) $customer_lng }},
                         initMap() {
                             if (typeof L === 'undefined') return;
                             if (this.map) {
                                 this.map.remove();
                             }
                             this.map = L.map($refs.mapContainer, {
                                 zoomControl: false,
                                 dragging: false,
                                 scrollWheelZoom: false,
                                 touchZoom: false,
                                 doubleClickZoom: false
                             }).setView([this.lat, this.lng], 15);

                             L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                 maxZoom: 19
                             }).addTo(this.map);

                             L.marker([this.lat, this.lng]).addTo(this.map);

                             setTimeout(() => {
                                 this.map.invalidateSize();
                             }, 200);
                         }
                     }" 
                     x-init="initMap()">
                    <div x-ref="mapContainer" class="w-full h-full"></div>
                </div>
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
                <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path></svg>
                <input type="text" wire:model.live.debounce.300ms="productSearch" placeholder="Cari produk..." 
                    class="w-full pl-10 pr-4 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-800 dark:text-white">
                
                @if(strlen($productSearch) >= 2)
                <div class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg overflow-hidden divide-y divide-slate-100 dark:divide-slate-700 max-h-48 overflow-y-auto">
                    @forelse($this->products as $prod)
                        <button type="button" wire:click="addProduct({{ $prod->id }})" class="w-full text-left px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-800 flex justify-between items-center">
                            <div>
                                <div class="font-medium text-slate-900 dark:text-white text-sm">{{ $prod->name }}</div>
                                <div class="text-xs text-primary-600 font-medium">Rp {{ number_format($prod->sell_price, 0, ',', '.') }}</div>
                            </div>
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
                            <button type="button" wire:click="removeProduct({{ $index }})" class="text-danger-500 p-0.5 hover:bg-danger-50 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                            </button>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <div class="flex items-center gap-2">
                                <input type="number" wire:model.lazy="items.{{ $index }}.qty" wire:change="updateQty({{ $index }}, $event.target.value)" 
                                    class="w-16 text-center text-sm border-slate-200 dark:border-slate-600 rounded-md py-1 px-2 dark:bg-slate-700 dark:text-white" min="1" step="1">
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
        <button type="button" wire:click="submit" class="w-full flex justify-center items-center gap-2 bg-primary-600 text-white font-medium py-3 rounded-xl hover:bg-primary-700 transition shadow-lg shadow-primary-600/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Simpan Perubahan
        </button>
    </div>
</div>
