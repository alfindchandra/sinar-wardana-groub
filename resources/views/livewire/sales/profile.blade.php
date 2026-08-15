<div class="space-y-5">

    <!-- Profile Card -->
    <div class="glass-card p-6 text-center">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($salesPerson->name) }}&background=2563EB&color=fff&size=128" class="w-20 h-20 rounded-full mx-auto border-4 border-white dark:border-slate-800 shadow-sm">
        <p class="text-lg font-bold text-slate-800 dark:text-white mt-3">{{ $salesPerson->name }}</p>
        <p class="text-sm text-slate-400">{{ $salesPerson->code }} &middot; {{ $salesPerson->area ?: 'Tanpa Area' }}</p>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400 mt-3">
            <span class="w-1.5 h-1.5 rounded-full bg-success-500"></span>
            Aktif
        </span>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 gap-3">
        <div class="glass-card p-4 text-center">
            <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $totalCustomers }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Toko Binaan</p>
        </div>
        <div class="glass-card p-4 text-center">
            <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $totalVisits }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Total Kunjungan</p>
        </div>
    </div>

    <!-- Info -->
    <div class="glass-card overflow-hidden divide-y divide-slate-100 dark:divide-slate-700/50">
        <div class="p-4 flex items-center gap-3">
            <svg class="w-4.5 h-4.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <div>
                <p class="text-[11px] text-slate-400">Email</p>
                <p class="text-sm text-slate-700 dark:text-slate-300">{{ $salesPerson->email ?: '-' }}</p>
            </div>
        </div>
        <div class="p-4 flex items-center gap-3">
            <svg class="w-4.5 h-4.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
            <div>
                <p class="text-[11px] text-slate-400">Telepon</p>
                <p class="text-sm text-slate-700 dark:text-slate-300">{{ $salesPerson->phone ?: '-' }}</p>
            </div>
        </div>
        <div class="p-4 flex items-center gap-3">
            <svg class="w-4.5 h-4.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            <div>
                <p class="text-[11px] text-slate-400">Komisi</p>
                <p class="text-sm text-slate-700 dark:text-slate-300">{{ $salesPerson->commission_rate }}% per penjualan</p>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle -->
    <div class="glass-card p-4 flex items-center justify-between" x-data>
        <div class="flex items-center gap-3">
            <svg class="w-4.5 h-4.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Mode Gelap</span>
        </div>
        <button @click="$store.theme.toggle()" type="button" :class="$store.theme.dark ? 'bg-primary-600' : 'bg-slate-300 dark:bg-slate-700'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
            <span :class="$store.theme.dark ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
        </button>
    </div>

    <!-- Logout -->
    <button type="button" wire:click="logout" wire:confirm="Yakin ingin keluar?" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl font-bold text-sm text-danger-600 bg-danger-50 dark:bg-danger-900/20 active:scale-[0.98] transition-transform">
        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        Keluar
    </button>
</div>
