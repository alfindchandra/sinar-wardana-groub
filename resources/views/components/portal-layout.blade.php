@props(['title' => 'Portal'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.theme-init')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - {{ config('app.name', 'Sinar Wardana') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-900 overflow-x-hidden selection:bg-primary-500 selection:text-white" x-data="{ mobileMenuOpen: false }">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="h-20 flex items-center justify-between px-6 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30">
            <div class="flex items-center gap-6">
                <a href="/" class="flex items-center gap-3">
                    <div class="p-2 bg-primary-600 rounded-lg shadow-lg shadow-primary-600/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-wide">Sinar Wardana</span>
                </a>
                
                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-4">
                    <a href="#" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Dashboard</a>
                    <a href="#" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Pesanan</a>
                    <a href="#" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Pengiriman</a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <!-- Dark Mode Toggle -->
                <button @click="$store.theme.toggle()" class="p-2 text-slate-500 hover:text-primary-600 dark:text-slate-400 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                    <svg x-show="!$store.theme.dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg x-show="$store.theme.dark" class="w-5 h-5" fill="none" stroke="currentColor" style="display: none;" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>

                <!-- User Dropdown -->
                <div x-data="{ open: false }" class="relative hidden md:block">
                    <button @click="open = !open" class="flex items-center gap-2 focus:outline-none rounded-xl p-1 pr-2 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <img src="https://ui-avatars.com/api/?name=User&background=2563EB&color=fff" alt="Avatar" class="w-8 h-8 rounded-full border border-slate-200 dark:border-slate-700">
                        <div class="text-left">
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-200 leading-tight">Customer</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700 py-1 z-50">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 transition-colors">
                            Profil Saya
                        </a>
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-900/20 transition-colors">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg dark:hover:bg-slate-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </header>

        <!-- Mobile Nav -->
        <div x-show="mobileMenuOpen" class="md:hidden bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
            <div class="px-4 py-2 space-y-1">
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 dark:text-slate-200 hover:text-primary-600 hover:bg-slate-50 dark:hover:bg-slate-700">Dashboard</a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 dark:text-slate-200 hover:text-primary-600 hover:bg-slate-50 dark:hover:bg-slate-700">Pesanan</a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 dark:text-slate-200 hover:text-primary-600 hover:bg-slate-50 dark:hover:bg-slate-700">Pengiriman</a>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-900/20">Keluar</button>
                </form>
            </div>
        </div>

        <main class="flex-1 p-4 md:p-6 lg:p-8">
            @if (isset($header))
                <div class="mb-6">
                    {{ $header }}
                </div>
            @endif
            
            {{ $slot }}
        </main>

        <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-6 text-center">
            <p class="text-sm text-slate-500 dark:text-slate-400">&copy; {{ date('Y') }} Sinar Wardana. All rights reserved.</p>
        </footer>
    </div>

    <x-toast />
    @livewireScripts
</body>
</html>
