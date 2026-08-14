<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || false }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sinar Wardana') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-900 overflow-x-hidden selection:bg-primary-500 selection:text-white" x-data="{ sidebarOpen: false, searchOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Backdrop (Mobile) -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 flex flex-col bg-gradient-to-b from-slate-900 to-slate-800 text-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-20 px-6 border-b border-slate-700/50">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="p-2 bg-primary-600 rounded-lg shadow-lg shadow-primary-600/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-wide">Sinar Wardana</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Sidebar Navigation -->
            <div class="flex-1 overflow-y-auto overflow-x-hidden py-6 space-y-1 px-3 custom-scrollbar">
                
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-slate-300 rounded-xl hover:bg-slate-800/50 hover:text-white group transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-600/10 text-primary-400 font-medium relative before:absolute before:left-0 before:top-2 before:bottom-2 before:w-1 before:bg-primary-500 before:rounded-r-lg' : '' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-primary-500' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>

                <!-- Master Data -->
                <div x-data="{ open: {{ request()->routeIs('master-data.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-slate-300 rounded-xl hover:bg-slate-800/50 hover:text-white transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                            Master Data
                        </div>
                        <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <div x-show="open" x-transition.opacity class="pl-11 pr-4 py-2 space-y-2">
                        <a wire:navigate href="{{ route('master-data.products.index') }}" class="block text-sm transition-colors {{ request()->routeIs('master-data.products.*') ? 'text-white font-medium' : 'text-slate-400 hover:text-white' }}">Produk</a>
                        <a wire:navigate href="{{ route('master-data.categories.index') }}" class="block text-sm transition-colors {{ request()->routeIs('master-data.categories.*') ? 'text-white font-medium' : 'text-slate-400 hover:text-white' }}">Kategori</a>
                        <a wire:navigate href="{{ route('master-data.suppliers.index') }}" class="block text-sm transition-colors {{ request()->routeIs('master-data.suppliers.*') ? 'text-white font-medium' : 'text-slate-400 hover:text-white' }}">Supplier</a>
                        <a wire:navigate href="{{ route('master-data.warehouses.index') }}" class="block text-sm transition-colors {{ request()->routeIs('master-data.warehouses.*') ? 'text-white font-medium' : 'text-slate-400 hover:text-white' }}">Gudang</a>
                    </div>
                </div>

                <!-- Pembelian -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-slate-300 rounded-xl hover:bg-slate-800/50 hover:text-white transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Pembelian
                        </div>
                        <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <div x-show="open" x-transition.opacity class="pl-11 pr-4 py-2 space-y-2">
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Purchase Order</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Penerimaan Barang</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Retur Supplier</a>
                    </div>
                </div>

                <!-- Gudang -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-slate-300 rounded-xl hover:bg-slate-800/50 hover:text-white transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Gudang
                        </div>
                        <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <div x-show="open" x-transition.opacity class="pl-11 pr-4 py-2 space-y-2">
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Stok</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Mutasi</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Stock Opname</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Batch</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Kartu Stok</a>
                    </div>
                </div>

                <!-- Penjualan -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-slate-300 rounded-xl hover:bg-slate-800/50 hover:text-white transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Penjualan
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="bg-primary-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">New</span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </button>
                    <div x-show="open" x-transition.opacity class="pl-11 pr-4 py-2 space-y-2">
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Sales Order</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Invoice</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Pengiriman</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Retur Penjualan</a>
                    </div>
                </div>

                <!-- Sales & Toko -->
                <div x-data="{ open: {{ request()->routeIs('admin.stores.*') || request()->routeIs('admin.sales-orders.*') || request()->routeIs('admin.sales-targets.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-slate-300 rounded-xl hover:bg-slate-800/50 hover:text-white transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Sales & Toko
                        </div>
                        <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <div x-show="open" x-transition.opacity class="pl-11 pr-4 py-2 space-y-2">
                        <a wire:navigate href="{{ route('admin.stores.index') }}" class="block text-sm transition-colors {{ request()->routeIs('admin.stores.index') ? 'text-white font-medium' : 'text-slate-400 hover:text-white' }}">Daftar Toko</a>
                        <a wire:navigate href="{{ route('admin.stores.map') }}" class="block text-sm transition-colors {{ request()->routeIs('admin.stores.map') ? 'text-white font-medium' : 'text-slate-400 hover:text-white' }}">Peta Toko</a>
                        <a wire:navigate href="{{ route('admin.sales-orders.index') }}" class="block text-sm transition-colors {{ request()->routeIs('admin.sales-orders.*') ? 'text-white font-medium' : 'text-slate-400 hover:text-white' }}">Orderan Sales</a>
                        <a wire:navigate href="{{ route('admin.sales-targets.index') }}" class="block text-sm transition-colors {{ request()->routeIs('admin.sales-targets.*') ? 'text-white font-medium' : 'text-slate-400 hover:text-white' }}">Target Omset</a>
                    </div>
                </div>

                <!-- Pelanggan -->
                <a href="#" class="flex items-center px-4 py-3 text-slate-300 rounded-xl hover:bg-slate-800/50 hover:text-white group transition-all duration-200">
                    <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Pelanggan
                </a>

                <!-- Keuangan -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-slate-300 rounded-xl hover:bg-slate-800/50 hover:text-white transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Keuangan
                        </div>
                        <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <div x-show="open" x-transition.opacity class="pl-11 pr-4 py-2 space-y-2">
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Piutang</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Hutang</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Pembayaran</a>
                    </div>
                </div>

                <!-- Laporan -->
                <a href="#" class="flex items-center px-4 py-3 text-slate-300 rounded-xl hover:bg-slate-800/50 hover:text-white group transition-all duration-200">
                    <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan
                </a>
                
                <!-- Promo -->
                <a href="#" class="flex items-center px-4 py-3 text-slate-300 rounded-xl hover:bg-slate-800/50 hover:text-white group transition-all duration-200">
                    <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                    Promo
                    <span class="ml-auto bg-warning-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">3 Aktif</span>
                </a>

                <!-- Pengaturan -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-slate-300 rounded-xl hover:bg-slate-800/50 hover:text-white transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Pengaturan
                        </div>
                        <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <div x-show="open" x-transition.opacity class="pl-11 pr-4 py-2 space-y-2">
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Users</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Roles</a>
                        <a href="#" class="block text-sm text-slate-400 hover:text-white transition-colors">Settings</a>
                    </div>
                </div>

            </div>
            
            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-slate-700/50">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=2563EB&color=fff" alt="Avatar" class="w-10 h-10 rounded-full border-2 border-slate-700">
                    <div class="flex-1 overflow-hidden">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Admin User' }}</p>
                        <p class="text-xs text-slate-400 truncate">Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
            
            <!-- Header -->
            <header class="h-20 flex items-center justify-between px-6 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30 transition-colors duration-300">
                
                <div class="flex items-center gap-4">
                    <!-- Hamburger (Mobile) -->
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    </button>

                    <!-- Search Bar -->
                    <div class="hidden md:flex items-center relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" class="block w-full pl-10 pr-16 py-2.5 border-slate-200 dark:border-slate-700 rounded-xl leading-5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm transition-all shadow-sm" placeholder="Pencarian cepat...">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-xs text-slate-400 border border-slate-200 dark:border-slate-700 rounded px-1.5 py-0.5 bg-white dark:bg-slate-900">Ctrl+K</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    
                    <!-- Search Toggle (Mobile) -->
                    <button class="md:hidden p-2 text-slate-500 hover:text-primary-600 dark:text-slate-400 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>

                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode" class="p-2 text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    <!-- Notifications -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="relative p-2 text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-danger-500 rounded-full border-2 border-white dark:border-slate-900"></span>
                        </button>
                        
                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition.opacity class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-lg shadow-slate-200/50 dark:shadow-slate-900/50 border border-slate-100 dark:border-slate-700 py-2 z-50">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                                <h3 class="font-semibold text-slate-800 dark:text-slate-200">Notifikasi</h3>
                                <span class="text-xs text-primary-600 dark:text-primary-400 cursor-pointer">Tandai sudah dibaca</span>
                            </div>
                            <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                <a href="#" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors border-b border-slate-50 dark:border-slate-700/50 last:border-0">
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Pesanan Baru #SO-2309-001</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Toko Maju Jaya order 50 karton Indomie.</p>
                                            <p class="text-xs text-slate-400 mt-1">10 menit yang lalu</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded-full bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Stok Menipis</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Minyak Goreng Bimoli 2L sisa 10 karton.</p>
                                            <p class="text-xs text-slate-400 mt-1">1 jam yang lalu</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 mx-1"></div>

                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 focus:outline-none rounded-xl p-1 pr-2 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=2563EB&color=fff" alt="Avatar" class="w-8 h-8 rounded-full border border-slate-200 dark:border-slate-700">
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200 leading-tight">{{ Auth::user()->name ?? 'Administrator' }}</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg shadow-slate-200/50 dark:shadow-slate-900/50 border border-slate-100 dark:border-slate-700 py-1 z-50">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700 md:hidden">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ Auth::user()->name ?? 'Administrator' }}</p>
                                <p class="text-xs text-slate-500">Admin</p>
                            </div>
                            <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Profil Saya
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Pengaturan Akun
                            </a>
                            <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-danger-600 dark:text-danger-400 hover:bg-danger-50 dark:hover:bg-danger-900/20 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-6 lg:p-8 custom-scrollbar relative">
                
                @if (isset($header))
                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            {{ $header }}
                        </div>
                        @if (isset($actions))
                            <div class="flex items-center gap-2">
                                {{ $actions }}
                            </div>
                        @endif
                    </div>
                @endif

                {{ $slot }}
            </main>
            
        </div>
    </div>

    <!-- Global Toast Notifications -->
    <x-toast />

    @livewireScripts
</body>
</html>
