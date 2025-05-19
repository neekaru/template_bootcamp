<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Carousel extends Component
{
    public $title = 'Agricultural Innovation'; // For the browser tab title
    public $mainHeading = 'Driving transformation with innovation';

    public $sections = [
        [
            'tab_title' => 'Crop Assure',
            'description' => 'End-to-end Crop Management & Decision Support Solution assisting farmers with actionable farm-specific advisories backed with hyperspectral imaging.',
            'preview_image' => 'https://picsum.photos/seed/dronefield/400/300'
        ],
        [
            'tab_title' => 'Seed Assure',
            'description' => 'Advanced seed analysis and quality assurance ensuring optimal crop yield and resilience. Leveraging cutting-edge technology for seed viability and genetic purity assessment.',
            'preview_image' => 'https://picsum.photos/seed/grainstorage/400/300'
        ],
        [
            'tab_title' => 'Source Assure',
            'description' => 'Comprehensive supply chain traceability and sustainability verification, empowering consumers and businesses with transparent and ethical sourcing information.',
            'preview_image' => 'https://picsum.photos/seed/farmteam/400/300'
        ],
    ];

    public $activeIndex = 1; // Default to "Seed Assure"

    public function selectSection($index)
    {
        $this->activeIndex = $index;
    }

    public function render()
    {
        return view('livewire.carousel');
    }
}
