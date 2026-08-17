<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ mobileMenu: false }">
<head>
    @include('partials.theme-init')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="uFELMg_VKLIT3Gnw2694BSL2NIF1ijYqFJw5KJ_lKHA" />

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
    <div class="bg-primary-600 text-white text-xs sm:text-sm py-2 px-4 shadow-sm">
        <div class="flex items-center justify-center gap-2 max-w-7xl mx-auto">
            <!-- Ikon Mobil / Truk Pengiriman -->
            <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                <circle cx="7" cy="17" r="2" stroke-width="2" />
                <circle cx="17" cy="17" r="2" stroke-width="2" />
            </svg>

            <span class="font-medium truncate sm:overflow-visible">
               Gratis ongkir untuk pembelian di atas <span class="font-bold">Rp 500.000</span> &middot; Order sekarang, kami antar hari ini!
            </span>
        </div>
    </div>

    <!-- Navbar -->
    <header class="sticky top-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center gap-4">

                <!-- Logo -->
                <a href="{{ route('shop.home') }}" class="flex items-center gap-2.5 shrink-0">
                        <img src="{{ asset('images/logo.png') }}" class="w-14 h-8" alt="Sinar Wardana">
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
                    <button @click="$store.theme.toggle()" class="p-2 text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg x-show="!$store.theme.dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="$store.theme.dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
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
                        <a href="{{ route('shop.register-partner') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 rounded-xl text-sm font-medium text-white shadow-sm shadow-primary-600/20 hover:bg-primary-700 transition-all">Daftar</a>
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
    <footer class="bg-gray-950 text-slate-300 mt-12 border-t border-red-950/30">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12">
            
            <!-- Kolom Logo & Deskripsi -->
            <div class="md:col-span-4 lg:col-span-5">
                <div class="flex items-center gap-3 mb-5">
                    <!-- Placeholder untuk Logo Anda -->
                    <div class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center shadow-inner">
                        <img src="{{ asset('images/logo.png') }}" alt="Sinar Wardana Logo" class="h-7 w-auto object-contain">
                    </div>
                    <span class="text-2xl font-extrabold tracking-tight text-white">Sinar <span class="text-red-500">Wardana</span></span>
                </div>
                <p class="text-base text-slate-400 leading-relaxed max-w-md">
                    Distributor &amp; grosir sembako terpercaya sejak 2026. Kami bangga melayani kebutuhan toko, agen, dan distributor di seluruh penjuru Indonesia dengan produk berkualitas dan harga bersaing.
                </p>
                
                <!-- Opsi: Tambahkan ikon media sosial di sini nanti -->
            </div>

            <!-- Kolom Navigasi -->
            <div class="md:col-span-8 lg:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-8">
                
                <!-- Belanja -->
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-100 mb-5">Belanja</h4>
                    <ul class="space-y-3.5 text-base text-slate-400">
                        <li>
                            <a href="{{ route('shop.products') }}" class="inline-flex items-center group transition-colors hover:text-red-400">
                                <span class="origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 w-2 h-0.5 bg-red-500 mr-0 group-hover:mr-2 rounded-full"></span>
                                Semua Produk
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('shop.home') }}" class="inline-flex items-center group transition-colors hover:text-red-400">
                                <span class="origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 w-2 h-0.5 bg-red-500 mr-0 group-hover:mr-2 rounded-full"></span>
                                Promo Spesial
                            </a>
                        </li>
                        <!-- Tambahkan kategori utama jika perlu -->
                    </ul>
                </div>

                <!-- Akun -->
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-100 mb-5">Akun</h4>
                    <ul class="space-y-3.5 text-base text-slate-400">
                        @guest
                            <li>
                                <a href="{{ route('login') }}" class="inline-flex items-center group transition-colors hover:text-red-400">
                                    <span class="origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 w-2 h-0.5 bg-red-500 mr-0 group-hover:mr-2 rounded-full"></span>
                                    Masuk
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}" class="inline-flex items-center group transition-colors hover:text-red-400">
                                    <span class="origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 w-2 h-0.5 bg-red-500 mr-0 group-hover:mr-2 rounded-full"></span>
                                    Daftar Toko Baru
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('portal.dashboard') }}" class="inline-flex items-center group transition-colors hover:text-red-400">
                                    <span class="origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 w-2 h-0.5 bg-red-500 mr-0 group-hover:mr-2 rounded-full"></span>
                                    Dashboard Saya
                                </a>
                            </li>
                            <!-- Tambahkan Pesanan Saya, dll. jika perlu -->
                        @endguest
                    </ul>
                </div>

                <!-- Hubungi Kami -->
                <div class="col-span-2 sm:col-span-1">
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-100 mb-5">Hubungi Kami</h4>
                    <ul class="space-y-4 text-base text-slate-400">
                        <li class="flex items-start gap-3">
                            <!-- Ikon Telepon/WA (SVG) -->
                            <svg class="w-5 h-5 mt-0.5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="font-medium text-slate-200">0813-8217-6161</span>
                        </li>
                        <li class="flex items-start gap-3">
                             <!-- Ikon Lokasi (SVG) -->
                            <svg class="w-5 h-5 mt-0.5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Paregan</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <!-- Bagian Bawah Footer -->
        <div class="border-t border-slate-800/60 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left text-sm text-slate-500">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Sinar Wardana') }}. Seluruh hak cipta dilindungi.</p>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-slate-300 transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-slate-300 transition-colors">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>

    <x-toast />

    @livewireScripts
</body>
</html>
