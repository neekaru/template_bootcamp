<?php

namespace App\Livewire;

use Livewire\Component;

class ProductDetail extends Component
{
    public $productId;

    public function mount($productId)
    {
        $this->productId = $productId;
        // In a real application, you would fetch product data based on $productId
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}