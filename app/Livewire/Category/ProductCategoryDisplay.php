<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Produk;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Support\Str;

class ProductCategoryDisplay extends Component
{
    public $categoriesWithProducts = [];

    public function mount()
    {
        $categoryNames = [
            'Perabotan Rumah Tangga',
            'Dekorasi Rumah',
            'Aksesoris',
        ];

        $this->categoriesWithProducts = collect($categoryNames)->map(function ($catName) {
            $category = Category::where('name', $catName)->first();
            $products = Produk::where('kategori_produk', $catName)
                ->latest()
                ->take(3)
                ->get()
                ->map(function ($produk) {
                    return [
                        'id' => $produk->id,
                        'name' => $produk->nama_produk,
                        'price' => 'Rp.' . number_format($produk->harga, 0, ',', '.'),
                        'rating' => 4, // You can replace with real rating if available
                        'image' => $produk->image_url ?? 'https://via.placeholder.com/150?text=No+Image',
                    ];
                });
            return [
                'name' => $catName,
                'products' => $products,
            ];
        })->toArray();
    }

    public function addToCart($produkId)
    {
        if (!auth()->guard('pembeli')->check()) {
            session()->flash('warning', 'Silahkan login terlebih dahulu');
            return $this->redirect('/login', navigate: true);
        }
        $pembeliId = auth()->guard('pembeli')->user()->id;
        $cartItem = Cart::where('produk_id', $produkId)
            ->where('pembeli_id', $pembeliId)
            ->first();
        if ($cartItem) {
            $cartItem->increment('qty');
        } else {
            Cart::create([
                'pembeli_id' => $pembeliId,
                'produk_id' => $produkId,
                'qty' => 1
            ]);
        }
        session()->flash('message', 'Produk berhasil ditambahkan ke keranjang!');
        return $this->redirect('/cart', navigate: true);
    }

    public function render()
    {
        return view('livewire.category.product-category-display');
    }
}