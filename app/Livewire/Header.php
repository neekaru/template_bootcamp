<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Header extends Component
{
    public function render()
    {
        $isAuthenticated = Auth::guard('pembeli')->check();
        return view('livewire.header', [
            'isAuthenticated' => $isAuthenticated
        ]);
    }
}