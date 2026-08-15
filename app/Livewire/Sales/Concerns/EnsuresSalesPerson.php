<?php

namespace App\Livewire\Sales\Concerns;

use App\Models\SalesPerson;
use Illuminate\Support\Facades\Auth;

trait EnsuresSalesPerson
{
    public ?SalesPerson $salesPerson = null;

    public function bootEnsuresSalesPerson(): void
    {
        $this->ensureSalesPerson();
    }

    public function ensureSalesPerson(): void
    {
        $person = Auth::user()?->salesPerson;

        abort_unless($person, 403, 'Akun Anda belum terdaftar sebagai Sales. Hubungi admin untuk menghubungkan akun.');
        abort_unless($person->is_active, 403, 'Akun Sales Anda sedang nonaktif. Hubungi admin.');

        $this->salesPerson = $person;
    }
}