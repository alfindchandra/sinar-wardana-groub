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
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="text-[11px] text-slate-400">Hari Ini</span>
            </div>
            <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $todayOrders->count() }}</p>
            <p class="text-[11px] text-slate-400">Orderan</p>
        </div>

        <div class="glass-card p-4">
            <div class="flex items-center gap-2 mb-1">
                <div class="p-1.5 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-[11px] text-slate-400">Bulan Ini</span>
            </div>
            <p class="text-lg font-bold text-slate-800 dark:text-white">Rp {{ number_format($omsetThisMonth, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400">Omset</p>
        </div>

        <div class="glass-card p-4">
            <div class="flex items-center gap-2 mb-1">
                <div class="p-1.5 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <span class="text-[11px] text-slate-400">Toko</span>
            </div>
            <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $totalStores }}</p>
            <p class="text-[11px] text-slate-400">Total Toko</p>
        </div>

        <!-- Omset Hari Ini -->
        @php 
            $dailyPct = $dailyTarget > 0 ? min(100, round(($omsetToday / $dailyTarget) * 100)) : 0; 
        @endphp
        <div class="glass-card p-4">
            <div class="flex items-center justify-between mb-1">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <span class="text-[11px] text-slate-400">Omset Hari Ini</span>
                </div>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $dailyPct >= 100 ? 'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400' : 'bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400' }}">
                    {{ $dailyPct }}%
                </span>
            </div>
            <p class="text-lg font-bold text-slate-800 dark:text-white">Rp {{ number_format($omsetToday, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400">Target: Rp {{ number_format($dailyTarget, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Target Progress -->
    <div class="glass-card p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white">Target Omset Bulan Ini</h3>
            <a href="{{ route('sales.targets') }}" wire:navigate class="text-xs font-medium text-primary-600 dark:text-primary-400">Detail &rarr;</a>
        </div>

        @if ($target)
            @php $pct = min(100, $target->achievement_percentage); @endphp
            <div class="flex items-center gap-4">
                <div class="relative w-20 h-20 shrink-0">
                    <svg class="w-20 h-20 -rotate-90">
                        <circle cx="40" cy="40" r="34" stroke-width="7" fill="none" class="text-slate-100 dark:text-slate-800" stroke="currentColor"></circle>
                        <circle cx="40" cy="40" r="34" stroke-width="7" fill="none" stroke-linecap="round"
                            class="{{ $pct >= 100 ? 'text-success-500' : 'text-primary-600 dark:text-primary-400' }}" stroke="currentColor"
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
            <p class="text-sm text-slate-400 text-center py-4">Belum ada target omset untuk bulan ini.</p>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('sales.orders.create') }}" wire:navigate class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-gradient-to-br from-primary-600 to-primary-700 text-white shadow-lg shadow-primary-600/20 active:scale-[0.98] transition-transform">
            <div class="p-2.5 bg-white/15 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <span class="text-xs font-bold">Buat Order</span>
        </a>
        <a href="{{ route('sales.stores.register') }}" wire:navigate class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-gradient-to-br from-success-600 to-success-700 text-white shadow-lg shadow-success-600/20 active:scale-[0.98] transition-transform">
            <div class="p-2.5 bg-white/15 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <span class="text-xs font-bold">Daftar Toko</span>
        </a>
    </div>

    <!-- Orderan Hari Ini -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white">Orderan Hari Ini</h3>
            <a href="{{ route('sales.orders.index') }}" wire:navigate class="text-xs font-medium text-primary-600 dark:text-primary-400">Lihat Semua &rarr;</a>
        </div>

        @if ($todayOrders->isEmpty())
            <div class="glass-card p-6 text-center">
                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-sm text-slate-400">Belum ada orderan hari ini</p>
            </div>
        @else
            <div class="space-y-2.5">
                @foreach ($todayOrders->take(5) as $order)
                    <a href="{{ route('sales.orders.show', $order) }}" wire:navigate class="glass-card p-3.5 flex items-center gap-3 block active:bg-slate-50 dark:active:bg-slate-800/50 transition-colors">
                        @if ($order->customer?->store_photo)
                            <img src="{{ Storage::url($order->customer->store_photo) }}" class="w-10 h-10 rounded-lg object-cover shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate">{{ $order->customer?->store_name ?? '-' }}</p>
                            <p class="text-[11px] text-slate-400">{{ $order->so_number }} &middot; Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-1 rounded-full shrink-0 {{ match($order->status) {
                            'completed' => 'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400',
                            'confirmed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                            'processing' => 'bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400',
                            'shipped' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                            'cancelled' => 'bg-danger-100 text-danger-700 dark:bg-danger-900/30 dark:text-danger-400',
                            default => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400',
                        } }}">
                            {{ match($order->status) {
                                'draft' => 'Draft',
                                'confirmed' => 'Dikonfirmasi',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                default => ucfirst($order->status),
                            } }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>