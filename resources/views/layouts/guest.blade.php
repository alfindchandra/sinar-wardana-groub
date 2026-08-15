@props(['maxWidth' => 'md'])

@php
    $maxWidthClass = match ($maxWidth) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        '7xl' => 'max-w-7xl',
        default => 'max-w-md',
    };
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @include('partials.theme-init')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-full flex flex-col justify-center selection:bg-primary-500 selection:text-white">
        
        <!-- Ornamen Latar Belakang -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-primary-500/10 dark:bg-primary-500/5 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-amber-500/10 dark:bg-amber-500/5 blur-3xl"></div>
        </div>

        <div class="w-full {{ $maxWidthClass }} mx-auto px-4 sm:px-6 py-6 sm:py-10 flex flex-col justify-center">
            
            <!-- Tombol Kembali ke Beranda -->
            <div class="mb-4">
                <a href="{{ route('shop.home') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Belanja</span>
                </a>
            </div>

            <!-- Kartu Kontainer Utama -->
            <div class="w-full bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800 rounded-3xl p-5 sm:p-8 shadow-xl shadow-slate-200/50 dark:shadow-none">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-xs text-slate-400 dark:text-slate-500">
                &copy; {{ date('Y') }} {{ config('app.name', 'Toko Sembako') }}. All rights reserved.
            </div>

        </div>
    </body>
</html>