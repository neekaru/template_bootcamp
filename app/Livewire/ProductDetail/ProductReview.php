<?php

namespace App\Livewire\ProductDetail;

use Livewire\Component;

class ProductReview extends Component
{
    public $overallRating = 4.0;
    public $maxRating = 5.0;
    public $satisfactionPercentage = 95;

    // Dummy data for general review photos/videos
    public $generalReviewPhotos = [
        'https://cdn.dummyjson.com/products/images/furniture/Wooden%20Bookshelf/1.png',
        'https://cdn.dummyjson.com/products/images/furniture/Wooden%20Bookshelf/2.png',
        'https://cdn.dummyjson.com/products/images/furniture/Wooden%20Bookshelf/3.png',
        'https://cdn.dummyjson.com/products/images/furniture/Wooden%20Bookshelf/4.png',
        'https://cdn.dummyjson.com/products/images/furniture/Wooden%20Table/1.png',
        'https://cdn.dummyjson.com/products/images/furniture/Wooden%20Table/2.png', // Base for "90+"
    ];
    public $countOverlayText = "90+";

    // Dummy data for a specific user review
    public $userReview = [
        'avatar' => null, // Will use a placeholder icon
        'name' => 'User',
        'text' => 'Lampunya super aesthetic dan cocok banget buat lampu tidur. Cahayanya hangat, bikin kamar terasa cozy. Ukurannya juga pas, nggak makan tempat. Dikirim cepat dan packing aman. Udah beli 2 buat kamar sendiri dan adik!',
        'photos' => [
            'https://cdn.dummyjson.com/products/images/home-decoration/Plant%20Pot/1.png',
            'https://cdn.dummyjson.com/products/images/home-decoration/Plant%20Pot/2.png',
            'https://cdn.dummyjson.com/products/images/home-decoration/Plant%20Pot/3.png',
        ]
    ];

    public function render()
    {
        return view('livewire.product-detail.product-review');
    }
}
