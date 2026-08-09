<div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-8">

    <nav class="flex text-sm text-slate-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li><a href="{{ route('shop.home') }}" wire:navigate class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Beranda</a></li>
            <li class="flex items-center"><svg class="w-4 h-4 mx-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <a href="{{ route('shop.cart') }}" wire:navigate class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Keranjang</a>
            </li>
            <li class="flex items-center"><svg class="w-4 h-4 mx-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> <span class="text-slate-700 dark:text-slate-200 font-medium">Checkout</span></li>
        </ol>
    </nav>

    <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">Checkout</h1>

    @if (! $customer)
        <!-- Lengkapi Profil Toko -->
        <div class="glass-card p-6 max-w-xl">
            <h3 class="text-base font-bold text-slate-800 dark:text-white mb-1">Lengkapi Profil Toko Anda</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">Data ini dipakai untuk pengiriman pesanan Anda.</p>

            <form wire:submit="completeProfile" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Toko <span class="text-danger-500">*</span></label>
                    <input type="text" wire:model="store_name" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                    @error('store_name') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Pemilik <span class="text-danger-500">*</span></label>
                    <input type="text" wire:model="owner_name" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                    @error('owner_name') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">No. Telepon / WhatsApp <span class="text-danger-500">*</span></label>
                    <input type="text" wire:model="phone" placeholder="08xxxxxxxxxx" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                    @error('phone') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Alamat Pengiriman <span class="text-danger-500">*</span></label>
                    <textarea wire:model="address" rows="3" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm"></textarea>
                    @error('address') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Kota <span class="text-danger-500">*</span></label>
                    <input type="text" wire:model="city" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm">
                    @error('city') <p class="mt-1.5 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror
                </div>

                <button type="submit" wire:loading.attr="disabled" wire:target="completeProfile" class="w-full inline-flex items-center justify-center px-5 py-3 bg-primary-600 rounded-xl font-semibold text-sm text-white shadow-sm shadow-primary-600/20 hover:bg-primary-700 disabled:opacity-60 transition-all">
                    Simpan &amp; Lanjutkan
                </button>
            </form>
        </div>
    @elseif ($items->isEmpty())
        <div class="glass-card p-16 text-center">
            <p class="font-medium text-slate-600 dark:text-slate-300">Keranjang belanja Anda kosong.</p>
            <a href="{{ route('shop.products') }}" wire:navigate class="mt-4 inline-flex items-center px-5 py-2.5 bg-primary-600 rounded-xl font-medium text-sm text-white shadow-sm shadow-primary-600/20 hover:bg-primary-700 transition-all">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <!-- Alamat Pengiriman -->
                <div class="glass-card p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white">Alamat Pengiriman</h3>
                        <a href="{{ route('portal.dashboard') }}" class="text-xs font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">Kelola Profil</a>
                    </div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $customer->store_name }} ({{ $customer->owner_name }})</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $customer->phone }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $customer->address }}, {{ $customer->city }}</p>
                </div>

                <!-- Items -->
                <div class="glass-card overflow-hidden divide-y divide-slate-100 dark:divide-slate-700/50">
                    @foreach ($items as $item)
                        <div class="flex items-center gap-4 p-4" wire:key="checkout-item-{{ $item['product']->id }}-{{ $item['variant']?->id ?? 0 }}">
                            <div class="w-14 h-14 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
                                @if ($item['product']->primaryImage)
                                    <img src="{{ $item['product']->primaryImage->url }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200 line-clamp-1">{{ $item['product']->name }}</p>
                                @if ($item['variant'])
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 mt-0.5">
                                        {{ $item['variant']->name }}
                                    </span>
                                @endif
                                <p class="text-xs text-slate-400 mt-0.5">{{ $item['qty'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200 shrink-0">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Catatan -->
                <div class="glass-card p-5">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Catatan Pesanan (opsional)</label>
                    <textarea wire:model="notes" rows="3" placeholder="Contoh: tolong dikirim pagi hari" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm"></textarea>
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="glass-card p-5 h-fit lg:sticky lg:top-24">
                <h3 class="text-base font-bold text-slate-800 dark:text-white mb-4">Ringkasan Pembayaran</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-slate-500 dark:text-slate-400">
                        <span>Subtotal ({{ $items->sum('qty') }} item)</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500 dark:text-slate-400">
                        <span>Ongkos Kirim</span>
                        <span class="text-success-600 dark:text-success-400 font-medium">Dikonfirmasi admin</span>
                    </div>
                </div>
                <div class="border-t border-slate-100 dark:border-slate-700 mt-4 pt-4 flex justify-between items-baseline">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Total</span>
                    <span class="text-xl font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <button type="button" wire:click="placeOrder" wire:loading.attr="disabled" wire:target="placeOrder" class="mt-5 w-full inline-flex items-center justify-center px-5 py-3 bg-primary-600 rounded-xl font-semibold text-sm text-white shadow-sm shadow-primary-600/20 hover:bg-primary-700 disabled:opacity-60 transition-all">
                    <svg wire:loading wire:target="placeOrder" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    Buat Pesanan
                </button>
                <p class="text-xs text-slate-400 mt-3 text-center">Pesanan akan dikonfirmasi oleh tim kami sebelum diproses.</p>
            </div>
        </div>
    @endif
</div>
