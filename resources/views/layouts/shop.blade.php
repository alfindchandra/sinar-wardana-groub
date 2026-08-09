<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || false, mobileMenu: false }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Belanja' }} - {{ config('app.name', 'Sinar Wardana') }}</title>
    <meta name="description" content="Belanja kebutuhan sembako &amp; grosir online di {{ config('app.name', 'Sinar Wardana') }}. Harga bersaing, pengiriman cepat.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-900 min-h-screen flex flex-col transition-colors duration-300 selection:bg-primary-500 selection:text-white">

    <!-- Top bar promo -->
    <div class="bg-primary-600 text-white text-center text-xs sm:text-sm py-1.5 px-4">
        🚚 Gratis ongkir untuk pembelian di atas Rp 500.000 &middot; Order sekarang, kami antar hari ini!
    </div>

    <!-- Navbar -->
    <header class="sticky top-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center gap-4">

                <!-- Logo -->
                <a href="{{ route('shop.home') }}" class="flex items-center gap-2.5 shrink-0">
                    <div class="p-2 bg-primary-600 rounded-lg shadow-lg shadow-primary-600/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-wide text-slate-800 dark:text-white hidden sm:block">Sinar Wardana</span>
                </a>

                <!-- Search (desktop) -->
                <form action="{{ route('shop.products') }}" method="GET" class="hidden md:flex flex-1 max-w-xl">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4.5 w-4.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk sembako, minyak, beras..." class="block w-full pl-10 pr-4 py-2.5 border-slate-200 dark:border-slate-700 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 text-sm">
                    </div>
                </form>

                <div class="flex-1 md:hidden"></div>

                <!-- Right actions -->
                <div class="flex items-center gap-1.5 sm:gap-2">

                    <!-- Dark Mode -->
                    <button @click="darkMode = !darkMode" class="p-2 text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    <!-- Cart -->
                    <livewire:shop.cart-badge />

                    <!-- Account -->
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 p-1.5 pr-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2563EB&color=fff" class="w-8 h-8 rounded-full">
                                <span class="hidden lg:block text-sm font-medium text-slate-700 dark:text-slate-200 max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition.opacity class="absolute right-0 mt-2 w-52 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700 py-1 z-50" style="display:none">
                                @if (auth()->user()->hasRole('pelanggan'))
                                    <a href="{{ route('portal.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        Dashboard Saya
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        Dashboard Admin
                                    </a>
                                @endif
                                <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-danger-600 dark:text-danger-400 hover:bg-danger-50 dark:hover:bg-danger-900/20">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center px-3.5 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 rounded-xl text-sm font-medium text-white shadow-sm shadow-primary-600/20 hover:bg-primary-700 transition-all">Daftar</a>
                    @endauth

                    <!-- Mobile menu -->
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-slate-500 hover:text-primary-600 dark:text-slate-400 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        <svg x-show="mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Search (mobile) -->
            <form action="{{ route('shop.products') }}" method="GET" class="md:hidden pb-3">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4.5 w-4.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="block w-full pl-10 pr-4 py-2.5 border-slate-200 dark:border-slate-700 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 text-sm">
                </div>
            </form>

            <!-- Mobile menu links -->
            <nav x-show="mobileMenu" x-transition.opacity class="md:hidden pb-4 space-y-1" style="display:none">
                <a href="{{ route('shop.home') }}" class="block px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Beranda</a>
                <a href="{{ route('shop.products') }}" class="block px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Semua Produk</a>
                @guest
                    <a href="{{ route('login') }}" class="block px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Masuk</a>
                @endguest
            </nav>
        </div>
    </header>

    <!-- Page Header Slot -->
    @if (isset($header))
        <div class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-5">
                {{ $header }}
            </div>
        </div>
    @endif

    <!-- Main -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 mt-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="p-2 bg-primary-600 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-white">Sinar Wardana</span>
                    </div>
                    <p class="text-sm text-slate-400">Distributor &amp; grosir sembako terpercaya. Melayani toko, agen, dan distributor di seluruh Indonesia.</p>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-white mb-3">Belanja</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('shop.products') }}" class="hover:text-white transition-colors">Semua Produk</a></li>
                        <li><a href="{{ route('shop.home') }}" class="hover:text-white transition-colors">Promo</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-white mb-3">Akun</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        @guest
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Daftar Toko Baru</a></li>
                        @else
                            <li><a href="{{ route('portal.dashboard') }}" class="hover:text-white transition-colors">Dashboard Saya</a></li>
                        @endguest
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-white mb-3">Hubungi Kami</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li>0812-3456-7890</li>
                        <li>halo@sinarwardana.co.id</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 mt-8 pt-6 text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} {{ config('app.name', 'Sinar Wardana') }}. Seluruh hak cipta dilindungi.
            </div>
        </div>
    </footer>

    <x-toast />

    @livewireScripts
</body>
</html>
