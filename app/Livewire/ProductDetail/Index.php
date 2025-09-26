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
        $this->product = Produk::with(["ratings.customer"])
            ->withAvg("ratings", "rating")
            ->withCount("ratings")
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
        if (!auth()->guard("pembeli")->check()) {
            session()->flash("warning", "Silahkan login terlebih dahulu");
            return $this->redirect("/login", navigate: true);
        }

        $pembeliId = auth()->guard("pembeli")->user()->id;
        $cartItem = \App\Models\Cart::where("produk_id", $this->productId)
            ->where("pembeli_id", $pembeliId)
            ->first();

        if ($cartItem) {
            $cartItem->increment("qty", $this->quantity);
        } else {
            \App\Models\Cart::create([
                "pembeli_id" => $pembeliId,
                "produk_id" => $this->productId,
                "qty" => $this->quantity,
            ]);
        }

        session()->flash(
            "success",
            "Produk berhasil ditambahkan ke keranjang!"
        );
        return $this->redirect("/cart", navigate: true);
    }

    public function buyNow()
    {
        if (!auth()->guard("pembeli")->check()) {
            session()->flash("warning", "Silahkan login terlebih dahulu");
            return $this->redirect("/login", navigate: true);
        }

        $pembeliId = auth()->guard("pembeli")->user()->id;
        $cartItem = \App\Models\Cart::where("produk_id", $this->productId)
            ->where("pembeli_id", $pembeliId)
            ->first();

        if ($cartItem) {
            $cartItem->increment("qty", $this->quantity);
        } else {
            \App\Models\Cart::create([
                "pembeli_id" => $pembeliId,
                "produk_id" => $this->productId,
                "qty" => $this->quantity,
            ]);
        }

        // Redirect directly to checkout instead of cart
        return $this->redirect("/checkout", navigate: true);
    }

    public function render()
    {
        $breadcrumbs = [
            // Removed Home entry since the breadcrumb component automatically adds it
            // Attempt to get category name. Adjust if your product model doesn't have a 'category' relationship or if the category doesn't have a 'name'
            [
                "label" => $this->product->category->name ?? "Category",
                "url" => isset($this->product->category)
                    ? route("category.products", $this->product->category->name)
                    : "#",
            ],
            ["label" => $this->product->nama_produk, "url" => "#"], // Current page
        ];

        return view("livewire.product-detail.index", [
            "product" => $this->product,
            "quantity" => $this->quantity,
            "breadcrumbs" => $breadcrumbs,
            // productId is already available via $this->productId for the review component if needed
        ]);
    }
}
