<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    protected $orderService = 'http://127.0.0.1:5003';
    protected $menuService = 'http://127.0.0.1:5002';

    // GET /order
    public function index()
    {
        // Tampilkan daftar menu untuk memulai order
        $menusResponse = Http::get("{$this->menuService}/menus");
        $menus = $menusResponse->successful() ? $menusResponse->json() : [];
        return view('order.index', compact('menus'));
    }

    // GET /order/create?menu_id=1
    public function create(Request $request)
    {
        $menu = null;
        if ($request->has('menu_id')) {
            $response = Http::get("{$this->menuService}/menus/{$request->menu_id}");
            $menu = $response->successful() ? $response->json() : null;
        }
        return view('order.create', compact('menu'));
    }

    // GET /order/{id}
    public function show($id)
    {
        $response = Http::get("{$this->menuService}/menus/{$id}");
        if ($response->failed()) abort(404);
        $menu = $response->json();
        return view('order.show', compact('menu'));
    }

    // POST /order/orders
    public function orderStore(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|integer',
            'customer_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'table_number' => 'nullable|string'
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post("{$this->orderService}/orders", $request->only([
            'menu_id', 'quantity', 'customer_name', 'table_number'
        ]));

        if ($response->failed()) {
            return back()->with('error', 'Gagal mengirim pesanan.');
        }

        return redirect()->route('order.index')->with('success', 'Pesanan berhasil dikirim!');
    }

    // GET /order/history
    public function history(Request $request)
    {
        $customerName = $request->input('customer_name', '');
        $allOrders = Http::get("{$this->orderService}/orders")->successful() ? Http::get("{$this->orderService}/orders")->json() : [];

        if ($customerName) {
            $myOrders = array_filter($allOrders, fn($o) => $o['customer_name'] === $customerName);
        } else {
            $myOrders = [];
        }

        return view('order.history', compact('myOrders', 'customerName'));
    }

    // POST /order/reviews
    public function reviewStore(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|integer',
            'customer_name' => 'required|string|max:255',
            'rating' => 'required|in:1,2,3,4,5',
            'comment' => 'nullable|string'
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post("{$this->orderService}/reviews", $request->only([
            'menu_id', 'customer_name', 'rating', 'comment'
        ]));

        if ($response->failed()) {
            return back()->with('error', 'Gagal mengirim ulasan.');
        }

        return back()->with('success', 'Ulasan berhasil dikirim!');
    }
}
