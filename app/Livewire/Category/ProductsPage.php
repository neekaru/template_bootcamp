<?php

namespace App\Livewire\Category;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use App\Models\Produk;
use App\Models\Cart;

class ProductsPage extends Component
{
    use WithPagination;

    public $categoryName;
    public $categoryDisplayName;
    public $perPage = 9;

    public function mount($categoryName)
    {
        $this->categoryName = $categoryName;
        $this->categoryDisplayName = str_replace('-', ' ', Str::title($categoryName));
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
        $products = Produk::where('kategori_produk', str_replace('-', ' ', Str::title($this->categoryName)))
            ->latest()
            ->paginate($this->perPage);
        return view('livewire.category.products-page', [
            'paginatedProducts' => $products,
            'categoryDisplayName' => $this->categoryDisplayName,
        ])->layout('components.layouts.app')->title($this->categoryDisplayName . ' Products');
    }
}
