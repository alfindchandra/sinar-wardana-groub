<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || false }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2563EB">
    <title>{{ $title ?? 'Sales App' }} - {{ config('app.name', 'Sinar Wardana') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        html { -webkit-tap-highlight-color: transparent; }
        .safe-bottom { padding-bottom: env(safe-area-inset-bottom, 0px); }
        .safe-top { padding-top: env(safe-area-inset-top, 0px); }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-950 selection:bg-primary-500 selection:text-white overscroll-none">

    <div class="max-w-lg mx-auto min-h-screen bg-slate-50 dark:bg-slate-900 relative flex flex-col shadow-xl">

        <!-- Sticky Header -->
        <header class="sticky top-0 z-30 safe-top bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3 px-4 h-14">
                @if (request()->routeIs('sales.dashboard'))
                    <div class="flex items-center gap-2.5">
                        <div class="p-1.5 bg-primary-600 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-white">Sales App</span>
                    </div>
                @elseif (request()->routeIs('sales.stores.register') || request()->routeIs('sales.stores.show'))
                    <a href="{{ route('sales.stores.index') }}" wire:navigate class="p-1.5 -ml-1.5 text-slate-500 dark:text-slate-400 rounded-lg active:bg-slate-100 dark:active:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <span class="text-sm font-bold text-slate-800 dark:text-white">{{ request()->routeIs('sales.stores.register') ? 'Daftarkan Toko Baru' : 'Detail Toko' }}</span>
                @elseif (request()->routeIs('sales.stores.index'))
                    <span class="text-sm font-bold text-slate-800 dark:text-white">Toko Saya</span>
                @elseif (request()->routeIs('sales.orders.create'))
                    <a href="{{ route('sales.orders.index') }}" wire:navigate class="p-1.5 -ml-1.5 text-slate-500 dark:text-slate-400 rounded-lg active:bg-slate-100 dark:active:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <span class="text-sm font-bold text-slate-800 dark:text-white">Buat Order Baru</span>
                @elseif (request()->routeIs('sales.orders.show'))
                    <a href="{{ route('sales.orders.index') }}" wire:navigate class="p-1.5 -ml-1.5 text-slate-500 dark:text-slate-400 rounded-lg active:bg-slate-100 dark:active:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <span class="text-sm font-bold text-slate-800 dark:text-white">Detail Order</span>
                @elseif (request()->routeIs('sales.orders.index'))
                    <span class="text-sm font-bold text-slate-800 dark:text-white">Orderan Saya</span>
                @elseif (request()->routeIs('sales.targets'))
                    <span class="text-sm font-bold text-slate-800 dark:text-white">Target Omset & Komisi</span>
                @elseif (request()->routeIs('sales.profile'))
                    <span class="text-sm font-bold text-slate-800 dark:text-white">Profil Saya</span>
                @endif

                <div class="ml-auto flex items-center gap-1">
                    <button @click="darkMode = !darkMode" class="p-2 text-slate-400 hover:text-primary-600 rounded-lg active:bg-slate-100 dark:active:bg-slate-800">
                        <svg x-show="!darkMode" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-1 pb-28 px-4 pt-4">
            {{ $slot }}
        </main>

        <!-- Floating Action Button -->
        @if (! isset($hideFab))
            <div class="fixed inset-x-0 bottom-24 z-40 pointer-events-none">
                <div class="max-w-lg mx-auto relative px-4">
                    <a href="{{ route('sales.orders.create') }}" wire:navigate class="pointer-events-auto absolute right-4 bottom-0 inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary-600 text-white shadow-lg shadow-primary-600/40 active:scale-95 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </a>
                </div>
            </div>
        @endif

        <!-- Bottom Nav -->
        <nav class="fixed bottom-0 left-0 right-0 z-30 safe-bottom">
            <div class="max-w-lg mx-auto bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 flex items-stretch">
                <a href="{{ route('sales.dashboard') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center gap-0.5 py-2.5 {{ request()->routeIs('sales.dashboard') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-400' }}">
                    <svg class="w-5 h-5" fill="{{ request()->routeIs('sales.dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-[10px] font-medium">Beranda</span>
                </a>
                <a href="{{ route('sales.stores.index') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center gap-0.5 py-2.5 {{ request()->routeIs('sales.stores.*') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-400' }}">
                    <svg class="w-5 h-5" fill="{{ request()->routeIs('sales.stores.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="text-[10px] font-medium">Toko</span>
                </a>
                <a href="{{ route('sales.orders.index') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center gap-0.5 py-2.5 {{ request()->routeIs('sales.orders.*') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-400' }}">
                    <svg class="w-5 h-5" fill="{{ request()->routeIs('sales.orders.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="text-[10px] font-medium">Order</span>
                </a>
                <a href="{{ route('sales.targets') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center gap-0.5 py-2.5 {{ request()->routeIs('sales.targets') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-400' }}">
                    <svg class="w-5 h-5" fill="{{ request()->routeIs('sales.targets') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span class="text-[10px] font-medium">Target</span>
                </a>
                <a href="{{ route('sales.profile') }}" wire:navigate class="flex-1 flex flex-col items-center justify-center gap-0.5 py-2.5 {{ request()->routeIs('sales.profile') ? 'text-primary-600 dark:text-primary-400' : 'text-slate-400' }}">
                    <svg class="w-5 h-5" fill="{{ request()->routeIs('sales.profile') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-[10px] font-medium">Profil</span>
                </a>
            </div>
        </nav>
    </div>

    <x-toast position="top-center" />

    @livewireScripts
</body>
</html>
