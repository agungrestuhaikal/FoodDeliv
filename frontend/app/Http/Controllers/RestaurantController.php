<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    /**
     * GET /api/restaurants
     * TAMPILKAN SEMUA RESTORAN
     */
    public function index()
    {
        $restaurants = Restaurant::all();
        return response()->json(['data' => $restaurants], 200);
    }

    /**
     * GET /api/restaurants/{id}
     * TAMPILKAN DETAIL SATU RESTORAN
     */
    public function show($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json(['message' => 'Restoran tidak ditemukan'], 404);
        }

        // Kita load juga menunya kalau perlu
        // $restaurant->load('menus'); 

        return response()->json(['data' => $restaurant], 200);
    }

    /**
     * POST /api/restaurants
     * MENAMBAHKAN DATA RESTORAN BARU (Kode dari Kamu)
     */
    public function store(Request $request)
    {
        try {
            // 1. Validasi data
            $request->validate([
                'name' => 'required|string|max:100',
                'address' => 'required|string',
                'phone' => 'nullable|string|max:20',
            ]);

            // 2. Simpan data ke database
            // INI MEMBUTUHKAN MODEL YANG SUDAH DI-SET $guarded/fillable
            $restaurant = Restaurant::create($request->all());

            return response()->json([
                'message' => 'Restoran berhasil ditambahkan', 
                'data' => $restaurant
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validasi Gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menambahkan restoran', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * PUT/PATCH /api/restaurants/{id}
     * UPDATE DATA RESTORAN
     */
    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json(['message' => 'Restoran tidak ditemukan'], 404);
        }

        $request->validate([
            'name' => 'string|max:100',
            'address' => 'string',
            'phone' => 'nullable|string|max:20',
        ]);

        $restaurant->update($request->all());

        return response()->json([
            'message' => 'Restoran berhasil diupdate',
            'data' => $restaurant
        ], 200);
    }

    /**
     * DELETE /api/restaurants/{id}
     * HAPUS RESTORAN
     */
    public function destroy($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json(['message' => 'Restoran tidak ditemukan'], 404);
        }

        $restaurant->delete();

        return response()->json(['message' => 'Restoran berhasil dihapus'], 200);
    }
}