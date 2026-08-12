<div class="space-y-5" x-data="{
    checkingOut: false,
    doCheckOut() {
        if (!navigator.geolocation) { $wire.checkOutFailed(); return; }
        this.checkingOut = true;
        navigator.geolocation.getCurrentPosition(
            (pos) => { this.checkingOut = false; $wire.checkOut(pos.coords.latitude, pos.coords.longitude); },
            () => { this.checkingOut = false; $wire.checkOutFailed(); },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }
}">

    <!-- Status Badge -->
    <div class="flex justify-center">
        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-semibold {{ $visit->check_out_time ? 'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400' : 'bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400' }}">
            <span class="w-2 h-2 rounded-full {{ $visit->check_out_time ? 'bg-success-500' : 'bg-warning-500 animate-pulse' }}"></span>
            {{ $visit->check_out_time ? 'Kunjungan Selesai' : 'Sedang Berlangsung' }}
        </span>
    </div>

    <!-- Customer Card -->
    <div class="glass-card p-5 text-center">
        <div class="w-14 h-14 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <p class="text-base font-bold text-slate-800 dark:text-white">{{ $visit->customer?->store_name ?? 'Kunjungan Umum' }}</p>
        @if ($visit->customer)
            <p class="text-sm text-slate-400">{{ $visit->customer->owner_name }} &middot; {{ $visit->customer->area }}</p>
        @endif
    </div>

    <!-- Timeline Waktu -->
    <div class="glass-card p-5">
        <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">Waktu Kunjungan</h3>
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Check-in</p>
                    <p class="text-xs text-slate-400">{{ $visit->check_in_time?->translatedFormat('d M Y, H:i') }}</p>
                    @if ($visit->check_in_latitude)
                        <p class="text-[11px] text-slate-400 font-mono mt-0.5">{{ number_format($visit->check_in_latitude, 5) }}, {{ number_format($visit->check_in_longitude, 5) }}</p>
                    @endif
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full {{ $visit->check_out_time ? 'bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }} flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Check-out</p>
                    @if ($visit->check_out_time)
                        <p class="text-xs text-slate-400">{{ $visit->check_out_time->translatedFormat('d M Y, H:i') }}</p>
                        <p class="text-[11px] text-slate-400 font-mono mt-0.5">{{ number_format($visit->check_out_latitude, 5) }}, {{ number_format($visit->check_out_longitude, 5) }}</p>
                    @else
                        <p class="text-xs text-slate-400">Belum check-out</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($visit->photo)
        <div class="glass-card overflow-hidden">
            <img src="{{ Storage::disk('public')->url($visit->photo) }}" class="w-full h-56 object-cover">
        </div>
    @endif

    @if ($visit->notes)
        <div class="glass-card p-5">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-1.5">Catatan</h3>
            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $visit->notes }}</p>
        </div>
    @endif

    @unless ($visit->check_out_time)
        <button
            type="button"
            @click="doCheckOut()"
            :disabled="checkingOut"
            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl font-bold text-sm text-white bg-danger-600 shadow-lg shadow-danger-600/30 active:scale-[0.98] disabled:opacity-60 transition-all"
        >
            <svg x-show="checkingOut" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span x-text="checkingOut ? 'Mendeteksi lokasi...' : 'Check-out Sekarang'"></span>
        </button>
    @endunless
</div>
