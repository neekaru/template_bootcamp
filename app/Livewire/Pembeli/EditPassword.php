<?php

namespace App\Livewire\Pembeli;

use App\Models\Pembeli;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
class EditPassword extends Component
{
    public Pembeli $pembeli;

    #[Rule('required')]
    public $current_password = '';

    #[Rule('required|string|min:8|confirmed')]
    public $new_password = '';

    public $new_password_confirmation = '';

    public function mount()
    {
        $this->pembeli = Auth::guard('pembeli')->user();
    }

    public function save()
    {
        $this->validate();

        if (!Hash::check($this->current_password, $this->pembeli->password)) {
            $this->addError('current_password', 'Password lama tidak sesuai.');
            $this->reset('current_password', 'new_password', 'new_password_confirmation');
            return;
        }

        // Check if new password is the same as the old one
        if (Hash::check($this->new_password, $this->pembeli->password)) {
            $this->addError('new_password', 'Password baru tidak boleh sama dengan password lama.');
            $this->reset('new_password', 'new_password_confirmation');
            return;
        }

        $this->pembeli->password = Hash::make($this->new_password);
        $this->pembeli->save();

        session()->flash('success', 'Password berhasil diperbarui.');
        $this->reset('current_password', 'new_password', 'new_password_confirmation');
    }

    public function render()
    {
        return view('livewire.pembeli.edit-password');
    }
}
