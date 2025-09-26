<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

class Login extends Component
{
    #[Rule('required|string')]
    public $username = '';

    #[Rule('required|string')]
    public $password = '';

    public function login()
    {
        $this->validate();

        if (Auth::guard('pembeli')->attempt(['username' => $this->username, 'password' => $this->password])) {
            session()->regenerate();
            return $this->redirect('/', navigate: true);
        }

        $this->addError('username', 'The provided credentials do not match our records.');
        $this->reset('password');
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.auth.login');
    }
} 