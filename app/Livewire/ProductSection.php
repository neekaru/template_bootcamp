<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produk;

class ProductSection extends Component
{
    public function render()
    {
        $produks = Produk::with(['ratings' => function($query) {
                $query->select('produk_id', 'rating'); // Only select needed fields
            }])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->select('id', 'nama_produk', 'harga', 'foto', 'deskripsi_produk', 'kategori_produk') // Only select needed fields
            ->latest()
            ->take(6)
            ->get();
        return view('livewire.product-section', compact('produks'));
    }

    public function addToCart($produkId)
    {
        if (!auth()->guard('pembeli')->check()) {
            session()->flash('warning', 'Silahkan login terlebih dahulu');
            return $this->redirect('/login', navigate: true);
        }

        $pembeliId = auth()->guard('pembeli')->user()->id;
        $cartItem = \App\Models\Cart::where('produk_id', $produkId)
            ->where('pembeli_id', $pembeliId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('qty');
        } else {
            \App\Models\Cart::create([
                'pembeli_id' => $pembeliId,
                'produk_id' => $produkId,
                'qty' => 1
            ]);
        }

        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang!');
        return $this->redirect('/cart', navigate: true);
    }
}
