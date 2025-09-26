<?php

namespace App\Livewire\ProductDetail;

use App\Models\Produk;
use Livewire\Component;

class Show extends Component
{
    /**
     * slug
     *
     * @var mixed
     */
    public $slug;
    
    /**
     * mount
     *
     * @param  mixed $slug
     * @return void
     */
    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        //get product by slug
        $product = Produk::query()
            ->with('category', 'ratings.customer')
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->where('slug', $this->slug)
            ->firstOrFail();

        $breadcrumbs = [
            ['label' => 'Home', 'url' => '/'],
            ['label' => $product->category->name, 'url' => route('category.products', $product->category->name)],
            ['label' => $product->name, 'url' => '#'] // Current page, no URL or URL is current page
        ];

        return view('livewire.product-detail.show', compact('product', 'breadcrumbs'));
    }
}