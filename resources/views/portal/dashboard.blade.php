<x-portal-layout :title="'Dashboard'">
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-primary-600 dark:text-primary-400">Portal Pelanggan</p>
                <h1 class="mt-1 text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Selamat datang, {{ Auth::user()->name ?? 'Pelanggan' }} 👋</h1>
                <p class="mt-2 max-w-2xl text-slate-500 dark:text-slate-400">Pantau pesanan, cek status pengiriman, dan temukan promo yang sedang berjalan dari satu tempat.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-2 rounded-2xl bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 px-4 py-3 text-sm font-semibold text-success-700 dark:text-success-400">
                    <span class="w-2 h-2 rounded-full bg-success-500 animate-pulse"></span>
                    Akun Aktif
                </span>
                <button type="button" class="inline-flex items-center justify-center px-4 py-3 bg-primary-600 rounded-xl font-medium text-sm text-white shadow-sm shadow-primary-600/20 hover:bg-primary-700 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Pesanan
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pesanan Aktif</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">3</h4>
                    </div>
                    <div class="p-2.5 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-400">Sedang diproses gudang</p>
            </div>

            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pengiriman Hari Ini</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">2</h4>
                    </div>
                    <div class="p-2.5 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-400">Estimasi tiba sore ini</p>
            </div>

            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Piutang Berjalan</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Rp 3.200.000</h4>
                    </div>
                    <div class="p-2.5 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-400">Jatuh tempo 12 hari lagi</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Pesanan Terbaru -->
            <div class="lg:col-span-2 glass-card overflow-hidden">
                <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-white/50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Pesanan Terbaru</h3>
                    <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">Lihat Semua</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    <div class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 flex items-center justify-center font-bold text-sm">SO</div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">SO-2309-135</p>
                                <p class="text-xs text-slate-500">5 item &bull; 10 mnt lalu</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Rp 4.500.000</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">Diproses</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 flex items-center justify-center font-bold text-sm">SO</div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">SO-2309-121</p>
                                <p class="text-xs text-slate-500">12 item &bull; Kemarin</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Rp 9.750.000</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400">Terkirim</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 flex items-center justify-center font-bold text-sm">SO</div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">SO-2309-108</p>
                                <p class="text-xs text-slate-500">8 item &bull; 3 hari lalu</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Rp 2.100.000</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400">Terkirim</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promo Aktif -->
            <div class="glass-card overflow-hidden">
                <div class="p-5 border-b border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center">
                        <svg class="w-5 h-5 text-warning-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                        Promo Tersedia
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="rounded-xl border border-primary-100 dark:border-primary-900/40 bg-primary-50/60 dark:bg-primary-900/10 p-4">
                        <p class="text-sm font-semibold text-primary-700 dark:text-primary-400">Diskon 10% Beras 5Kg</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Berlaku s.d. 31 Agustus 2026</p>
                    </div>
                    <div class="rounded-xl border border-primary-100 dark:border-primary-900/40 bg-primary-50/60 dark:bg-primary-900/10 p-4">
                        <p class="text-sm font-semibold text-primary-700 dark:text-primary-400">Beli 10 Dus Gratis 1</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Khusus produk Indomie Goreng</p>
                    </div>
                    <a href="#" class="block text-center text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 pt-2">Lihat Semua Promo &rarr;</a>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('dashboard') }}" class="rounded-xl bg-slate-900 dark:bg-slate-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 dark:hover:bg-slate-600 transition-colors">Ke dashboard admin</a>
        </div>
    </div>
</x-portal-layout>
