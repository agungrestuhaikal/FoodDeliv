<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite menu (tambah/hapus dari favorite)
     */
    public function toggleMenuFavorite($menuId)
    {
        $user = Auth::user();
        
        // Cek apakah sudah ada di favorite
        $favorite = Favorite::where('user_id', $user->id)
                            ->where('menu_id', $menuId)
                            ->first();

        if ($favorite) {
            // Jika sudah ada, hapus (unfavorite)
            $favorite->delete();
            return response()->json([
                'success' => true,
                'is_favorite' => false,
                'message' => 'Menu dihapus dari favorit'
            ]);
        } else {
            // Jika belum ada, tambahkan (favorite)
            Favorite::create([
                'user_id' => $user->id,
                'menu_id' => $menuId
            ]);
            return response()->json([
                'success' => true,
                'is_favorite' => true,
                'message' => 'Menu ditambahkan ke favorit'
            ]);
        }
    }

    /**
     * Toggle favorite order (untuk repeat order)
     */
    public function toggleOrderFavorite($orderId)
    {
        $user = Auth::user();
        
        $order = Order::where('id', $orderId)
                     ->where('user_id', $user->id)
                     ->firstOrFail();

        // Toggle is_favorite
        $order->is_favorite = !$order->is_favorite;
        $order->save();

        return response()->json([
            'success' => true,
            'is_favorite' => $order->is_favorite,
            'message' => $order->is_favorite ? 'Pesanan ditandai sebagai favorit' : 'Pesanan dihapus dari favorit'
        ]);
    }

    /**
     * Halaman daftar menu favorit
     */
    public function favoriteMenus()
    {
        $user = Auth::user();
        $favoriteMenus = Menu::whereHas('favorites', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('favorites.menus', compact('favoriteMenus'));
    }

    /**
     * Halaman daftar order favorit (untuk repeat order)
     */
    public function favoriteOrders()
    {
        $user = Auth::user();
        $favoriteOrders = Order::where('user_id', $user->id)
                               ->where('is_favorite', true)
                               ->with('menu')
                               ->latest()
                               ->get();

        return view('favorites.orders', compact('favoriteOrders'));
    }

    /**
     * Repeat order (buat pesanan baru berdasarkan order favorit)
     */
    public function repeatOrder($orderId)
    {
        $user = Auth::user();
        
        $originalOrder = Order::where('id', $orderId)
                             ->where('user_id', $user->id)
                             ->where('is_favorite', true)
                             ->with('menu')
                             ->firstOrFail();

        // Buat order baru dengan data yang sama
        $newOrder = Order::create([
            'user_id' => $user->id,
            'menu_id' => $originalOrder->menu_id,
            'menu_name' => $originalOrder->menu_name,
            'quantity' => $originalOrder->quantity,
            'total_price' => $originalOrder->total_price,
            'customer_name' => $originalOrder->customer_name,
            'customer_address' => $originalOrder->customer_address,
            'status' => 'pending',
            'is_favorite' => false, // Order baru belum favorit
        ]);

        return redirect()->route('order.history')
                        ->with('success', 'Pesanan berhasil diulang!');
    }
}

