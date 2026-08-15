<div x-data x-on:copy-nota.window="
    navigator.clipboard.writeText($event.detail.text).then(() => {
        $dispatch('notify', {message: 'Nota berhasil disalin ke clipboard!', type: 'success'});
        $wire.dispatch('toast', {type: 'success', message: 'Nota berhasil disalin!'});
    }).catch(() => {
        // Fallback: create textarea
        const ta = document.createElement('textarea');
        ta.value = $event.detail.text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        $wire.dispatch('toast', {type: 'success', message: 'Nota berhasil disalin!'});
    });
">
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
            $statusLabels = [
                'draft' => 'Draft',
                'confirmed' => 'Dikonfirmasi',
                'processing' => 'Diproses',
                'shipped' => 'Dikirim',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
            ];
        @endphp
        <div class="p-3 rounded-lg border {{ $statusColors }} flex justify-center items-center gap-2 shadow-sm">
            <span class="text-sm font-bold uppercase tracking-wider">{{ $statusLabels[$salesOrder->status] ?? ucfirst($salesOrder->status) }}</span>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            @if($salesOrder->status === 'draft')
                <a href="{{ route('sales.orders.edit', $salesOrder) }}" wire:navigate class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 text-warning-700 dark:text-warning-400 text-sm font-medium hover:bg-warning-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path></svg>
                    Edit Order
                </a>
            @endif
            <button wire:click="copyNota" class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 text-primary-700 dark:text-primary-400 text-sm font-medium hover:bg-primary-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9.75a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"></path></svg>
                Copy Nota
            </button>
        </div>

        <!-- Store Info -->
        <div class="glass-card p-4">
            <div class="flex items-start gap-4">
                @if($salesOrder->customer?->store_photo)
                    <img src="{{ Storage::url($salesOrder->customer->store_photo) }}" class="w-16 h-16 rounded-lg object-cover border border-slate-100">
                @else
                    <div class="w-16 h-16 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center border border-slate-200 dark:border-slate-700">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"></path></svg>
                    </div>
                @endif
                <div class="flex-1">
                    <h2 class="font-bold text-slate-900 dark:text-white">{{ $salesOrder->customer?->store_name }}</h2>
                    @if($salesOrder->customer?->area)
                        <p class="text-xs text-primary-600 dark:text-primary-400 font-medium mt-0.5">{{ $salesOrder->customer->area }}</p>
                    @endif
                    <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">{{ $salesOrder->customer?->address }}</p>
                    @if($salesOrder->customer?->phone)
                        <p class="text-xs text-slate-500 mt-0.5">📞 {{ $salesOrder->customer->phone }}</p>
                    @endif
                    
                    @if($salesOrder->customer?->latitude && $salesOrder->customer?->longitude)
                    <div x-data="{ showMap: false }" class="mt-2">
                        <button @click="showMap = !showMap" class="text-xs text-primary-600 font-medium flex items-center gap-1 hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"></path></svg>
                            Lihat Maps
                        </button>
                        
                        <div x-show="showMap" x-transition class="mt-2">
                            <div wire:ignore
                                 x-data="{
                                     map: null,
                                     initMap() {
                                         if (typeof L === 'undefined') return;
                                         this.map = L.map($refs.detailMap, { zoomControl: false }).setView([{{ $salesOrder->customer->latitude }}, {{ $salesOrder->customer->longitude }}], 15);
                                         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(this.map);
                                         L.marker([{{ $salesOrder->customer->latitude }}, {{ $salesOrder->customer->longitude }}]).addTo(this.map);
                                         setTimeout(() => this.map.invalidateSize(), 200);
                                     }
                                 }"
                                 x-init="$watch('$el.offsetParent', v => { if (v && !map) initMap(); })"
                                 x-intersect.once="initMap()"
                                 class="rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 h-32 w-full">
                                <div x-ref="detailMap" class="w-full h-full"></div>
                            </div>
                        </div>
                    </div>
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
