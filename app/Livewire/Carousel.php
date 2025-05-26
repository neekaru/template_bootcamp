<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Carousel extends Component
{
    public $title = 'Hero Banner'; // For the browser tab title
    public $mainHeading = 'Welcome to Our Store'; // Updated heading

    public $sections = [
        [
            'tab_title' => 'Handcrafted Goods',
            'description' => 'Discover unique, handcrafted items made with passion and skill. Perfect for gifts or adding a special touch to your home.',
            'preview_image' => 'https://picsum.photos/seed/crafts1/800/600' // Example image
        ],
        [
            'tab_title' => 'Artisan Products',
            'description' => 'Explore a wide range of artisan products, from pottery to textiles, each telling a unique story.',
            'preview_image' => 'https://picsum.photos/seed/crafts2/800/600' // Example image
        ],
        [
            'tab_title' => 'Support Local Crafters',
            'description' => 'By shopping with us, you support local artisans and help preserve traditional crafting techniques.',
            'preview_image' => 'https://picsum.photos/seed/crafts3/800/600' // Example image
        ],
    ];

    // activeIndex and selectSection are no longer needed here as Alpine.js handles it.

    public function render()
    {
        return view('livewire.carousel');
    }
}
