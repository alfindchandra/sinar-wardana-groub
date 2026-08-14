<div class="space-y-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Peta Toko</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Pemetaan lokasi toko pelanggan</p>
        </div>
        <a href="{{ route('admin.stores') }}" wire:navigate class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
            Daftar Toko
        </a>
    </div>

    <div class="glass-card p-4 rounded-xl flex flex-col sm:flex-row gap-4 bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex-1">
            <select wire:model.live="areaFilter" class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200">
                <option value="">Semua Area</option>
                @foreach($areas as $area)
                    <option value="{{ $area }}">{{ $area }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <select wire:model.live="salesFilter" class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200">
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
            x-data="storeMap(@js($stores))" 
            x-init="initMap()" 
            class="w-full bg-slate-100 dark:bg-slate-800"
            style="height: calc(100vh - 250px); min-height: 400px;"
            wire:ignore
        >
            <div id="map" class="w-full h-full z-0"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('storeMap', (stores) => ({
            map: null,
            markers: [],
            layerGroup: null,
            storesData: stores,
            
            initMap() {
                this.map = L.map('map').setView([-2.5, 118], 5);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(this.map);
                
                this.layerGroup = L.featureGroup().addTo(this.map);
                this.renderMarkers();
                
                this.$watch('storesData', (value) => {
                    this.renderMarkers();
                });
                
                window.addEventListener('contentChanged', () => {
                    setTimeout(() => { this.map.invalidateSize(); }, 300);
                });
            },
            
            renderMarkers() {
                this.layerGroup.clearLayers();
                
                if (!this.storesData || this.storesData.length === 0) return;
                
                this.storesData.forEach(store => {
                    if (store.lat && store.lng) {
                        const marker = L.marker([store.lat, store.lng]);
                        
                        let photoHtml = '';
                        if (store.photo) {
                            photoHtml = `<img src="${store.photo}" alt="${store.name}" style="width:100%; height:120px; object-fit:cover; border-radius:8px; margin-bottom:8px;">`;
                        } else {
                            photoHtml = `<div style="width:100%; height:120px; background:#f1f5f9; border-radius:8px; margin-bottom:8px; display:flex; align-items:center; justify-content:center; color:#94a3b8;"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>`;
                        }
                        
                        const popupContent = `
                            <div style="min-width:200px; padding:4px;">
                                ${photoHtml}
                                <h3 style="margin:0 0 4px 0; font-weight:bold; font-size:16px;">${store.name}</h3>
                                <p style="margin:0 0 4px 0; font-size:13px; color:#475569;">${store.owner}</p>
                                <p style="margin:0 0 8px 0; font-size:12px; color:#64748b; line-height:1.4;">${store.address || '-'}</p>
                                <div style="display:flex; justify-content:space-between; font-size:11px; padding-top:8px; border-top:1px solid #e2e8f0;">
                                    <span style="color:#0ea5e9; font-weight:500;">${store.area || '-'}</span>
                                    <span style="color:#64748b;">Sales: ${store.sales}</span>
                                </div>
                            </div>
                        `;
                        
                        marker.bindPopup(popupContent);
                        this.layerGroup.addLayer(marker);
                    }
                });
                
                if (this.storesData.length > 0) {
                    this.map.fitBounds(this.layerGroup.getBounds(), { padding: [50, 50] });
                }
            }
        }));
    });
</script>
@endpush
