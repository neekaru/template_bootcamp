<?php

namespace App\Livewire\ProductDetail;

use Livewire\Component;
use App\Models\Produk;

class Index extends Component
{
    public $productId;
    public $product;
    public $quantity = 1;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->product = Produk::with(['ratings.customer'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->findOrFail($productId);
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
        return view('livewire.product-detail.index', [
            'product' => $this->product,
            'quantity' => $this->quantity,
            // productId is already available via $this->productId for the review component if needed
        ]);
    }
}
