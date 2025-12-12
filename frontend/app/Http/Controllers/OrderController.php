<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Jangan lupa import ini

class OrderController extends Controller
{
    // 1. Halaman Utama Pelanggan (Lihat Menu)
    public function index()
    {
        $menus = Menu::all();
        return view('order.index', compact('menus'));
    }

    // 2. Dashboard Restoran (Lihat Pesanan Masuk)
    public function restaurantIndex()
    {
        // Admin Restoran melihat SEMUA pesanan
        $orders = Order::with('menu', 'user')->latest()->get();
        return view('restaurant_dashboard', compact('orders'));
    }

    // 3. Halaman Form Order
    public function create(Request $request)
    {
        $menu = null;
        if ($request->has('menu_id')) {
            $menu = Menu::find($request->menu_id);
        }
        return view('order.create', compact('menu'));
    }

    // 4. Detail Menu
    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        return view('order.show', compact('menu'));
    }

    // 5. PROSES ORDER (Simpan Pesanan)
    public function orderStore(Request $request)
    {
        // Validasi input
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'customer_address' => 'required|string',
            // customer_name kita ambil dari Auth, tapi kalau mau diedit boleh divalidasi juga
            'customer_name' => 'required|string|max:255', 
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        $totalPrice = $menu->price * $request->quantity;

        Order::create([
            'user_id' => Auth::id(), // <--- PENTING: Link ke akun yang login
            'menu_id' => $menu->id,
            'menu_name' => $menu->name,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'status' => 'pending' // Default biasanya pending dulu, bukan done
        ]);

        return redirect()->route('order.history')->with('success', 'Pesanan berhasil dibuat! Silakan tunggu.');
    }

    // 6. Riwayat Pesanan Saya (Otomatis Deteksi User)
    public function history()
    {
        // Ambil order milik user yang sedang login saja
        $orders = Order::where('user_id', Auth::id())
                    ->with('menu') // Load relasi menu biar efisien
                    ->latest()
                    ->get();

        return view('order.history', compact('orders'));
    }

    // 7. Simpan Review/Ulasan (Otomatis Nama User)
    public function reviewStore(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string'
        ]);

        Review::create([
            'menu_id' => $request->menu_id,
            'customer_name' => Auth::user()->name, // <--- PENTING: Otomatis pakai nama akun
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}