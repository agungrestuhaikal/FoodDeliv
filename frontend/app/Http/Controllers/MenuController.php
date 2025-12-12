<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    // Tampilkan semua menu (Dashboard)
    public function index()
    {
        // DULU: Nembak API Python
        // $response = Http::get(...)
        
        // SEKARANG: Langsung ambil dari Database
        // (Asumsi: User yang login adalah pemilik restoran)
        // Jika belum ada Auth, bisa pakai Menu::all() dulu
        $menus = Menu::latest()->get(); 
        
        return view('menus.index', compact('menus'));
    }

    // Tampilkan Form Tambah Menu
    public function create()
    {
        return view('menus.create');
    }

    // Simpan Menu ke Database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        // 1. Upload Gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Simpan ke folder 'storage/app/public/menus'
            $imagePath = $request->file('image')->store('menus', 'public');
        }

        // 2. Simpan Data ke Database (Eloquent)
        Menu::create([
            // PENTING: Karena di migrasi ada restaurant_id, kita perlu isinya.
            // Kalau kamu sudah pakai Auth login restoran, ganti 1 dengan auth()->id()
            'restaurant_id' => 1, // <-- Default sementara biar gak error
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'image' => $imagePath, // Simpan path-nya saja (misal: menus/foto.jpg)
            'stock' => 100 // Default stock sesuai migrasi
        ]);

        return redirect()->route('restaurant.dashboard')->with('success', 'Menu ditambahkan!');
    }

    // Tampilkan Detail Menu
    public function show($id)
    {
        $menu = Menu::findOrFail($id); // Langsung cari di DB, kalau gak ada otomatis 404
        return view('menus.show', compact('menu'));
    }

    // Tampilkan Form Edit
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('menus.edit', compact('menu'));
    }

    // Update Menu di Database
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        // Data yang mau diupdate
        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description
        ];

        // Cek jika ada upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama biar gak nyampah
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            // Upload gambar baru
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($data);

        return redirect()->route('restaurant.dashboard')->with('success', 'Menu diupdate!');
    }

    // Hapus Menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        
        // Hapus file gambar dari penyimpanan
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()->route('restaurant.dashboard')->with('success', 'Menu dihapus!');
    }
}