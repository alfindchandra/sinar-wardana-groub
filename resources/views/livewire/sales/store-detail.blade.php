<div class="max-w-lg mx-auto pb-20">
    <!-- Header Image -->
    <div class="w-full h-64 bg-slate-200 dark:bg-slate-800 relative">
        @if($customer->store_photo)
            <img src="{{ Storage::url($customer->store_photo) }}" alt="{{ $customer->store_name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-20 h-20 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-4">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white backdrop-blur-sm mb-2">
                {{ ucfirst($customer->customer_type) }}
            </span>
            <h1 class="text-2xl font-bold text-white">{{ $customer->store_name }}</h1>
            <p class="text-slate-200 text-sm">{{ $customer->code }}</p>
        </div>
    </div>

    <div class="px-4 py-4 space-y-4 -mt-4 relative z-10">
        
        <!-- Quick Action -->
        <div class="glass-card p-4 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Omset</p>
                <p class="text-lg font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($totalOmset, 0, ',', '.') }}</p>
            </div>
            <button wire:click="$navigate('{{ route('sales.orders.create', ['customer' => $customer->id]) }}')" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-xl shadow-sm transition-colors duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Order
            </button>
        </div>

        <!-- Info Card -->
        <div class="glass-card p-4 rounded-2xl">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200 mb-3 uppercase tracking-wider">Informasi Toko</h3>
            
            <div class="space-y-3">
                <div class="flex">
                    <div class="flex-shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $customer->owner_name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pemilik</p>
                    </div>
                </div>
                
                <div class="flex">
                    <div class="flex-shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $customer->phone ?: '-' }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Telepon</p>
                    </div>
                </div>

                <div class="flex">
                    <div class="flex-shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900 dark:text-white">
                            {{ $customer->address ?: '-' }}
                            @if($customer->city || $customer->area)
                                <br><span class="text-xs text-slate-500">{{ $customer->area }} {{ $customer->city ? ', '.$customer->city : '' }}</span>
                            @endif
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Alamat</p>
                    </div>
                </div>

                @if($customer->credit_limit > 0)
                <div class="flex">
                    <div class="flex-shrink-0 w-8 flex justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900 dark:text-white">Rp {{ number_format($customer->credit_limit, 0, ',', '.') }} ({{ $customer->payment_term_days }} Hari)</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Limit Kredit & TOP</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Map Location -->
        @if($customer->latitude && $customer->longitude)
        <div class="glass-card p-4 rounded-2xl">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200 mb-3 uppercase tracking-wider">Lokasi Peta</h3>
            
            <div wire:ignore id="map" class="h-48 w-full rounded-xl z-0"></div>

            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $customer->latitude }},{{ $customer->longitude }}" target="_blank" class="mt-3 w-full flex items-center justify-center py-2 px-4 border border-slate-300 dark:border-slate-600 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <svg class="w-4 h-4 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                Petunjuk Arah (Google Maps)
            </a>
        </div>
        @endif

        <!-- Recent Orders -->
        <div class="glass-card p-4 rounded-2xl">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Order Terakhir</h3>
                <a href="{{ route('sales.orders.index') }}" wire:navigate class="text-xs text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
            </div>
            
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-3 border border-slate-100 dark:border-slate-800 cursor-pointer hover:border-primary-300 transition-colors" wire:click="$navigate('{{ route('sales.orders.show', $order->id) }}')">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $order->so_number }}</span>
                        @php
                            $statusColors = [
                                'draft' => 'bg-slate-100 text-slate-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'processing' => 'bg-warning-100 text-warning-800',
                                'shipped' => 'bg-indigo-100 text-indigo-800',
                                'completed' => 'bg-success-100 text-success-800',
                                'cancelled' => 'bg-danger-100 text-danger-800',
                            ];
                            $statusColor = $statusColors[$order->status] ?? 'bg-slate-100 text-slate-800';
                            
                            $statusLabels = [
                                'draft' => 'Draft',
                                'confirmed' => 'Dikonfirmasi',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Batal',
                            ];
                            $statusLabel = $statusLabels[$order->status] ?? ucfirst($order->status);
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium uppercase tracking-wider {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <div class="flex justify-between items-end mt-2">
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada riwayat order.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    @if($customer->latitude && $customer->longitude)
    <!-- Leaflet JS & CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const lat = {{ $customer->latitude }};
            const lng = {{ $customer->longitude }};
            
            setTimeout(() => {
                if (document.getElementById('map')) {
                    const map = L.map('map').setView([lat, lng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);
                    
                    const marker = L.marker([lat, lng]).addTo(map);
                    marker.bindPopup("<b>{{ $customer->store_name }}</b><br>{{ $customer->address }}").openPopup();
                }
            }, 100);
        });
    </script>
    @endif
</div>
