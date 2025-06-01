<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Header extends Component
{
    public function logout()
    {
        Auth::guard('pembeli')->logout();
        
        session()->invalidate();
        session()->regenerateToken();
        
        return $this->redirect('/', navigate: true);
    }

    public function render()
    {
        $isAuthenticated = Auth::guard('pembeli')->check();
        $user = Auth::guard('pembeli')->user();
        return view('livewire.header', [
            'isAuthenticated' => $isAuthenticated,
            'user' => $user
        ]);
    }
}