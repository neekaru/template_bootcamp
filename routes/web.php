<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Carousel;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Livewire\Web\Produk\Show;

Route::get('/', function () {
    return view('layouts.main'); // This view will just extend the app layout
});

Route::get('/carousel', Carousel::class)->name('carousel');

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


//route product show
Route::get('/produk/{slug}', Web\Produk\Show::class)->name('web.produk.show');
