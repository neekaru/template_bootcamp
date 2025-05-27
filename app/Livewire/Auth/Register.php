<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\Pembeli;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

class Register extends Component
{
    #[Rule('required|string|min:3|max:50|unique:pembelis,username')]
    public $username = '';

    #[Rule('required|email|unique:pembelis,email')]
    public $email = '';

    #[Rule('required|string|min:8')]
    public $password = '';

    public function register()
    {
        $this->validate();

        $pembeli = Pembeli::create([
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::guard('pembeli')->login($pembeli);
        
        session()->regenerate();
        return $this->redirect('/', navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.auth.register');
    }
} 