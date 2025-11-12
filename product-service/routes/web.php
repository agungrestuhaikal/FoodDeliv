<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;

Route::get('/', fn() => view('welcome_page'))->name('home');

Route::get('/restaurant', [OrderController::class, 'restaurantIndex'])->name('restaurant.dashboard');

Route::resource('menus', MenuController::class); 

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/'); 
})->name('logout.custom');

Route::get('/order/history', [OrderController::class, 'history'])->name('order.history'); 
Route::post('order/orders', [OrderController::class, 'orderStore'])->name('order.orders.store');
Route::post('order/reviews', [OrderController::class, 'reviewStore'])->name('order.reviews.store');

Route::get('/order', [OrderController::class, 'index'])->name('order.index'); 

Route::resource('order', OrderController::class)->only(['create', 'show']);