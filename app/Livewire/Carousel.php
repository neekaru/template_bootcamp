<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Slider;

#[Layout('components.layouts.app')]
class Carousel extends Component
{
    public $title = 'Hero Banner'; // For the browser tab title
    public $mainHeading = 'Welcome to Our Store'; // Updated heading

    public $fixed_title = 'Dukung Produk Lokal';
    public $fixed_subtitle = 'Belanja Sekarang!';
    public $fixed_description = 'Temukan kualitas terbaik dari tangan kreatif anak bangsa.';

    // activeIndex and selectSection are no longer needed here as Alpine.js handles it.

    public function render()
    {
        $sections = Slider::all()->map(function ($slider) {
            $imagePath = $slider->image ? asset('storage/' . ltrim($slider->image, '/')) : null;
            return [
                'category_title' => $slider->category_name,
                'preview_image' => $imagePath,
                'link' => $slider->link,
            ];
        })->toArray();
        return view('livewire.carousel', [
            'sections' => $sections,
        ]);
    }
}
