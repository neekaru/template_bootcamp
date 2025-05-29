<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Carousel extends Component
{
    public $title = 'Hero Banner'; // For the browser tab title
    public $mainHeading = 'Welcome to Our Store'; // Updated heading

    public $fixed_title = 'Dukung Produk Lokal';
    public $fixed_subtitle = 'Belanja Sekarang!';
    public $fixed_description = 'Temukan kualitas terbaik dari tangan kreatif anak bangsa.';

    public $sections = [
        [
            'category_title' => 'Perabotan Rumah',
            'preview_image' => 'https://via.placeholder.com/400x300.png?text=Perabotan+Rumah' // Placeholder image
        ],
        [
            'category_title' => 'Dekorasi Rumah',
            'preview_image' => 'https://via.placeholder.com/400x300.png?text=Dekorasi+Rumah' // Placeholder image
        ],
        [
            'category_title' => 'Aksesoris',
            'preview_image' => 'https://via.placeholder.com/400x300.png?text=Aksesoris' // Placeholder image
        ],
    ];

    // activeIndex and selectSection are no longer needed here as Alpine.js handles it.

    public function render()
    {
        return view('livewire.carousel');
    }
}
