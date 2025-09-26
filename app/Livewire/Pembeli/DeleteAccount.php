<?php

namespace App\Livewire\Pembeli;

use App\Models\Pembeli;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DeleteAccount extends Component
{
    public function confirmDeleteAccount()
    {
        LivewireAlert::title('Apakah Anda yakin?')
            ->text('Akun Anda akan dihapus secara permanen!')
            ->warning()
            ->withConfirmButton('Ya, hapus!')
            ->withCancelButton('Tidak')
            ->onConfirm('deleteAccount')
            ->show();
    }

    public function deleteAccount()
    {
        $user = Auth::guard('pembeli')->user();
        if ($user) {
            Auth::guard('pembeli')->logout();
            $pembeli = Pembeli::find($user->id);
            if ($pembeli) {
                $pembeli->delete();
            }
            session()->invalidate();
            session()->regenerateToken();
        }
        return redirect('/');
    }

    public function render()
    {
        return view('livewire.pembeli.delete-account');
    }
}
