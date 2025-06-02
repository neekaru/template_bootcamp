<?php

namespace App\Livewire\Category;

use Livewire\Component;

class ProductCategoryDisplay extends Component
{
    public $categoriesWithProducts = [];

    public function mount()
    {
        // Placeholder image URL for the owl lamp. User should replace this with their actual image path.
        $owlImageUrl = 'https://via.placeholder.com/150/CD853F/FFFFFF?text=Wooden+Owl+Lamp'; // Updated to better reflect a wooden owl

        $productTemplate = ['name' => 'Lampu tidur hias', 'price' => 'Rp.40.000', 'rating' => 4, 'image' => $owlImageUrl];

        $this->categoriesWithProducts = [
            [
                'name' => 'Perabotan Rumah Tangga',
                'products' => array_fill(0, 3, $productTemplate)
            ],
            [
                'name' => 'Dekorasi Rumah',
                'products' => array_fill(0, 3, $productTemplate)
            ],
            [
                'name' => 'Aksesoris',
                'products' => array_fill(0, 3, $productTemplate)
            ],
        ];
    }

    public function addToCart($productName)
    {
        // Placeholder for addToCart logic
        session()->flash('message', $productName . ' ditambahkan ke keranjang.');
    }

    public function render()
    {
        return view('livewire.category.product-category-display');
    }
}