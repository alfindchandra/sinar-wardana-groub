<div class="space-y-4">

    <!-- Filter Chips -->
    <div class="flex gap-2">
        <button type="button" wire:click="$set('filter', 'today')" class="flex-1 px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ $filter === 'today' ? 'bg-primary-600 text-white shadow-sm' : 'glass-card text-slate-600 dark:text-slate-300' }}">
            Hari Ini
        </button>
        <button type="button" wire:click="$set('filter', 'week')" class="flex-1 px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ $filter === 'week' ? 'bg-primary-600 text-white shadow-sm' : 'glass-card text-slate-600 dark:text-slate-300' }}">
            Minggu Ini
        </button>
        <button type="button" wire:click="$set('filter', 'month')" class="flex-1 px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ $filter === 'month' ? 'bg-primary-600 text-white shadow-sm' : 'glass-card text-slate-600 dark:text-slate-300' }}">
            Bulan Ini
        </button>
    </div>

    @if ($visits->isEmpty())
        <div class="glass-card p-10 text-center">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Belum ada kunjungan</p>
            <p class="text-xs text-slate-400 mt-1">Tekan tombol + untuk mulai check-in</p>
        </div>
    @else
        <div class="space-y-2.5">
            @foreach ($visits as $visit)
                <a href="{{ route('sales.visits.show', $visit) }}" wire:navigate class="glass-card p-3.5 flex items-center gap-3 active:scale-[0.99] transition-transform">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $visit->check_out_time ? 'bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400' : 'bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $visit->customer?->store_name ?? 'Kunjungan Umum' }}</p>
                        <p class="text-[11px] text-slate-400">
                            {{ $visit->visit_date->translatedFormat('d M Y') }} &middot; {{ $visit->check_in_time?->format('H:i') }}
                            @if ($visit->check_out_time) &ndash; {{ $visit->check_out_time->format('H:i') }} @endif
                        </p>
                    </div>
                    <span class="text-[10px] font-semibold px-2 py-1 rounded-full shrink-0 {{ $visit->check_out_time ? 'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400' : 'bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400' }}">
                        {{ $visit->check_out_time ? 'Selesai' : 'Berlangsung' }}
                    </span>
                    <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @endforeach
        </div>

        @if ($visits->hasPages())
            <div class="pt-2">{{ $visits->links() }}</div>
        @endif
    @endif
</div>
