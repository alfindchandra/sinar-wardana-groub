<div class="space-y-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Peta Toko</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Pemetaan lokasi toko pelanggan</p>
        </div>
        <a href="{{ Route::has('admin.stores.index') ? route('admin.stores.index') : (Route::has('sales.stores.index') ? route('sales.stores.index') : '#') }}" 
           wire:navigate 
           class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
            Daftar Toko
        </a>
    </div>

    <div class="glass-card p-4 rounded-xl flex flex-col sm:flex-row gap-4 bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex-1">
            <select wire:model.live="areaFilter" class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Area</option>
                @foreach($areas as $area)
                    <option value="{{ $area }}">{{ $area }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <select wire:model.live="salesFilter" class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Sales</option>
                @foreach($salesPersons as $sp)
                    <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="glass-card overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm relative z-0">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <div 
            x-data="storeMapHandler()" 
            x-init="initMap()"
            class="w-full bg-slate-100 dark:bg-slate-800 relative"
            style="height: calc(100vh - 250px); min-height: 420px;"
        >
            <div x-ref="mapContainer" class="w-full h-full z-0" wire:ignore></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('storeMapHandler', () => ({
            map: null,
            layerGroup: null,
            defaultCenter: [-2.5, 118],
            defaultZoom: 5,

            initMap() {
                if (typeof L === 'undefined') return;

                if (!this.map) {
                    this.map = L.map(this.$refs.mapContainer).setView(this.defaultCenter, this.defaultZoom);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(this.map);

                    this.layerGroup = L.featureGroup().addTo(this.map);
                }

                // Render marker pertama kali
                this.renderMarkers(@js($stores));

                // Pantau perubahan stores dari Livewire
                this.$watch('$wire.stores', (newStores) => {
                    this.renderMarkers(newStores);
                });

                // Perbaiki rendering layout jika ada resize/tab switch
                setTimeout(() => {
                    this.map.invalidateSize();
                }, 250);
            },

            renderMarkers(stores) {
                if (!this.layerGroup) return;

                this.layerGroup.clearLayers();

                if (!stores || !Array.isArray(stores) || stores.length === 0) {
                    this.map.setView(this.defaultCenter, this.defaultZoom);
                    return;
                }

                let validMarkerCount = 0;

                stores.forEach(store => {
                    const lat = parseFloat(store.lat || store.latitude);
                    const lng = parseFloat(store.lng || store.longitude);

                    if (!isNaN(lat) && !isNaN(lng)) {
                        validMarkerCount++;
                        const marker = L.marker([lat, lng]);

                        let photoHtml = '';
                        const photoUrl = store.photo || store.store_photo;
                        if (photoUrl) {
                            const fullPhotoUrl = photoUrl.startsWith('http') ? photoUrl : `/storage/${photoUrl}`;
                            photoHtml = `<img src="${fullPhotoUrl}" alt="${store.name || store.store_name}" style="width:100%; height:110px; object-fit:cover; border-radius:6px; margin-bottom:8px;">`;
                        } else {
                            photoHtml = `<div style="width:100%; height:80px; background:#f1f5f9; border-radius:6px; margin-bottom:8px; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:12px;">Tanpa Foto</div>`;
                        }

                        const storeName = store.name || store.store_name || '-';
                        const ownerName = store.owner || store.owner_name || '';
                        const address = store.address || '-';
                        const area = store.area || '-';
                        const salesName = store.sales || (store.sales_person ? store.sales_person.name : '-');

                        const popupContent = `
                            <div style="min-width:210px; padding:2px; font-family: inherit;">
                                ${photoHtml}
                                <h3 style="margin:0 0 3px 0; font-weight:bold; font-size:14px; color:#1e293b;">${storeName}</h3>
                                ${ownerName ? `<p style="margin:0 0 4px 0; font-size:12px; color:#475569;">${ownerName}</p>` : ''}
                                <p style="margin:0 0 6px 0; font-size:11px; color:#64748b; line-height:1.3;">${address}</p>
                                <div style="display:flex; justify-content:space-between; font-size:11px; padding-top:6px; border-top:1px solid #e2e8f0;">
                                    <span style="color:#0284c7; font-weight:600;">${area}</span>
                                    <span style="color:#64748b;">Sales: ${salesName}</span>
                                </div>
                            </div>
                        `;

                        marker.bindPopup(popupContent);
                        this.layerGroup.addLayer(marker);
                    }
                });

                // Validasi agar tidak crash saat memanggil getBounds()
                if (validMarkerCount > 0) {
                    try {
                        const bounds = this.layerGroup.getBounds();
                        if (bounds.isValid()) {
                            this.map.fitBounds(bounds, { padding: [40, 40], maxZoom: 16 });
                        }
                    } catch (e) {
                        console.error('Error fitting map bounds:', e);
                    }
                } else {
                    this.map.setView(this.defaultCenter, this.defaultZoom);
                }
            }
        }));
    });
</script>
@endpush