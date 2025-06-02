<?php

namespace App\Livewire\Category;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsPage extends Component
{
    use WithPagination;

    public $categoryName;
    public $categoryDisplayName;
    public $products = [];
    public $perPage = 9;

    public function mount($categoryName)
    {
        $this->categoryName = $categoryName;
        $this->categoryDisplayName = str_replace('-', ' ', Str::title($categoryName));

        // Simulate fetching products for this category
        // Using product details from the user-provided image
        $owlImageUrl = 'https://via.placeholder.com/150/CD853F/FFFFFF?text=Wooden+Owl+Lamp';
        $productTemplate = [
            'id' => uniqid(), // Add unique ID for keying and cart
            'name' => 'Lampu tidur hias',
            'price' => 'Rp.40.000',
            'rating' => 3, // 3 stars as per user image
            'image' => $owlImageUrl
        ];

        // Generate more products for pagination demonstration
        $allProducts = [];
        for ($i = 0; $i < 25; $i++) { // e.g., 25 products for this category
            $allProducts[] = array_merge($productTemplate, ['id' => uniqid() . $i]);
        }
        $this->products = $allProducts;
    }

    public function addToCart($productName)
    {
        // Placeholder for addToCart logic
        session()->flash('message', $productName . ' ditambahkan ke keranjang.');
    }

    public function getPageName()
    {
        return 'page';
    }

    public function render()
    {
        $items = Collection::make($this->products);
        $paginatedItems = new LengthAwarePaginator(
            $items->forPage($this->getPage(), $this->perPage),
            $items->count(), $this->perPage, $this->getPage(),
            ['path' => request()->url(), 'pageName' => $this->getPageName()]
        );

        return view('livewire.category.products-page', [
            'paginatedProducts' => $paginatedItems,
        ])->layout('components.layouts.app')->title($this->categoryDisplayName . ' Products');
    }
}
