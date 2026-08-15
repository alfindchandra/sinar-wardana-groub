<div>
    <!-- Header Pendaftaran -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-13 h-13 p-3 rounded-2xl bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 mb-3 shadow-sm">
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
            </svg>
        </div>
        <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">Pendaftaran Mitra &amp; Toko</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
            Area Layanan Distribusi: <strong>Bojonegoro &amp; Tuban</strong>
        </p>
    </div>

    <!-- Form Pendaftaran -->
    <form wire:submit.prevent="submitToWhatsapp" class="space-y-4">
        
        <!-- Nama Toko & Nama Pemilik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
            <div>
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Nama Toko / Usaha <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    wire:model="store_name" 
                    placeholder="Contoh: Toko Barokah Sembako" 
                    class="block w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                >
                @error('store_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Nama Pemilik <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    wire:model="owner_name" 
                    placeholder="Contoh: H. Ahmad Subandi" 
                    class="block w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                >
                @error('owner_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- No HP / WhatsApp -->
        <div>
            <label class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                Nomor HP / WhatsApp Aktif <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <input 
                    type="tel" 
                    wire:model="phone" 
                    placeholder="Contoh: 081234567890" 
                    class="block w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                >
            </div>
            @error('phone') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Dropdown Wilayah (Kabupaten & Kecamatan) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
            
            <!-- Pilihan Kabupaten -->
            <div>
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Kabupaten <span class="text-rose-500">*</span>
                </label>
                <select 
                    wire:model.live="regency" 
                    class="block w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                >
                    <option value="">-- Pilih Kabupaten --</option>
                    <option value="Kabupaten Bojonegoro">Kabupaten Bojonegoro</option>
                    <option value="Kabupaten Tuban">Kabupaten Tuban</option>
                </select>
                @error('regency') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Pilihan Kecamatan Dinamis -->
            <div>
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Kecamatan <span class="text-rose-500">*</span>
                </label>
                <select 
                    wire:model="district" 
                    @disabled(empty($regency))
                    class="block w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <option value="">
                        {{ empty($regency) ? '-- Pilih Kabupaten Dahulu --' : '-- Pilih Kecamatan --' }}
                    </option>
                    @foreach ($districts as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
                @error('district') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

        </div>

        <!-- Alamat Lengkap -->
        <div>
            <label class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                Alamat Lengkap &amp; Patokan <span class="text-rose-500">*</span>
            </label>
            <textarea 
                wire:model="full_address" 
                rows="2" 
                placeholder="Contoh: Desa Dander RT 04/RW 01 (Depan SDN Dander 4 / Sebelah Toko Bangunan)" 
                class="block w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
            ></textarea>
            @error('full_address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Link Google Maps -->
        <div>
            <label class="block text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                Link Titik Google Maps <span class="text-slate-400 font-normal text-xs">(Opsional)</span>
            </label>
            <input 
                type="url" 
                wire:model="maps_link" 
                placeholder="https://maps.app.goo.gl/..." 
                class="block w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
            >
            @error('maps_link') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Panduan Lampiran Foto & Live Location -->
        <div class="p-3.5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-900/60 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-xs text-amber-800 dark:text-amber-300 space-y-0.5">
                <p class="font-bold">Ketentuan Foto &amp; Lokasi:</p>
                <p>Setelah menekan tombol daftar, Anda akan diarahkan ke WhatsApp Admin. Silakan langsung kirimkan **foto tampak depan toko/warung** dan **Share Live Location** pada chat WhatsApp tersebut.</p>
            </div>
        </div>

        <!-- Tombol Kirim ke WhatsApp -->
        <div class="pt-2">
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-bold text-sm sm:text-base flex items-center justify-center gap-2.5 shadow-lg shadow-emerald-600/25 transition-all duration-200 disabled:opacity-50"
            >
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                </svg>
                <span wire:loading.remove>Daftar Sekarang via WhatsApp</span>
                <span wire:loading>Menyiapkan WhatsApp...</span>
            </button>
        </div>

        <!-- Link Masuk -->
        <div class="text-center pt-2">
            <span class="text-xs text-slate-500 dark:text-slate-400">Sudah punya akun toko terdaftar?</span>
            <a href="{{ route('login') }}" wire:navigate class="text-xs font-bold text-primary-600 hover:text-primary-700 dark:text-primary-400 ml-1">
                Masuk di sini
            </a>
        </div>
    </form>
</div>