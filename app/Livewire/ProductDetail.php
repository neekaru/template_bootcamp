<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produk;
use App\Models\Cart; // Ensure Cart model is imported
use Illuminate\Support\Facades\Auth; // Ensure Auth facade is imported

class ProductDetail extends Component
{
    public $productId;
    public $product;
    public $quantity = 1;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->product = Produk::findOrFail($productId);
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if (!auth()->guard('pembeli')->check()) {
            session()->flash('warning', 'Silahkan login terlebih dahulu');
            return $this->redirect('/login', navigate: true);
        }

        $pembeliId = auth()->guard('pembeli')->user()->id;
        
        // Use more efficient upsert operation
        $cartItem = \App\Models\Cart::where('produk_id', $this->productId)
            ->where('pembeli_id', $pembeliId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('qty', $this->quantity);
        } else {
            \App\Models\Cart::create([
                'pembeli_id' => $pembeliId,
                'produk_id' => $this->productId,
                'qty' => $this->quantity
            ]);
        }

        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang!');
        return $this->redirect('/cart', navigate: true);
    }

    public function render()
    {
        return view('livewire.product-detail', [
            'product' => $this->product,
            'quantity' => $this->quantity
        ]);
    }

    public function buyNow()
    {
        if (!Auth::guard('pembeli')->check()) {
            session()->put('url.intended', route('cart.index'));
            session()->flash('warning', 'Silahkan login terlebih dahulu untuk melanjutkan.');
            return $this->redirect(route('login'), navigate: true);
        }

        $pembeliId = Auth::guard('pembeli')->id();

        // Logic is similar to addToCart, using $this->productId and $this->quantity
        $cartItem = Cart::where('pembeli_id', $pembeliId)
            ->where('produk_id', $this->productId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('qty', $this->quantity);
        } else {
            Cart::create([
                'pembeli_id' => $pembeliId,
                'produk_id'  => $this->productId,
                'qty'        => $this->quantity,
            ]);
        }

        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang dan Anda akan diarahkan ke keranjang!');
        return $this->redirect(route('cart.index'), navigate: true);
    }
}