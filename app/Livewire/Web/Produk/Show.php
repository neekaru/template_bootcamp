<?php

namespace App\Livewire\Web\Produk;

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

        return view('livewire.web.produk.show', compact('produk'));
    }
}