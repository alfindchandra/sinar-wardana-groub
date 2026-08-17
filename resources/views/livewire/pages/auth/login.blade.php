<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = Auth::user();
        $default = match (true) {
            $user->hasRole('pelanggan') => route('shop.home', absolute: false),
            $user->hasRole('sales')     => route('sales.dashboard', absolute: false),
            default                     => route('dashboard', absolute: false),
        };

        $this->redirectIntended(default: $default, navigate: true);
    }
}; ?>

<div x-data="{ showPassword: false }" class="w-full">

    <!-- Header / Brand Logo Sembako -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center text-white  mb-3.5">
            <!-- Icon Keranjang Belanja / Toko -->
            <img src="{{ asset('images/logo.png') }}" 
                alt="Logo Sembako" 
                class="w-18 h-10 object-contain"
            />
        </div>
        <h2 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">
            Masuk ke Akun
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
            Pusat belanja sembako &amp; kebutuhan grosir toko Anda
        </p>
    </div>

    <!-- Session Status Flash -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        
        <!-- Input No. Handphone / WhatsApp -->
        <div>
            <label for="phone" class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                Nomor HP / WhatsApp <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <!-- Phone Icon -->
                    <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <input 
                    wire:model="form.phone" 
                    id="phone" 
                    type="tel" 
                    name="phone" 
                    required 
                    autofocus 
                    placeholder="Contoh: 081234567890" 
                    class="block w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-shadow"
                />
            </div>
            <x-input-error :messages="$errors->get('form.phone')" class="mt-1.5" />
        </div>

        <!-- Input Password dengan Toggle Hide/Show -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Kata Sandi <span class="text-rose-500">*</span>
                </label>

            </div>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <!-- Lock Icon -->
                    <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                
                <input 
                    wire:model="form.password" 
                    id="password" 
                    :type="showPassword ? 'text' : 'password'" 
                    name="password" 
                    required 
                    autocomplete="current-password" 
                    placeholder="Masukkan kata sandi Anda" 
                    class="block w-full pl-10 pr-11 py-2.5 sm:py-3 text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-shadow"
                />

                <!-- Tombol Intip Password -->
                <button 
                    type="button" 
                    @click="showPassword = !showPassword" 
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                >
                    <svg x-show="!showPassword" class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-1.5" />
        </div>

        <!-- Ingat Saya -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="inline-flex items-center gap-2 cursor-pointer">
                <input 
                    wire:model="form.remember" 
                    id="remember" 
                    type="checkbox" 
                    class="rounded border-slate-300 dark:border-slate-700 text-primary-600 shadow-sm focus:ring-primary-500/20" 
                    name="remember"
                >
                <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 select-none">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <!-- Tombol Masuk -->
        <div class="pt-2">
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="w-full py-3 px-4 rounded-xl bg-primary-600 hover:bg-primary-700 active:scale-[0.98] text-white font-bold text-sm sm:text-base flex items-center justify-center gap-2 shadow-lg shadow-primary-600/25 transition-all duration-200 disabled:opacity-50"
            >
                <span wire:loading.remove>Masuk Sekarang</span>
                <span wire:loading>Memverifikasi...</span>
            </button>
        </div>

        <!-- Pemisah -->
        <div class="relative flex items-center justify-center my-4">
            <div class="border-t border-slate-200 dark:border-slate-700 w-full"></div>
            <span class="bg-white dark:bg-slate-900 px-3 text-[11px] font-medium text-slate-400 uppercase tracking-wider absolute">
                Belum punya akun?
            </span>
        </div>

        <!-- Link Daftar Mitra Toko via WhatsApp -->
        <div>
            <a 
                href="{{ route('shop.register-partner') }}" 
                wire:navigate 
                class="w-full py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 active:scale-[0.98] text-slate-700 dark:text-slate-200 font-semibold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all duration-200"
            >
                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                </svg>
                <span>Daftar Jadi Mitra / Toko Baru</span>
            </a>
        </div>
    </form>
</div>