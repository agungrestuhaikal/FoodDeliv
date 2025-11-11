<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    // ... method index dan show ada di atasnya ...
    
    /**
     * POST /api/menus
     * MENAMBAHKAN ITEM MENU BARU (TUGAS ANDA)
     */
    public function store(Request $request)
    {
        // [TUGAS ANDA]: Logika validasi dan penyimpanan data menu baru
        try {
            // 1. Validasi data
            $request->validate([
                'restaurant_id' => 'required|exists:restaurants,id', // Pastikan ID Restoran ada
                'name' => 'required|string|max:100',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
            ]);

            // 2. Simpan data ke database
            $menu = Menu::create($request->all());

            return response()->json([
                'message' => 'Menu berhasil ditambahkan', 
                'data' => $menu
            ], 201); // Status 201: Created

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validasi Gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menambahkan menu', 'error' => $e->getMessage()], 500);
        }
    }

    // ... method update dan destroy dan updateStock ada di bawahnya ...
}