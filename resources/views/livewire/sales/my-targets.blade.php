<div class="space-y-5">

    <!-- Target Bulan Ini (besar) -->
    <div class="glass-card p-5">
        <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">Target {{ now()->translatedFormat('F Y') }}</h3>

        @if ($currentTarget)
            @php $pct = min(100, $currentTarget->achievement_percentage); @endphp
            <div class="text-center mb-4">
                <div class="relative w-28 h-28 mx-auto">
                    <svg class="w-28 h-28 -rotate-90">
                        <circle cx="56" cy="56" r="48" stroke-width="9" fill="none" class="text-slate-100 dark:text-slate-800" stroke="currentColor"></circle>
                        <circle cx="56" cy="56" r="48" stroke-width="9" fill="none" stroke-linecap="round"
                            class="{{ $pct >= 100 ? 'text-success-500' : 'text-primary-600 dark:text-primary-400' }}" stroke="currentColor"
                            stroke-dasharray="301.6" stroke-dashoffset="{{ 301.6 - (301.6 * $pct / 100) }}"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($pct, 0) }}%</span>
                        <span class="text-[10px] text-slate-400">Tercapai</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-3">
                    <p class="text-[11px] text-slate-400">Tercapai</p>
                    <p class="text-sm font-bold text-slate-800 dark:text-white mt-0.5">Rp {{ number_format($currentTarget->achieved_amount, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-3">
                    <p class="text-[11px] text-slate-400">Target</p>
                    <p class="text-sm font-bold text-slate-800 dark:text-white mt-0.5">Rp {{ number_format($currentTarget->target_amount, 0, ',', '.') }}</p>
                </div>
            </div>
        @else
            <p class="text-sm text-slate-400 text-center py-6">Belum ada target untuk bulan ini.</p>
        @endif
    </div>

    <!-- Riwayat Target -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-3">Riwayat Target</h3>
        @if ($targets->isEmpty())
            <div class="glass-card p-6 text-center text-sm text-slate-400">Belum ada riwayat target.</div>
        @else
            <div class="space-y-2.5">
                @foreach ($targets as $t)
                    @php $tPct = min(100, $t->achievement_percentage); @endphp
                    <div class="glass-card p-3.5">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $t->period_label }}</span>
                            <span class="text-xs font-bold {{ $tPct >= 100 ? 'text-success-600 dark:text-success-400' : 'text-slate-500 dark:text-slate-400' }}">{{ number_format($tPct, 0) }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full rounded-full {{ $tPct >= 100 ? 'bg-success-500' : 'bg-primary-500' }}" style="width: {{ $tPct }}%"></div>
                        </div>
                        <div class="flex justify-between mt-1.5 text-[11px] text-slate-400">
                            <span>Rp {{ number_format($t->achieved_amount, 0, ',', '.') }}</span>
                            <span>Rp {{ number_format($t->target_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Riwayat Komisi -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-3">Riwayat Komisi</h3>
        @if ($commissions->isEmpty())
            <div class="glass-card p-6 text-center text-sm text-slate-400">Belum ada riwayat komisi.</div>
        @else
            <div class="glass-card overflow-hidden divide-y divide-slate-100 dark:divide-slate-700/50">
                @foreach ($commissions as $c)
                    <div class="flex items-center justify-between p-3.5">
                        <div>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">
                                {{ $c->period_month ? date('F', mktime(0, 0, 0, $c->period_month, 10)) . ' ' . $c->period_year : '-' }}
                            </p>
                            <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full inline-block mt-0.5 {{ match($c->status) {
                                'paid' => 'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400',
                                'approved' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                default => 'bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400',
                            } }}">
                                {{ match($c->status) { 'paid' => 'Dibayar', 'approved' => 'Disetujui', default => 'Menunggu' } }}
                            </span>
                        </div>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">Rp {{ number_format($c->amount, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
