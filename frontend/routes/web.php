<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN UTAMA (SMART ROUTE)
|--------------------------------------------------------------------------
| Logika: Cek dulu apakah user sudah login?
| - Jika SUDAH, arahkan langsung ke halaman sesuai role mereka.
| - Jika BELUM, tampilkan halaman Welcome Page.
*/
Route::get('/', function () {
    if (Auth::check()) {
        // Cek Role User
        if (Auth::user()->role === 'restaurant') {
            return redirect()->route('restaurant.dashboard');
        } else {
            return redirect()->route('order.index');
        }
    }
    // Jika belum login, tampilkan landing page
    return view('welcome_page');
})->name('home');

/*
|--------------------------------------------------------------------------
| 2. GROUP GUEST (Khusus yang BELUM Login)
|--------------------------------------------------------------------------
| Akses: Login, Register
*/
Route::middleware('guest')->group(function () {
    
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register (Khusus Customer)
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| 3. GROUP AUTHENTICATED (Khusus yang SUDAH Login)
|--------------------------------------------------------------------------
| Akses: Logout, Dashboard Resto, Order Customer
*/
Route::middleware('auth')->group(function () {
    
    // Logout (Bisa diakses semua role)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |----------------------------------------------------------------------
    | A. KHUSUS RESTORAN (Admin)
    |----------------------------------------------------------------------
    | Middleware: auth + role:restaurant
    */
    Route::middleware('role:restaurant')->group(function () {
        // Dashboard Restoran
        Route::get('/restaurant', [OrderController::class, 'restaurantIndex'])->name('restaurant.dashboard');

        // Kelola Menu (CRUD Lengkap)
        Route::resource('menus', MenuController::class);
    });

    /*
    |----------------------------------------------------------------------
    | B. KHUSUS CUSTOMER (Pelanggan)
    |----------------------------------------------------------------------
    | Middleware: auth + role:customer
    */
    Route::middleware('role:customer')->group(function () {
        // 1. Halaman Pesan Menu (Daftar Menu)
        Route::get('/order', [OrderController::class, 'index'])->name('order.index');

        // 2. Riwayat Pesanan (WAJIB DITARUH DI ATAS RESOURCE)
        // Penjelasan: Agar Laravel tidak mengira kata "history" adalah sebuah ID pesanan.
        Route::get('/order/history', [OrderController::class, 'history'])->name('order.history');
        
        // 3. Proses Simpan (Route Statis)
        Route::post('order/orders', [OrderController::class, 'orderStore'])->name('order.orders.store');
        Route::post('order/reviews', [OrderController::class, 'reviewStore'])->name('order.reviews.store');

        // 4. Favorite Menu & Order
        Route::post('/favorites/menu/{menuId}', [FavoriteController::class, 'toggleMenuFavorite'])->name('favorites.menu.toggle');
        Route::post('/favorites/order/{orderId}', [FavoriteController::class, 'toggleOrderFavorite'])->name('favorites.order.toggle');
        Route::get('/favorites/menus', [FavoriteController::class, 'favoriteMenus'])->name('favorites.menus');
        Route::get('/favorites/orders', [FavoriteController::class, 'favoriteOrders'])->name('favorites.orders');
        Route::post('/favorites/repeat-order/{orderId}', [FavoriteController::class, 'repeatOrder'])->name('favorites.repeat');

        // 5. Detail Menu & Form Pesan (Resource / Wildcard)
        // PENTING: Ini harus paling bawah karena pola URL-nya '/order/{id}'
        Route::resource('order', OrderController::class)->only(['create', 'show']);
    });

});