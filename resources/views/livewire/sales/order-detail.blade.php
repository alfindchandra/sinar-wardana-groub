<div>
    <div class="space-y-4 pb-12">
        <!-- Status Banner -->
        @php
            $statusColors = match($salesOrder->status) {
                'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                'processing' => 'bg-warning-50 text-warning-700 border-warning-200',
                'shipped' => 'bg-purple-50 text-purple-700 border-purple-200',
                'completed' => 'bg-success-50 text-success-700 border-success-200',
                'cancelled' => 'bg-danger-50 text-danger-700 border-danger-200',
                default => 'bg-slate-100 text-slate-700 border-slate-200'
            };
        @endphp
        <div class="p-3 rounded-lg border {{ $statusColors }} flex justify-center items-center gap-2 shadow-sm">
            <span class="text-sm font-bold uppercase tracking-wider">{{ $salesOrder->status }}</span>
        </div>

        <!-- Store Info -->
        <div class="glass-card p-4">
            <div class="flex items-start gap-4">
                @if($salesOrder->customer?->store_photo)
                    <img src="{{ Storage::url($salesOrder->customer->store_photo) }}" class="w-16 h-16 rounded-lg object-cover border border-slate-100">
                @else
                    <div class="w-16 h-16 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center border border-slate-200 dark:border-slate-700">
                        <x-heroicon-o-storefront class="w-8 h-8 text-slate-400" />
                    </div>
                @endif
                <div class="flex-1">
                    <h2 class="font-bold text-slate-900 dark:text-white">{{ $salesOrder->customer?->store_name }}</h2>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $salesOrder->customer?->address }}</p>
                    
                    @if($salesOrder->customer?->latitude && $salesOrder->customer?->longitude)
                    <div x-data="{ showMap: false }" class="mt-2">
                        <button @click="showMap = !showMap" class="text-xs text-primary-600 font-medium flex items-center gap-1 hover:underline">
                            <x-heroicon-o-map class="w-3.5 h-3.5" />
                            Lihat Maps
                        </button>
                        
                        <div x-show="showMap" class="mt-2 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 h-32 w-full" x-transition>
                            <div id="detail-map-{{ $salesOrder->id }}" class="w-full h-full"></div>
                        </div>
                    </div>
                    
                    <script>
                        document.addEventListener('livewire:initialized', () => {
                            let mapInitialized = false;
                            window.addEventListener('alpine:init', () => {
                                Alpine.effect(() => {
                                    const showMapBtn = document.querySelector('[x-data]').__x.$data.showMap;
                                    if (showMapBtn && !mapInitialized) {
                                        setTimeout(() => {
                                            const mapEl = document.getElementById('detail-map-{{ $salesOrder->id }}');
                                            if(!mapEl) return;
                                            const map = L.map(mapEl, { zoomControl: false }).setView([{{ $salesOrder->customer->latitude }}, {{ $salesOrder->customer->longitude }}], 15);
                                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                                            L.marker([{{ $salesOrder->customer->latitude }}, {{ $salesOrder->customer->longitude }}]).addTo(map);
                                            mapInitialized = true;
                                        }, 100);
                                    }
                                });
                            });
                        });
                    </script>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Info -->
        <div class="glass-card p-4">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Informasi Pesanan</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Nomor SO</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ $salesOrder->so_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Tanggal Order</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($salesOrder->order_date)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Tipe Pembayaran</span>
                    <span class="font-medium text-slate-900 dark:text-white uppercase">{{ $salesOrder->payment_type }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Jatuh Tempo</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($salesOrder->due_date)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Gudang Asal</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ $salesOrder->warehouse?->name ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="glass-card overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Daftar Produk</h3>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($salesOrder->items as $item)
                <div class="p-4">
                    <div class="text-sm font-medium text-slate-900 dark:text-white mb-1">{{ $item->product?->name ?? 'Produk Dihapus' }}</div>
                    <div class="flex justify-between items-center text-xs">
                        <div class="text-slate-500">
                            {{ $item->qty }} {{ $item->unit }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                        </div>
                        <div class="font-bold text-slate-900 dark:text-white">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Notes -->
        @if($salesOrder->notes)
        <div class="glass-card p-4">
            <h3 class="text-xs font-medium text-slate-500 mb-1">Catatan</h3>
            <p class="text-sm text-slate-900 dark:text-white">{{ $salesOrder->notes }}</p>
        </div>
        @endif

        <!-- Totals -->
        <div class="glass-card p-4 space-y-2">
            <div class="flex justify-between text-sm text-slate-500">
                <span>Subtotal</span>
                <span>Rp {{ number_format($salesOrder->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($salesOrder->discount > 0)
            <div class="flex justify-between text-sm text-danger-500">
                <span>Diskon</span>
                <span>- Rp {{ number_format($salesOrder->discount, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($salesOrder->tax > 0)
            <div class="flex justify-between text-sm text-slate-500">
                <span>Pajak (PPN)</span>
                <span>Rp {{ number_format($salesOrder->tax, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($salesOrder->shipping_cost > 0)
            <div class="flex justify-between text-sm text-slate-500">
                <span>Ongkir</span>
                <span>Rp {{ number_format($salesOrder->shipping_cost, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="pt-2 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center mt-2">
                <span class="font-bold text-slate-900 dark:text-white">Grand Total</span>
                <span class="text-lg font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($salesOrder->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
