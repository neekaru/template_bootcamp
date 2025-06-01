<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;

class MobileBottomNavigation extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        if (auth()->guard('pembeli')->check()) {
            $this->cartCount = Cart::where('pembeli_id', auth()->guard('pembeli')->user()->id)->sum('qty');
        }
    }

    public function render()
    {
        return view('livewire.mobile-bottom-navigation', [
            'cartCount' => $this->cartCount
        ]);
    }
}
