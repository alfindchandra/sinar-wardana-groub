<?php

namespace App\Livewire\Sales;

use App\Livewire\Sales\Concerns\EnsuresSalesPerson;
use App\Models\SalesVisit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.sales')]
class Profile extends Component
{
    use EnsuresSalesPerson;

    public function title(): string
    {
        return 'Profil Saya';
    }

    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->redirect(route('login'), navigate: false);
    }

    public function render()
    {
        $totalVisits = SalesVisit::where('sales_person_id', $this->salesPerson->id)->count();
        $totalCustomers = $this->salesPerson->customers()->count();

        return view('livewire.sales.profile', [
            'totalVisits' => $totalVisits,
            'totalCustomers' => $totalCustomers,
        ]);
    }
}
