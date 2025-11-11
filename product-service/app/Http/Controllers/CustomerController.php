<?php
// app/Http/Controllers/CustomerController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomerController extends Controller
{
    protected $api = 'http://127.0.0.1:5002';

    public function index()
    {
        return view('customer.index');
    }

    public function menus()
    {
        $response = Http::get("{$this->api}/menus");
        $menus = $response->successful() ? $response->json() : [];
        return view('customer.menus', compact('menus'));
    }

    public function menuShow($id)
    {
        $response = Http::get("{$this->api}/menus/{$id}");
        if ($response->failed()) abort(404);
        $menu = $response->json();
        return view('customer.menu-show', compact('menu'));
    }

    public function orderStore(Request $request)
    {
        $request->validate([
            'menu_id' => 'required', 'customer_name' => 'required', 'quantity' => 'required|min:1'
        ]);

        Http::post("{$this->api}/orders", $request->only(['menu_id', 'quantity', 'customer_name', 'table_number']));
        return back()->with('success', 'Pesanan berhasil!');
    }

    public function reviewStore(Request $request)
    {
        $request->validate([
            'menu_id' => 'required', 'customer_name' => 'required', 'rating' => 'required|in:1,2,3,4,5'
        ]);

        Http::post("{$this->api}/reviews", $request->only(['menu_id', 'customer_name', 'rating', 'comment']));
        return back()->with('success', 'Ulasan berhasil!');
    }
}