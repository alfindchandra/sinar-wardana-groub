<div class="space-y-5" x-data="{
    requesting: false,
    requestLocation() {
        if (!navigator.geolocation) {
            $wire.locationFailed();
            return;
        }
        this.requesting = true;
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                this.requesting = false;
                $wire.setLocation(pos.coords.latitude, pos.coords.longitude);
            },
            () => {
                this.requesting = false;
                $wire.locationFailed();
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }
}" x-init="requestLocation()">

    <!-- GPS Status -->
    <div class="glass-card p-4">
        @if ($locationCaptured)
            <div class="flex items-center gap-3">
                <div class="p-2 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-xl shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 dark:text-white">Lokasi Terdeteksi</p>
                    <p class="text-[11px] text-slate-400 font-mono">{{ number_format($latitude, 5) }}, {{ number_format($longitude, 5) }}</p>
                </div>
                <svg class="w-5 h-5 text-success-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
        @elseif ($locationDenied)
            <div class="flex items-center gap-3">
                <div class="p-2 bg-danger-100 dark:bg-danger-900/30 text-danger-600 dark:text-danger-400 rounded-xl shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-800 dark:text-white">Lokasi Gagal Diakses</p>
                    <p class="text-[11px] text-slate-400">Aktifkan izin lokasi di browser Anda.</p>
                </div>
                <button type="button" @click="requestLocation()" class="text-xs font-semibold text-primary-600 dark:text-primary-400 shrink-0">Coba Lagi</button>
            </div>
        @else
            <div class="flex items-center gap-3">
                <div class="p-2 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-xl shrink-0">
                    <svg x-show="requesting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <svg x-show="!requesting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Mendeteksi lokasi Anda...</p>
            </div>
        @endif
    </div>

    @error('latitude') <p class="text-xs text-danger-600 dark:text-danger-400 -mt-3 px-1">{{ $message }}</p> @enderror

    <!-- Pilih Customer -->
    <div>
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Toko / Pelanggan (opsional)</label>

        @if ($customer_id)
            <div class="glass-card p-3.5 flex items-center gap-3">
                <div class="p-2 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-lg shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <span class="flex-1 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $customer_name }}</span>
                <button type="button" wire:click="clearCustomer" class="p-1 text-slate-400 hover:text-danger-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @else
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama toko..." class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 text-sm">

                @if ($search !== '')
                    <div class="mt-2 glass-card overflow-hidden divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse ($customers as $cust)
                            <button type="button" wire:click="selectCustomer({{ $cust->id }}, '{{ addslashes($cust->store_name) }}')" class="w-full text-left px-3.5 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $cust->store_name }}</p>
                                <p class="text-xs text-slate-400">{{ $cust->owner_name }} &middot; {{ $cust->area }}</p>
                            </button>
                        @empty
                            <p class="px-3.5 py-3 text-sm text-slate-400">Tidak ada toko ditemukan.</p>
                        @endforelse
                    </div>
                @endif
            </div>
            <p class="mt-1.5 text-xs text-slate-400">Kosongkan jika ini kunjungan umum tanpa toko spesifik.</p>
        @endif
    </div>

    <!-- Foto -->
    <div>
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Foto Bukti Kunjungan (opsional)</label>
        @if ($photo)
            <div class="relative rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-48 object-cover">
                <button type="button" wire:click="$set('photo', null)" class="absolute top-2 right-2 p-1.5 bg-black/60 rounded-full text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @else
            <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer active:bg-slate-50 dark:active:bg-slate-800/50 transition-colors">
                <svg class="w-6 h-6 text-slate-400 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="text-xs text-slate-500 dark:text-slate-400">Ambil / Upload Foto</span>
                <input type="file" wire:model="photo" accept="image/*" capture="environment" class="hidden">
            </label>
        @endif
        <div wire:loading wire:target="photo" class="text-xs text-primary-600 mt-1.5">Mengunggah foto...</div>
    </div>

    <!-- Catatan -->
    <div>
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Catatan</label>
        <textarea wire:model="notes" rows="3" placeholder="Tulis catatan kunjungan (opsional)" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 text-sm"></textarea>
    </div>

    <!-- Submit -->
    <button
        type="button"
        wire:click="submit"
        wire:loading.attr="disabled"
        wire:target="submit"
        @disabled(! $locationCaptured)
        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl font-bold text-sm text-white bg-primary-600 shadow-lg shadow-primary-600/30 active:scale-[0.98] disabled:bg-slate-300 dark:disabled:bg-slate-700 disabled:cursor-not-allowed transition-all"
    >
        <svg wire:loading wire:target="submit" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        <span wire:loading.remove wire:target="submit">
            {{ $locationCaptured ? 'Check-in Sekarang' : 'Menunggu Lokasi GPS...' }}
        </span>
        <span wire:loading wire:target="submit">Menyimpan...</span>
    </button>
</div>
