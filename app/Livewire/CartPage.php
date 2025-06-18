<?php

namespace App\Livewire;

use App\Models\Cart;
use Livewire\Component;

class CartPage extends Component
{
    public $shipping_method = 'standard';
    public $promo_code = '';
    public $shipping_cost = 0;
    public $discount = 0;
    public $promo_applied = false;

    public function mount()
    {
        // Check if user is logged in
        if (!auth()->guard('pembeli')->check()) {
            session()->flash('warning', 'Silahkan login terlebih dahulu');
            return $this->redirect('/login', navigate: true);
        }

        // Set default shipping cost
        $this->updateShippingCost();
    }

    public function incrementQuantity($cartId)
    {
        $cartItem = Cart::where('id', $cartId)
                       ->where('pembeli_id', auth()->guard('pembeli')->user()->id)
                       ->first();

        if ($cartItem) {
            $cartItem->increment('qty');
            $this->clearCartCache(); // Clear cache after modification
            session()->flash('success', 'Kuantitas berhasil ditambah');
        }
    }

    public function decrementQuantity($cartId)
    {
        $cartItem = Cart::where('id', $cartId)
                       ->where('pembeli_id', auth()->guard('pembeli')->user()->id)
                       ->first();

        if ($cartItem && $cartItem->qty > 1) {
            $cartItem->decrement('qty');
            $this->clearCartCache(); // Clear cache after modification
            session()->flash('success', 'Kuantitas berhasil dikurangi');
        } elseif ($cartItem && $cartItem->qty == 1) {
            $this->removeItem($cartId);
        }
    }

    public function removeItem($cartId)
    {
        $cartItem = Cart::where('id', $cartId)
                       ->where('pembeli_id', auth()->guard('pembeli')->user()->id)
                       ->first();

        if ($cartItem) {
            $cartItem->delete();
            $this->clearCartCache(); // Clear cache after modification
            session()->flash('success', 'Item berhasil dihapus dari keranjang');
        }
    }

    public function updateShippingCost()
    {
        switch ($this->shipping_method) {
            case 'standard':
                $this->shipping_cost = 15000;
                break;
            case 'express':
                $this->shipping_cost = 25000;
                break;
            default:
                $this->shipping_cost = 15000;
        }
    }

    public function updatedShippingMethod()
    {
        $this->updateShippingCost();
    }

    public function applyPromoCode()
    {
        // Simple promo code validation - you can expand this
        $validPromoCodes = [
            'DISKON10' => 10, // 10% discount
            'DISKON20' => 20, // 20% discount
            'HEMAT50' => 50000, // 50k flat discount
        ];

        if (array_key_exists(strtoupper($this->promo_code), $validPromoCodes)) {
            $discountValue = $validPromoCodes[strtoupper($this->promo_code)];

            if ($discountValue <= 100) {
                // Percentage discount
                $subtotal = $this->getSubtotal();
                $this->discount = ($subtotal * $discountValue) / 100;
            } else {
                // Flat discount
                $this->discount = $discountValue;
            }

            $this->promo_applied = true;
            session()->flash('success', 'Kode promo berhasil diterapkan!');
        } else {
            $this->discount = 0;
            $this->promo_applied = false;
            session()->flash('error', 'Kode promo tidak valid!');
        }
    }

    private $cachedCartItems;

    public function getCartItems()
    {
        // Cache cart items to avoid repeated database queries
        if (!isset($this->cachedCartItems)) {
            $this->cachedCartItems = Cart::with(['produk' => function($query) {
                $query->select('id', 'nama_produk', 'harga', 'foto', 'deskripsi_produk', 'kategori_produk');
            }])->where('pembeli_id', auth()->guard('pembeli')->user()->id)
              ->get();
        }
        return $this->cachedCartItems;
    }

    // Clear cache when cart is modified
    private function clearCartCache()
    {
        $this->cachedCartItems = null;
        $this->cachedSubtotal = null;
        $this->cachedTotalItems = null;
    }

    private $cachedSubtotal;
    private $cachedTotalItems;

    public function getSubtotal()
    {
        if (!isset($this->cachedSubtotal)) {
            $this->cachedSubtotal = $this->getCartItems()->sum(function ($item) {
                return $item->qty * $item->produk->harga;
            });
        }
        return $this->cachedSubtotal;
    }

    public function getTotalItems()
    {
        if (!isset($this->cachedTotalItems)) {
            $this->cachedTotalItems = $this->getCartItems()->sum('qty');
        }
        return $this->cachedTotalItems;
    }

    public function getGrandTotal()
    {
        $subtotal = $this->getSubtotal();
        return $subtotal + $this->shipping_cost - $this->discount;
    }

    public function checkout()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            session()->flash('error', 'Keranjang kosong! Silahkan tambahkan produk terlebih dahulu.');
            return;
        }

        // Redirect to the new checkout page
        return $this->redirectRoute('checkout.index', navigate: true);
    }

    public function render()
    {
        $cartItems = $this->getCartItems();
        $subtotal = $this->getSubtotal();
        $totalItems = $this->getTotalItems();
        $grandTotal = $this->getGrandTotal();

        return view('livewire.cart-page', compact('cartItems', 'subtotal', 'totalItems', 'grandTotal'));
    }
}