<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;


// Halaman Utama (Welcome Page)
// Memanggil file welcome_page.blade.php
Route::get('/', function () {
    return view('welcome_page'); 
});

// Halaman Customer (Consumer)
Route::get('/customer', function () {
    return view('customer_view'); 
});

// Halaman Restaurant (Provider)
Route::get('/restaurant', function () {
    return view('restaurant_dashboard'); 
});

Route::resource('menus', MenuController::class);