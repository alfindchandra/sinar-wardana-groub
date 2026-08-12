<div class="space-y-5">

    <!-- Welcome Card -->
    <div class="flex items-center gap-3">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($salesPerson->name) }}&background=2563EB&color=fff" class="w-11 h-11 rounded-full border-2 border-white dark:border-slate-800 shadow-sm">
        <div>
            <p class="text-xs text-slate-400">Selamat datang kembali,</p>
            <p class="text-base font-bold text-slate-800 dark:text-white">{{ $salesPerson->name }}</p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 gap-3">
        <div class="glass-card p-4">
            <div class="flex items-center gap-2 mb-1">
                <div class="p-1.5 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                </div>
                <span class="text-[11px] text-slate-400">Hari Ini</span>
            </div>
            <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $todayVisits->count() }}</p>
            <p class="text-[11px] text-slate-400">Kunjungan</p>
        </div>

        <div class="glass-card p-4">
            <div class="flex items-center gap-2 mb-1">
                <div class="p-1.5 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-[11px] text-slate-400">Bulan Ini</span>
            </div>
            <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $visitsThisMonth }}</p>
            <p class="text-[11px] text-slate-400">Total Kunjungan</p>
        </div>

        <div class="glass-card p-4 col-span-2">
            <div class="flex items-center gap-2 mb-1">
                <div class="p-1.5 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3v-6m-3 6v-9m-2 9h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v9a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-[11px] text-slate-400">Komisi Bulan Ini</span>
            </div>
            <p class="text-xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($commissionThisMonth, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Target Progress -->
    <div class="glass-card p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white">Target Bulan Ini</h3>
            <a href="{{ route('sales.targets') }}" wire:navigate class="text-xs font-medium text-primary-600 dark:text-primary-400">Detail &rarr;</a>
        </div>

        @if ($target)
            @php $pct = min(100, $target->achievement_percentage); @endphp
            <div class="flex items-center gap-4">
                <div class="relative w-20 h-20 shrink-0">
                    <svg class="w-20 h-20 -rotate-90">
                        <circle cx="40" cy="40" r="34" stroke-width="7" fill="none" class="text-slate-100 dark:text-slate-800" stroke="currentColor"></circle>
                        <circle cx="40" cy="40" r="34" stroke-width="7" fill="none" stroke-linecap="round"
                            class="text-primary-600 dark:text-primary-400" stroke="currentColor"
                            stroke-dasharray="213.6" stroke-dashoffset="{{ 213.6 - (213.6 * $pct / 100) }}"></circle>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-sm font-bold text-slate-800 dark:text-white">{{ number_format($pct, 0) }}%</span>
                    </div>
                </div>
                <div class="flex-1 min-w-0 space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Tercapai</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">Rp {{ number_format($target->achieved_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Target</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">Rp {{ number_format($target->target_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @else
            <p class="text-sm text-slate-400 text-center py-4">Belum ada target untuk bulan ini.</p>
        @endif
    </div>

    <!-- Quick Action -->
    <a href="{{ route('sales.visits.checkin') }}" wire:navigate class="flex items-center gap-3 p-4 rounded-2xl bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-lg shadow-primary-600/20 active:scale-[0.98] transition-transform">
        <div class="p-2.5 bg-white/15 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-bold">Check-in Kunjungan Baru</p>
            <p class="text-xs text-primary-100">Catat kunjungan ke toko pelanggan</p>
        </div>
        <svg class="w-5 h-5 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
    </a>

    <!-- Kunjungan Hari Ini -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white">Kunjungan Hari Ini</h3>
            <a href="{{ route('sales.visits.index') }}" wire:navigate class="text-xs font-medium text-primary-600 dark:text-primary-400">Lihat Semua &rarr;</a>
        </div>

        @if ($todayVisits->isEmpty())
            <div class="glass-card p-6 text-center">
                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <p class="text-sm text-slate-400">Belum ada kunjungan hari ini</p>
            </div>
        @else
            <div class="space-y-2.5">
                @foreach ($todayVisits->take(4) as $visit)
                    <div class="glass-card p-3.5 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 {{ $visit->check_out_time ? 'bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400' : 'bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate">{{ $visit->customer?->store_name ?? 'Kunjungan Umum' }}</p>
                            <p class="text-[11px] text-slate-400">{{ $visit->check_in_time?->format('H:i') }} @if($visit->check_out_time) &ndash; {{ $visit->check_out_time->format('H:i') }} @endif</p>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-1 rounded-full {{ $visit->check_out_time ? 'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400' : 'bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400' }}">
                            {{ $visit->check_out_time ? 'Selesai' : 'Berlangsung' }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
