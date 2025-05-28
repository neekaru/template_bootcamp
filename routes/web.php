<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;

Route::get('/', function () {
    return view('layouts.main'); // This view will just extend the app layout
});

// Authentication Routes (Livewire-based for Pembeli)
Route::get('login', Login::class)->name('login')->middleware('guest:pembeli');
Route::get('register', Register::class)->name('register')->middleware('guest:pembeli');

// Fallback to controller-based auth (if needed)
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('register', [RegisterController::class, 'register'])->name('register.submit');

// Protected routes for Pembeli
Route::middleware(['auth:pembeli'])->prefix('pembeli')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Protected routes for admin (Filament)
Route::middleware(['auth'])->group(function () {
    // Any routes that should be protected by default auth guard (web)
});

//route product show
Route::get('/produk/{slug}', \App\Livewire\Web\Produk\Show::class)->name('web.produk.show');

// About Us page
Route::get('/about', \App\Livewire\AboutUs::class)->name('about');
