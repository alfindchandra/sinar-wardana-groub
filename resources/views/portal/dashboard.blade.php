<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Pelanggan - Sinar Wardana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="mx-auto max-w-7xl px-6 py-10">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-primary-600">Portal Pelanggan</p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">Selamat datang, {{ Auth::user()->name ?? 'Customer' }}</h1>
                    <p class="mt-3 max-w-2xl text-slate-600">Pantau pesanan, cek status pengiriman, dan temukan promo yang sedang berjalan dari satu tempat.</p>
                </div>
                <div class="rounded-2xl bg-slate-900 px-5 py-4 text-white">
                    <p class="text-sm text-slate-300">Status akun</p>
                    <p class="mt-1 text-xl font-semibold">Aktif</p>
                </div>
            </div>

            <div class="mt-8 grid gap-6 md:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Pesanan aktif</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">3</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Pengiriman hari ini</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">2</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Promo tersedia</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">5</p>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700">Ke dashboard admin</a>
                <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                    @csrf
                    <button type="submit" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
