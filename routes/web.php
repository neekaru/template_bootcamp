<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Account;
use App\Http\Controllers\MidtransController;
use App\Livewire\CheckoutPage;
use App\Livewire\Checkout\CheckoutResult;

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

// Social Login Routes
Route::get('auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('social.login');
Route::get('auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])->name('social.callback');

// Protected routes for Pembeli
Route::middleware(['auth:pembeli'])->prefix('pembeli')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //route my order
    Route::get('/my-orders', \App\Livewire\Account\MyOrders\Index::class)->name('account.my-orders.index');
    // Edit Profile Page for Pembeli
    Route::get('/edit-profile', \App\Livewire\Pembeli\EditProfile::class)->name('pembeli.edit-profile');
    Route::get('/edit-password', \App\Livewire\Pembeli\EditPassword::class)->name('pembeli.edit-password');
    Route::get('/history-order', \App\Livewire\Pembeli\HistoryOrder::class)->name('pembeli.history-order');
});

// Protected routes for admin (Filament)
Route::middleware(['auth'])->group(function () {
    // Any routes that should be protected by default auth guard (web)
});

//route product show
Route::get('/produk/{slug}', \App\Livewire\Web\Produk\Show::class)->name('web.produk.show');

// About Us page
Route::get('/about', \App\Livewire\AboutUs::class)->name('about');

// Cart Page
Route::get('/cart', \App\Livewire\CartPage::class)->name('cart.index');

// Product Detail Page (Demo)
Route::get('/product/{productId}', \App\Livewire\ProductDetail\Index::class)->name('product.detail');

// Category Page
Route::get('/category', \App\Livewire\Category\Index::class)->name('category.index');
Route::get('/category/{categoryName}', \App\Livewire\Category\ProductsPage::class)->name('category.products');

// Midtrans Routes
Route::prefix('midtrans')->group(function () {
    Route::post('/snap-token', [MidtransController::class, 'getSnapToken'])->name('midtrans.snap_token');
    Route::post('/notification', [MidtransController::class, 'notificationHandler'])->name('midtrans.notification');
    Route::get('/payment-return', [MidtransController::class, 'handlePaymentReturn'])->name('midtrans.payment_return');
});

Route::get('/checkout', CheckoutPage::class)->name('checkout.index')->middleware('auth:pembeli');
Route::get('/checkout/result/{invoice}', CheckoutResult::class)->name('checkout.result');
