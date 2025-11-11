<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;

// HALAMAN UTAMA
Route::get('/', fn() => view('welcome_page'))->name('home');
Route::get('/restaurant', fn() => view('restaurant_dashboard'))->name('restaurant.dashboard');

// ADMIN: MENU
Route::resource('menus', MenuController::class);

Route::get('/order/history', [OrderController::class, 'history'])->name('order.history');

// CUSTOMER: ORDER
Route::resource('order', OrderController::class)->only(['index', 'create', 'show']);
Route::post('order/orders', [OrderController::class, 'orderStore'])->name('order.orders.store');
Route::post('order/reviews', [OrderController::class, 'reviewStore'])->name('order.reviews.store');

