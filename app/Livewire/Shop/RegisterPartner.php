<?php

namespace App\Livewire\Shop;

use Livewire\Component;

class RegisterPartner extends Component
{
    // Ganti dengan nomor WhatsApp Admin Anda (Format 62..., tanpa tanda + atau spasi)
    public $adminWhatsapp = '6281382176161';

    public $store_name = '';
    public $owner_name = '';
    public $phone = '';
    public $regency = '';      // Pilihan: Bojonegoro / Tuban
    public $district = '';     // Pilihan Kecamatan dinamis
    public $full_address = ''; // Detail jalan / RT RW / patokan
    public $maps_link = '';

    // Data Master Kecamatan Bojonegoro & Tuban
    public array $regions = [
        'Kabupaten Bojonegoro' => [
            'Balen', 'Baureno', 'Bojonegoro', 'Bubulan', 'Dander',
            'Gayam', 'Gondang', 'Kalitidu', 'Kanor', 'Kapas',
            'Kasiman', 'Kedewan', 'Kedungadem', 'Kepohbaru', 'Malo',
            'Margomulyo', 'Ngambon', 'Ngasem', 'Ngraho', 'Padangan',
            'Purwosari', 'Sekar', 'Sugihwaras', 'Sukosewu', 'Sumberrejo',
            'Tambakrejo', 'Temayang', 'Trucuk'
        ],
        'Kabupaten Tuban' => [
            'Bancar', 'Bangilan', 'Grabagan', 'Jatirogo', 'Jenu',
            'Kenduruan', 'Kerek', 'Merakurak', 'Montong', 'Palang',
            'Parengan', 'Plumpang', 'Rengel', 'Semanding', 'Senori',
            'Singgahan', 'Soko', 'Tambakboyo', 'Tuban', 'Widang'
        ],
    ];

    public function updatedRegency()
    {
        // Reset pilihan kecamatan jika kabupaten diganti
        $this->district = '';
    }

    protected function rules(): array
    {
        return [
            'store_name'   => 'required|string|max:255',
            'owner_name'   => 'required|string|max:255',
            'phone'        => 'required|numeric|digits_between:10,15',
            'regency'      => 'required|string|in:Kabupaten Bojonegoro,Kabupaten Tuban',
            'district'     => 'required|string|max:100',
            'full_address' => 'required|string|max:500',
            'maps_link'    => 'nullable|url',
        ];
    }

    protected function messages(): array
    {
        return [
            'store_name.required'   => 'Nama toko / usaha wajib diisi.',
            'owner_name.required'   => 'Nama pemilik wajib diisi.',
            'phone.required'        => 'Nomor telepon / WA wajib diisi.',
            'phone.numeric'         => 'Nomor telepon harus berupa angka.',
            'regency.required'      => 'Silakan pilih Kabupaten terlebih dahulu.',
            'district.required'     => 'Silakan pilih Kecamatan.',
            'full_address.required' => 'Detail alamat lengkap wajib diisi.',
            'maps_link.url'         => 'Format link Google Maps tidak valid (harus diawali https://).',
        ];
    }

    public function submitToWhatsapp()
    {
        $this->validate();

        $maps = $this->maps_link ? $this->maps_link : '- (Akan dikirim via share loc WA)';

        $message = "Halo Admin, saya ingin mendaftar sebagai Toko/Mitra baru:\n\n"
                 . "🏪 *Nama Toko:* {$this->store_name}\n"
                 . "👤 *Nama Pemilik:* {$this->owner_name}\n"
                 . "📞 *No. HP/WA:* {$this->phone}\n"
                 . "📍 *Kabupaten:* {$this->regency}\n"
                 . "📌 *Kecamatan:* Kec. {$this->district}\n"
                 . "🏠 *Alamat Lengkap:* {$this->full_address}\n"
                 . "🗺️ *Link Google Maps:* {$maps}\n\n"
                 . "📸 *Foto Toko / KTP:* (Foto akan saya lampirkan langsung di chat ini)\n\n"
                 . "Mohon untuk diproses pendaftarannya. Terima kasih!";

        $url = "https://wa.me/{$this->adminWhatsapp}?text=" . rawurlencode($message);

        return redirect()->away($url);
    }

    public function render()
    {
        // Ambil daftar kecamatan berdasarkan kabupaten yang sedang dipilih
        $districts = !empty($this->regency) && isset($this->regions[$this->regency]) 
            ? $this->regions[$this->regency] 
            : [];

        return view('livewire.shop.register-partner', [
            'districts' => $districts
        ])->layout('layouts.guest', ['maxWidth' => '2xl']);
    }
}