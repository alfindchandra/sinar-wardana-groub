<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('portal.orders.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400 transition-colors mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Pesanan Saya
            </a>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $order->order_number }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Dipesan pada {{ $order->order_date->translatedFormat('d F Y') }}</p>
        </div>
        @php $statusEnum = \App\Enums\OnlineOrderStatus::from($order->status); @endphp
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-{{ $statusEnum->color() }}-100 text-{{ $statusEnum->color() }}-800 dark:bg-{{ $statusEnum->color() }}-900/30 dark:text-{{ $statusEnum->color() }}-400">
            {{ $statusEnum->label() }}
        </span>
    </div>

    <!-- Timeline -->
    <div class="glass-card p-6">
        @if ($order->status === 'cancelled')
            <div class="flex items-center gap-3 text-danger-600 dark:text-danger-400">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="text-sm font-medium">{{ $statusEnum->description() }}</p>
            </div>
        @else
            <div class="flex items-center justify-between">
                @foreach ($timeline as $index => $step)
                    @php $stepEnum = $step['status']; @endphp
                    <div class="flex-1 flex flex-col items-center text-center relative">
                        @if ($index > 0)
                            <div class="absolute top-4 right-1/2 w-full h-0.5 {{ $step['done'] ? 'bg-primary-500' : 'bg-slate-200 dark:bg-slate-700' }}" style="left: -50%;"></div>
                        @endif
                        <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $step['done'] ? 'bg-primary-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-400' }}">
                            @if ($step['done'])
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                        <p class="text-[11px] sm:text-xs mt-2 font-medium {{ $step['done'] ? 'text-slate-800 dark:text-slate-200' : 'text-slate-400' }} max-w-[80px]">
                            {{ $stepEnum->label() }}
                        </p>
                    </div>
                @endforeach
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 text-center mt-6">{{ $statusEnum->description() }}</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Items -->
        <div class="lg:col-span-2 glass-card overflow-hidden divide-y divide-slate-100 dark:divide-slate-700/50">
            @foreach ($order->items as $item)
                <div class="flex items-center gap-4 p-4">
                    <div class="w-14 h-14 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
                        @if ($item->product?->primaryImage)
                            <img src="{{ $item->product->primaryImage->url }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200 line-clamp-1">{{ $item->product->name ?? 'Produk' }}</p>
                        @if ($item->product_variant_name)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 mt-0.5">{{ $item->product_variant_name }}</span>
                        @endif
                        <p class="text-xs text-slate-400">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200 shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="glass-card p-5 h-fit">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-3">Ringkasan</h3>
            <div class="space-y-1.5 text-sm">
                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if ($order->discount > 0)
                    <div class="flex justify-between text-slate-500 dark:text-slate-400">
                        <span>Diskon</span>
                        <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>
            <div class="border-t border-slate-100 dark:border-slate-700 mt-3 pt-3 flex justify-between items-baseline">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Total</span>
                <span class="text-lg font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>

            @if ($order->notes)
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Catatan</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
