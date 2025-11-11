<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\RestaurantController;

// --- Endpoint untuk Restoran (CRUD) ---
Route::resource('restaurants', RestaurantController::class)->except(['create', 'edit']);

// --- Endpoint untuk Menu (CRUD) ---
Route::resource('menus', MenuController::class)->except(['create', 'edit']);

// Endpoint Kunci Integrasi: PUT /api/menus/{id}/stock
// Endpoint ini wajib dipanggil oleh Order Service (Tim Node.js/Flask)
Route::put('menus/{id}/stock', [MenuController::class, 'updateStock']);