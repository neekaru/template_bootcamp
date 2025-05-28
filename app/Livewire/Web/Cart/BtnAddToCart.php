<?php

namespace App\Livewire\Web\Cart;

use App\Models\Cart;
use Livewire\Component;

class BtnAddToCart extends Component
{   
    /**
     * product_id
     *
     * @var mixed
     */
    public $produk_id;
    
    /**
     * addToCart
     *
     * @param  mixed $produk_id
     * @return void
     */
    public function addToCart()
    {
        //check user is logged in
        if(!auth()->guard('pembeli')->check()) {

            session()->flash('warning', 'Silahkan login terlebih dahulu');

            return $this->redirect('/login', navigate: true);
        }
        
        //check cart
        $item = Cart::where('produk_id', $this->produk_id)
                    ->where('pembeli_id', auth()->guard('pembeli')->user()->id)
                    ->first();
        
        //if cart already exist
        if ($item) {

            //update cart qty
            $item->increment('qty');

        } else {

            //store cart
            $item = Cart::create([
                'pembeli_id'   => auth()->guard('pembeli')->user()->id,
                'produk_id'    => $this->produk_id,
                'qty'           => 1
            ]);

        }

        // session flash
        session()->flash('success', 'Produk ditambahkan ke keranjang');

        //redirect to cart
        return $this->redirect('/cart', navigate: true);

    }
    
    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.web.cart.btn-add-to-cart');
    }
}