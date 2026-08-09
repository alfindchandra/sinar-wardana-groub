<x-app-layout>
    <x-slot name="header">
        <div>
            <!-- Breadcrumb -->
            <nav class="flex text-sm text-slate-500 dark:text-slate-400 mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 md:ml-2 font-medium text-slate-700 dark:text-slate-200">Dashboard</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                Dashboard Utama
            </h2>
        </div>
    </x-slot>

    <x-slot name="actions">
        <button type="button" onclick="toast('success', 'Laporan sedang disiapkan dan akan diunduh otomatis.', 'Export Dimulai')" class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-medium text-sm text-slate-700 dark:text-slate-300 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-slate-900 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Export Laporan
        </button>
        <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-xl font-medium text-sm text-white shadow-sm shadow-primary-600/20 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-slate-900 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Penjualan (SO)
        </button>
    </x-slot>

    <div class="space-y-6">
        
        <!-- Welcome Banner -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-500/10 to-transparent"></div>
            <!-- Decorative circle -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary-500/10 rounded-full blur-2xl"></div>
            
            <div class="relative p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="hidden sm:flex w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl shadow-lg shadow-primary-500/30 items-center justify-center text-white shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Selamat Datang, {{ Auth::user()->name ?? 'Administrator' }}! 👋</h3>
                        <p class="text-slate-500 dark:text-slate-400 mt-1">Ini adalah ringkasan performa Sinar Wardana hari ini. Anda memiliki <span class="font-semibold text-primary-600 dark:text-primary-400">3 pesanan baru</span> yang perlu diproses.</p>
                    </div>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <button class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl font-medium text-sm text-slate-700 dark:text-slate-200 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-600 transition-all">
                        Buat PO
                    </button>
                    <button class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2.5 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 border border-primary-100 dark:border-primary-800 rounded-xl font-medium text-sm hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-all">
                        Input Stok
                    </button>
                </div>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            
            <!-- Card 1 -->
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Penjualan Hari Ini</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Rp 24.500.000</h4>
                    </div>
                    <div class="p-2.5 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="flex items-center text-success-600 dark:text-success-400 font-medium bg-success-50 dark:bg-success-900/20 px-2 py-0.5 rounded-md">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        12.5%
                    </span>
                    <span class="text-slate-400 ml-2">vs kemarin</span>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Penjualan Bulan Ini</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">1,245 SO</h4>
                    </div>
                    <div class="p-2.5 bg-success-100 dark:bg-success-900/30 text-success-600 dark:text-success-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="flex items-center text-success-600 dark:text-success-400 font-medium bg-success-50 dark:bg-success-900/20 px-2 py-0.5 rounded-md">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        8.2%
                    </span>
                    <span class="text-slate-400 ml-2">vs bulan lalu</span>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Omzet Bulan Ini</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Rp 1.45M</h4>
                    </div>
                    <div class="p-2.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="flex items-center text-success-600 dark:text-success-400 font-medium bg-success-50 dark:bg-success-900/20 px-2 py-0.5 rounded-md">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        15.3%
                    </span>
                    <span class="text-slate-400 ml-2">vs bulan lalu</span>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Laba Kotor</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Rp 215 Juta</h4>
                    </div>
                    <div class="p-2.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="flex items-center text-success-600 dark:text-success-400 font-medium bg-success-50 dark:bg-success-900/20 px-2 py-0.5 rounded-md">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        4.1%
                    </span>
                    <span class="text-slate-400 ml-2">vs bulan lalu</span>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Piutang</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Rp 450 Juta</h4>
                    </div>
                    <div class="p-2.5 bg-danger-100 dark:bg-danger-900/30 text-danger-600 dark:text-danger-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="flex items-center text-danger-600 dark:text-danger-400 font-medium bg-danger-50 dark:bg-danger-900/20 px-2 py-0.5 rounded-md">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        12.5%
                    </span>
                    <span class="text-slate-400 ml-2">naik vs lalu</span>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Hutang</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Rp 120 Juta</h4>
                    </div>
                    <div class="p-2.5 bg-warning-100 dark:bg-warning-900/30 text-warning-600 dark:text-warning-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="flex items-center text-success-600 dark:text-success-400 font-medium bg-success-50 dark:bg-success-900/20 px-2 py-0.5 rounded-md">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        5.2%
                    </span>
                    <span class="text-slate-400 ml-2">turun vs lalu</span>
                </div>
            </div>

            <!-- Card 7 -->
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Toko Aktif</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">458</h4>
                    </div>
                    <div class="p-2.5 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="flex items-center text-success-600 dark:text-success-400 font-medium bg-success-50 dark:bg-success-900/20 px-2 py-0.5 rounded-md">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        12 Toko
                    </span>
                    <span class="text-slate-400 ml-2">baru bulan ini</span>
                </div>
            </div>

            <!-- Card 8 -->
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Jumlah Supplier</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">32</h4>
                    </div>
                    <div class="p-2.5 bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="flex items-center text-slate-600 dark:text-slate-400 font-medium bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md">
                        Stabil
                    </span>
                    <span class="text-slate-400 ml-2">vs bulan lalu</span>
                </div>
            </div>

            <!-- Card 9: Produk Hampir Habis -->
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Produk Hampir Habis</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">18</h4>
                    </div>
                    <div class="p-2.5 bg-danger-100 dark:bg-danger-900/30 text-danger-600 dark:text-danger-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <a href="#produk-hampir-habis" class="flex items-center text-danger-600 dark:text-danger-400 font-medium bg-danger-50 dark:bg-danger-900/20 px-2 py-0.5 rounded-md hover:bg-danger-100 dark:hover:bg-danger-900/40 transition-colors">
                        Perlu Restock
                    </a>
                    <span class="text-slate-400 ml-2">di bawah min. stok</span>
                </div>
            </div>

            <!-- Card 10: Produk Terlaris -->
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Produk Terlaris</p>
                        <h4 class="text-lg font-bold text-slate-800 dark:text-white mt-1 truncate">Beras BMW 5Kg</h4>
                    </div>
                    <div class="p-2.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg group-hover:scale-110 transition-transform shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="flex items-center text-indigo-600 dark:text-indigo-400 font-medium bg-indigo-50 dark:bg-indigo-900/20 px-2 py-0.5 rounded-md">
                        1.250 pcs
                    </span>
                    <span class="text-slate-400 ml-2">bulan ini</span>
                </div>
            </div>

            <!-- Card 11: Sales Terbaik -->
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Sales Terbaik</p>
                        <h4 class="text-lg font-bold text-slate-800 dark:text-white mt-1 truncate">Dimas Prasetyo</h4>
                    </div>
                    <div class="p-2.5 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg group-hover:scale-110 transition-transform shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="flex items-center text-purple-600 dark:text-purple-400 font-medium bg-purple-50 dark:bg-purple-900/20 px-2 py-0.5 rounded-md">
                        Rp 350 Juta
                    </span>
                    <span class="text-slate-400 ml-2">omzet bulan ini</span>
                </div>
            </div>

            <!-- Card 12: Pengiriman Hari Ini -->
            <div class="glass-card p-5 group hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pengiriman Hari Ini</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">14</h4>
                    </div>
                    <div class="p-2.5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <a href="#jadwal-pengiriman" class="flex items-center text-blue-600 dark:text-blue-400 font-medium bg-blue-50 dark:bg-blue-900/20 px-2 py-0.5 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                        9 Dalam Perjalanan
                    </a>
                    <span class="text-slate-400 ml-2">5 selesai</span>
                </div>
            </div>

        </div>


        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Penjualan Harian -->
            <div class="glass-card p-5" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 700)">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Penjualan 7 Hari Terakhir</h3>
                    <button @click="loading = true; setTimeout(() => { loading = false; toast('info', 'Grafik penjualan diperbarui.', 'Data Dimuat Ulang') }, 700)" class="text-slate-400 hover:text-primary-600 transition-colors" title="Muat ulang data">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </div>
                <div x-show="loading" x-cloak>
                    <x-loading-skeleton type="chart" class="!p-0 !shadow-none !border-0 !bg-transparent" />
                </div>
                <div x-show="!loading" x-cloak class="relative h-72 w-full">
                    <canvas id="chartPenjualanHarian"></canvas>
                </div>
            </div>

            <!-- Penjualan Bulanan -->
            <div class="glass-card p-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Penjualan Bulanan (12 Bulan)</h3>
                    <button class="text-slate-400 hover:text-primary-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                    </button>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="chartPenjualanBulanan"></canvas>
                </div>
            </div>

            <!-- Produk Terlaris -->
            <div class="glass-card p-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Top 5 Produk Terlaris</h3>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="chartProdukTerlaris"></canvas>
                </div>
            </div>

            <!-- Sales Terbaik -->
            <div class="glass-card p-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Top 5 Salesman</h3>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="chartSalesTerbaik"></canvas>
                </div>
            </div>

            <!-- Omzet Tahunan (Area Chart) -->
            <div class="glass-card p-5 lg:col-span-2">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Omzet Tahunan</h3>
                    <span class="text-xs font-medium text-slate-400 border border-slate-200 dark:border-slate-700 rounded-full px-2.5 py-1">Tahun {{ date('Y') }}</span>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="chartOmzetTahunan"></canvas>
                </div>
            </div>
        </div>

        <!-- Tables Section -->

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Produk Hampir Habis -->
            <div id="produk-hampir-habis" class="glass-card overflow-hidden scroll-mt-24">
                <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-white/50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center">
                        <svg class="w-5 h-5 text-warning-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Produk Hampir Habis
                    </h3>
                    <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                                <th class="p-4 font-medium">Nama Produk</th>
                                <th class="p-4 font-medium text-center">Stok</th>
                                <th class="p-4 font-medium text-center">Min Stok</th>
                                <th class="p-4 font-medium text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100 dark:divide-slate-700/50">
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="p-4">
                                    <div class="font-medium text-slate-800 dark:text-slate-200">Beras BMW 5Kg</div>
                                    <div class="text-xs text-slate-500">BRG-001</div>
                                </td>
                                <td class="p-4 text-center font-bold text-danger-600 dark:text-danger-400">12</td>
                                <td class="p-4 text-center text-slate-600 dark:text-slate-400">20</td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400">Kritis</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="p-4">
                                    <div class="font-medium text-slate-800 dark:text-slate-200">Gula Gulaku 1Kg</div>
                                    <div class="text-xs text-slate-500">BRG-042</div>
                                </td>
                                <td class="p-4 text-center font-bold text-warning-600 dark:text-warning-400">45</td>
                                <td class="p-4 text-center text-slate-600 dark:text-slate-400">50</td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-warning-100 text-warning-800 dark:bg-warning-900/30 dark:text-warning-400">Warning</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="p-4">
                                    <div class="font-medium text-slate-800 dark:text-slate-200">Minyak Sania 2L</div>
                                    <div class="text-xs text-slate-500">BRG-023</div>
                                </td>
                                <td class="p-4 text-center font-bold text-danger-600 dark:text-danger-400">5</td>
                                <td class="p-4 text-center text-slate-600 dark:text-slate-400">30</td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-danger-100 text-danger-800 dark:bg-danger-900/30 dark:text-danger-400">Kritis</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="p-4">
                                    <div class="font-medium text-slate-800 dark:text-slate-200">Indomie Goreng (Dus)</div>
                                    <div class="text-xs text-slate-500">BRG-105</div>
                                </td>
                                <td class="p-4 text-center font-bold text-warning-600 dark:text-warning-400">22</td>
                                <td class="p-4 text-center text-slate-600 dark:text-slate-400">25</td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-warning-100 text-warning-800 dark:bg-warning-900/30 dark:text-warning-400">Warning</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pengiriman Hari Ini -->
            <div id="jadwal-pengiriman" class="glass-card overflow-hidden scroll-mt-24">
                <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-white/50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center">
                        <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Jadwal Pengiriman
                    </h3>
                    <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                                <th class="p-4 font-medium">No. SO / Toko</th>
                                <th class="p-4 font-medium">Sopir</th>
                                <th class="p-4 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100 dark:divide-slate-700/50">
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="p-4">
                                    <div class="font-medium text-primary-600 dark:text-primary-400 hover:underline cursor-pointer">SO-2309-125</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">Toko Berkah Makmur</div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300">A</div>
                                        <span class="text-slate-700 dark:text-slate-300">Anton (L300)</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">Dalam Perjalanan</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="p-4">
                                    <div class="font-medium text-primary-600 dark:text-primary-400 hover:underline cursor-pointer">SO-2309-128</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">Toko Jaya Abadi</div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300">B</div>
                                        <span class="text-slate-700 dark:text-slate-300">Budi (Engkel)</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800 dark:bg-success-900/30 dark:text-success-400">Terkirim</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="p-4">
                                    <div class="font-medium text-primary-600 dark:text-primary-400 hover:underline cursor-pointer">SO-2309-130</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">Warung Sembako Bu Siti</div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300">C</div>
                                        <span class="text-slate-700 dark:text-slate-300">Cipto (Grandmax)</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800 dark:bg-warning-900/30 dark:text-warning-400">Persiapan</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Summary Lists -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-8">
            
            <!-- Order Terbaru -->
            <div class="glass-card p-5">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Order Penjualan Terbaru</h3>
                <div class="space-y-4">
                    <!-- Item -->
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 flex items-center justify-center font-bold">SO</div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">SO-2309-135</p>
                                <p class="text-xs text-slate-500">Toko Bintang Rezeki • Oleh: Dimas (Sales)</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Rp 4.500.000</p>
                            <p class="text-xs text-slate-500">10 mnt lalu</p>
                        </div>
                    </div>
                    <!-- Item -->
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 flex items-center justify-center font-bold">SO</div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">SO-2309-134</p>
                                <p class="text-xs text-slate-500">Minimarket Lestari • Oleh: Rina (Sales)</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Rp 12.350.000</p>
                            <p class="text-xs text-slate-500">45 mnt lalu</p>
                        </div>
                    </div>
                    <!-- Item -->
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 flex items-center justify-center font-bold">SO</div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">SO-2309-133</p>
                                <p class="text-xs text-slate-500">Toko Anugerah • Oleh: Budi (Sales)</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Rp 2.100.000</p>
                            <p class="text-xs text-slate-500">2 jam lalu</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Piutang Jatuh Tempo -->
            <div class="glass-card p-5">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Piutang Jatuh Tempo</h3>
                <div class="space-y-4">
                    <!-- Item -->
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-danger-100 dark:bg-danger-900/30 text-danger-600 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Toko Sumber Makmur</p>
                                <p class="text-xs text-danger-500">INV-2308-050 • Telat 5 Hari</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Rp 15.000.000</p>
                            <button class="text-xs font-medium text-primary-600 hover:text-primary-700">Follow Up</button>
                        </div>
                    </div>
                    <!-- Item -->
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-danger-100 dark:bg-danger-900/30 text-danger-600 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Koperasi Karyawan Sejahtera</p>
                                <p class="text-xs text-danger-500">INV-2308-082 • Telat 2 Hari</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Rp 8.750.000</p>
                            <button class="text-xs font-medium text-primary-600 hover:text-primary-700">Follow Up</button>
                        </div>
                    </div>
                    <!-- Item -->
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-warning-100 dark:bg-warning-900/30 text-warning-600 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Warung Sembako Budi</p>
                                <p class="text-xs text-warning-600">INV-2309-012 • Hari Ini</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Rp 3.200.000</p>
                            <button class="text-xs font-medium text-primary-600 hover:text-primary-700">Follow Up</button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

    </div>

    <!-- Chart JS Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup common chart styling based on theme
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? '#334155' : '#e2e8f0';

            Chart.defaults.color = textColor;
            Chart.defaults.font.family = "'Inter', sans-serif";

            // 1. Penjualan Harian (Bar Chart)
            const ctxHarian = document.getElementById('chartPenjualanHarian').getContext('2d');
            new Chart(ctxHarian, {
                type: 'bar',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                    datasets: [{
                        label: 'Penjualan (Juta Rp)',
                        data: [15, 19, 14, 22, 28, 35, 24],
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false }
                        },
                        x: { grid: { display: false, drawBorder: false } }
                    }
                }
            });

            // 2. Penjualan Bulanan (Line Chart) - jumlah Sales Order per bulan
            const ctxBulanan = document.getElementById('chartPenjualanBulanan').getContext('2d');
            new Chart(ctxBulanan, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Jumlah SO',
                        data: [95, 110, 102, 128, 121, 140, 133, 150, 168, 0, 0, 0],
                        borderColor: '#3b82f6',
                        backgroundColor: 'transparent',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false }
                        },
                        x: { grid: { display: false, drawBorder: false } }
                    }
                }
            });

            // 3. Produk Terlaris (Horizontal Bar)
            const ctxProduk = document.getElementById('chartProdukTerlaris').getContext('2d');
            new Chart(ctxProduk, {
                type: 'bar',
                data: {
                    labels: ['Beras BMW 5Kg', 'Minyak Bimoli 2L', 'Gula Gulaku 1Kg', 'Indomie Grg', 'Telur Ayam 1Kg'],
                    datasets: [{
                        label: 'Terjual (Pcs/Kg)',
                        data: [1250, 980, 850, 620, 450],
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                        borderRadius: 6,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false }
                        },
                        y: { grid: { display: false, drawBorder: false } }
                    }
                }
            });

            // 4. Sales Terbaik (Horizontal Bar)
            const ctxSales = document.getElementById('chartSalesTerbaik').getContext('2d');
            new Chart(ctxSales, {
                type: 'bar',
                data: {
                    labels: ['Dimas', 'Rina', 'Budi', 'Siti', 'Agus'],
                    datasets: [{
                        label: 'Omzet (Juta Rp)',
                        data: [350, 280, 240, 190, 150],
                        backgroundColor: '#8b5cf6',
                        borderRadius: 6,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false }
                        },
                        y: { grid: { display: false, drawBorder: false } }
                    }
                }
            });

            // 5. Omzet Tahunan (Area Chart)
            const ctxOmzet = document.getElementById('chartOmzetTahunan').getContext('2d');

            // Gradient fill
            const gradientOmzet = ctxOmzet.createLinearGradient(0, 0, 0, 400);
            gradientOmzet.addColorStop(0, 'rgba(16, 185, 129, 0.5)');
            gradientOmzet.addColorStop(1, 'rgba(16, 185, 129, 0)');

            new Chart(ctxOmzet, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Omzet (Juta Rp)',
                        data: [850, 920, 880, 1100, 1050, 1200, 1150, 1300, 1450, 0, 0, 0],
                        borderColor: '#10b981',
                        backgroundColor: gradientOmzet,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false }
                        },
                        x: { grid: { display: false, drawBorder: false } }
                    }
                }
            });

            // Listen for dark mode toggle to update chart colors
            window.addEventListener('storage', () => {
                // To be implemented: update chart config colors based on dark mode toggle
            });
        });
    </script>
</x-app-layout>
