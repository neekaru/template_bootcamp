<?php

namespace App\Livewire\Pembeli;

use App\Models\Pembeli;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
class EditProfile extends Component
{
    use WithFileUploads;

    public Pembeli $pembeli;

    #[Rule('required|string|max:255')]
    public $username;

    #[Rule('required|email|max:255')]
    public $email;

    public $newAvatar; 
    public $currentAvatarUrl;

    public function mount()
    {
        $this->pembeli = Auth::guard('pembeli')->user();
        $this->username = $this->pembeli->username;
        $this->email = $this->pembeli->email;
        
        if ($this->pembeli->avatar) {
            if (filter_var($this->pembeli->avatar, FILTER_VALIDATE_URL)) {
                $this->currentAvatarUrl = $this->pembeli->avatar;
            } else {
                $this->currentAvatarUrl = Storage::url($this->pembeli->avatar);
            }
        } else {
            $this->currentAvatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($this->pembeli->username) . '&color=7F9CF5&background=EBF4FF';
        }
    }

    public function updatedNewAvatar()
    {
        $this->validate([
            'newAvatar' => 'nullable|image|max:2048', // 2MB Max
        ]);
    }

    public function save()
    {
        $rules = [
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'newAvatar' => 'nullable|image|max:2048',
        ];

        if ($this->username !== $this->pembeli->username) {
            $rules['username'] .= '|unique:pembelis,username,' . $this->pembeli->id;
        }
        if ($this->email !== $this->pembeli->email) {
            $rules['email'] .= '|unique:pembelis,email,' . $this->pembeli->id;
        }
        
        $this->validate($rules);

        $this->pembeli->username = $this->username;
        $this->pembeli->email = $this->email;

        if ($this->newAvatar) {
            if ($this->pembeli->avatar && !filter_var($this->pembeli->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($this->pembeli->avatar);
            }
            $path = $this->newAvatar->store('avatars', 'public');
            $this->pembeli->avatar = $path;
        }

        $this->pembeli->save();
        session()->flash('success', 'Profil berhasil diperbarui.');
        $this->mount(); 
        $this->newAvatar = null; 
    }

    public function render()
    {
        return view('livewire.pembeli.edit-profile')
            ->title('Ubah Profile');
    }
} 