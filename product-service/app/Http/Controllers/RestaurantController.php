<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    // ... method index dan show ada di atasnya ...

    /**
     * POST /api/restaurants
     * MENAMBAHKAN DATA RESTORAN BARU (TUGAS ANDA)
     */
    public function store(Request $request)
    {
        // [TUGAS ANDA]: Logika validasi dan penyimpanan data restoran baru
        try {
            // 1. Validasi data
            $request->validate([
                'name' => 'required|string|max:100',
                'address' => 'required|string',
                'phone' => 'nullable|string|max:20',
            ]);

            // 2. Simpan data ke database
            $restaurant = Restaurant::create($request->all());

            return response()->json([
                'message' => 'Restoran berhasil ditambahkan', 
                'data' => $restaurant
            ], 201); // Status 201: Created

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validasi Gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menambahkan restoran', 'error' => $e->getMessage()], 500);
        }
    }

    // ... method update dan destroy ada di bawahnya ...
}