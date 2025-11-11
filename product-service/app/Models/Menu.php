<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MenuController extends Controller
{
    private $apiUrl = 'http://127.0.0.1:5000/api/menu';

    public function index()
    {
        $response = Http::get($this->apiUrl);

        $menus = $response->successful() ? $response->json() : [];

        return view('menu.index', compact('menus'));
    }

    public function create()
    {
        return view('menu.create');
    }

    public function store(Request $request)
    {
        $response = Http::post($this->apiUrl . '/store', [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'image' => $request->image,
        ]);

        return redirect()->route('menu.index');
    }
}
