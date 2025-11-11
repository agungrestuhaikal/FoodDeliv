<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    protected $apiUrl = 'http://127.0.0.1:5002';

    public function index()
    {
        $response = Http::get("{$this->apiUrl}/orders");
        $orders = $response->successful() ? $response->json() : [];
        return view('orders.index', compact('orders'));
    }

    public function create($menuId = null)
    {
        $menus = Http::get("{$this->apiUrl}/menus")->json();
        return view('orders.create', compact('menus', 'menuId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'table_number' => 'nullable|string'
        ]);

        $response = Http::post("{$this->apiUrl}/orders", $request->only([
            'menu_id', 'quantity', 'customer_name', 'table_number'
        ]));

        if ($response->failed()) {
            return back()->withErrors(['error' => 'Gagal memesan.']);
        }

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,done,cancelled']);
        
        $response = Http::put("{$this->apiUrl}/orders/{$id}/status", [
            'status' => $request->status
        ]);

        return response()->json(['success' => $response->successful()]);
    }
}