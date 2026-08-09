<div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 py-12">

    <div class="glass-card p-8 text-center">
        <div class="w-16 h-16 rounded-full bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Pesanan Berhasil Dibuat!</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
            Nomor pesanan Anda <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $order->order_number }}</span>.
            Tim kami akan segera memproses pesanan Anda.
        </p>
    </div>

    <div class="glass-card overflow-hidden mt-6">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white">Detail Pesanan</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800 dark:bg-warning-900/30 dark:text-warning-400">
                Menunggu Konfirmasi
            </span>
        </div>
        <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @foreach ($order->items as $item)
                <div class="flex items-center gap-4 p-4">
                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
                        @if ($item->product?->primaryImage)
                            <img src="{{ $item->product->primaryImage->url }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200 line-clamp-1">{{ $item->product->name ?? 'Produk' }}</p>
                        <p class="text-xs text-slate-400">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200 shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>
        <div class="p-5 border-t border-slate-100 dark:border-slate-700 space-y-1.5">
            <div class="flex justify-between text-sm text-slate-500 dark:text-slate-400">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            @if ($order->discount > 0)
                <div class="flex justify-between text-sm text-slate-500 dark:text-slate-400">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between items-baseline pt-1.5">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Total</span>
                <span class="text-lg font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 mt-6">
        <a href="{{ route('shop.products') }}" wire:navigate class="flex-1 inline-flex items-center justify-center px-5 py-3 bg-primary-600 rounded-xl font-semibold text-sm text-white shadow-sm shadow-primary-600/20 hover:bg-primary-700 transition-all">
            Lanjut Belanja
        </a>
        <a href="{{ route('portal.dashboard') }}" class="flex-1 inline-flex items-center justify-center px-5 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
            Dashboard Saya
        </a>
    </div>
</div>
