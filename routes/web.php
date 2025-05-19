<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Carousel;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/carousel', Carousel::class)->name('carousel');
