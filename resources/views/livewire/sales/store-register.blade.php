<div class="max-w-lg mx-auto pb-20">
    <div class="px-4 py-4">
        <form wire:submit="save" class="space-y-4">
            
            <!-- Informasi Toko -->
            <div class="glass-card p-4 rounded-2xl">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Informasi Toko</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Toko <span class="text-danger-600">*</span></label>
                        <input type="text" wire:model="store_name" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:ring-primary-500 focus:border-primary-500" placeholder="Contoh: Toko Jaya Abadi">
                        @error('store_name') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Pemilik <span class="text-danger-600">*</span></label>
                        <input type="text" wire:model="owner_name" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:ring-primary-500 focus:border-primary-500" placeholder="Nama Pemilik">
                        @error('owner_name') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor Telepon/WA</label>
                        <input type="tel" wire:model="phone" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:ring-primary-500 focus:border-primary-500" placeholder="081234567890">
                        @error('phone') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tipe Pelanggan</label>
                        <select wire:model="customer_type" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:ring-primary-500 focus:border-primary-500">
                            <option value="retail">Retail</option>
                            <option value="agen">Agen</option>
                            <option value="distributor">Distributor</option>
                        </select>
                        @error('customer_type') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Lokasi & Alamat -->
            <div class="glass-card p-4 rounded-2xl" 
                 x-data="{
                    selectedCity: @entangle('city'),
                    selectedArea: @entangle('area'),
                    districts: {
                        'Bojonegoro': [
                            'Balen', 'Baureno', 'Bojonegoro', 'Bubulan', 'Dander', 
                            'Gayam', 'Gondang', 'Kalitidu', 'Kanor', 'Kapas', 
                            'Kasiman', 'Kedewan', 'Kedungadem', 'Kepohbaru', 'Malo', 
                            'Margomulyo', 'Ngambon', 'Ngasem', 'Ngraho', 'Padangan', 
                            'Purwosari', 'Sekar', 'Sugihwaras', 'Sukosewu', 'Sumberejo', 
                            'Tambakrejo', 'Temayang', 'Trucuk'
                        ],
                        'Tuban': [
                            'Bancar', 'Bangilan', 'Grabagan', 'Jatirogo', 'Jenu', 
                            'Kenduruan', 'Kerek', 'Merakurak', 'Montong', 'Palang', 
                            'Parengan', 'Plumpang', 'Rengel', 'Semanding', 'Senori', 
                            'Singgahan', 'Soko', 'Tambakboyo', 'Tuban', 'Widang'
                        ]
                    },
                    get availableDistricts() {
                        return this.districts[this.selectedCity] || [];
                    },
                    onCityChange() {
                        this.selectedArea = '';
                    }
                 }">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Lokasi & Alamat</h3>

                <div x-data="geolocation()" x-init="initGeo()" class="space-y-4">
                    <!-- Status GPS -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center" :class="status === 'success' ? 'bg-success-100 text-success-600' : (status === 'loading' ? 'bg-warning-100 text-warning-600' : 'bg-danger-100 text-danger-600')">
                                <svg x-show="status === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <svg x-show="status === 'loading'" class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <svg x-show="status === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200" x-text="statusText"></p>
                                <p class="text-xs text-slate-500 dark:text-slate-400" x-show="status === 'success'">
                                    <span x-text="lat"></span>, <span x-text="lng"></span>
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="initGeo()" class="text-sm font-medium text-primary-600 hover:text-primary-700">Perbarui</button>
                    </div>

                    <!-- Mini Map -->
                    <div wire:ignore class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 h-48 w-full" x-show="status === 'success'">
                        <div x-ref="mapContainer" class="w-full h-full z-0"></div>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alamat Lengkap</label>
                        <textarea wire:model="address" rows="3" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:ring-primary-500 focus:border-primary-500" placeholder="Nama Jalan, RT/RW, Dusun, Desa"></textarea>
                        @error('address') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                    </div>

                    <!-- Dropdown Kabupaten & Kecamatan -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kabupaten <span class="text-danger-600">*</span></label>
                            <select x-model="selectedCity" @change="onCityChange()" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:ring-primary-500 focus:border-primary-500 text-sm">
                                <option value="">Pilih Kabupaten</option>
                                <option value="Bojonegoro">Kab. Bojonegoro</option>
                                <option value="Tuban">Kab. Tuban</option>
                            </select>
                            @error('city') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kecamatan (Area) <span class="text-danger-600">*</span></label>
                            <select x-model="selectedArea" :disabled="!selectedCity" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:ring-primary-500 focus:border-primary-500 text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                <option value="" x-text="selectedCity ? 'Pilih Kecamatan' : 'Pilih Kabupaten Dahulu'"></option>
                                <template x-for="item in availableDistricts" :key="item">
                                    <option :value="item" x-text="item" :selected="item === selectedArea"></option>
                                </template>
                            </select>
                            @error('area') <span class="text-sm text-danger-600">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Foto Toko -->
            <div class="glass-card p-4 rounded-2xl">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Foto Toko</h3>
                
                <div class="flex items-center justify-center w-full">
                    <label for="store_photo" class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-900 hover:bg-slate-100 dark:border-slate-700">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            @if ($store_photo)
                                <img src="{{ $store_photo->temporaryUrl() }}" class="h-32 object-contain mb-2 rounded-lg" alt="Preview">
                            @else
                                <svg class="w-10 h-10 mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <p class="mb-2 text-sm text-slate-500 dark:text-slate-400"><span class="font-semibold">Ambil Foto</span> atau Pilih dari Galeri</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Maks. 3MB</p>
                            @endif
                        </div>
                        <input id="store_photo" type="file" accept="image/*" capture="environment" wire:model="store_photo" class="hidden" />
                    </label>
                </div>
                @error('store_photo') <span class="text-sm text-danger-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-4 rounded-xl shadow-sm transition-colors duration-200 flex items-center justify-center gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5" />
                    Daftarkan Toko
                </button>
            </div>
        </form>
    </div>

    <!-- Leaflet Assets -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('geolocation', () => ({
                status: 'loading',
                statusText: 'Mengambil lokasi...',
                lat: null,
                lng: null,
                map: null,
                marker: null,

                initGeo() {
                    this.status = 'loading';
                    this.statusText = 'Mengambil lokasi...';
                    
                    if (!navigator.geolocation) {
                        this.status = 'error';
                        this.statusText = 'Geolokasi tidak didukung browser.';
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.lat = position.coords.latitude;
                            this.lng = position.coords.longitude;
                            this.status = 'success';
                            this.statusText = 'Lokasi berhasil didapatkan';
                            
                            this.$wire.set('latitude', this.lat);
                            this.$wire.set('longitude', this.lng);

                            this.initMap();
                        },
                        (error) => {
                            this.status = 'error';
                            this.statusText = 'Gagal mengambil lokasi: ' + error.message;
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                },

                initMap() {
                    setTimeout(() => {
                        if (typeof L === 'undefined') return;

                        if (!this.map && this.$refs.mapContainer) {
                            this.map = L.map(this.$refs.mapContainer).setView([this.lat, this.lng], 15);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '© OpenStreetMap'
                            }).addTo(this.map);
                            this.marker = L.marker([this.lat, this.lng], { draggable: true }).addTo(this.map);

                            // Update koordinat jika pin marker digeser manual
                            this.marker.on('dragend', (e) => {
                                const newPos = e.target.getLatLng();
                                this.lat = newPos.lat;
                                this.lng = newPos.lng;
                                this.$wire.set('latitude', this.lat);
                                this.$wire.set('longitude', this.lng);
                            });
                        } else if (this.map) {
                            this.map.setView([this.lat, this.lng], 15);
                            this.marker.setLatLng([this.lat, this.lng]);
                        }

                        if (this.map) {
                            this.map.invalidateSize();
                        }
                    }, 200);
                }
            }))
        });
    </script>
</div>