<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;

class MenuController extends Controller
{
    protected $apiUrl = 'http://127.0.0.1:5002/menus';

    public function index()
    {
        $response = \Illuminate\Support\Facades\Http::get($this->apiUrl);
        $menus = $response->successful() ? $response->json() : [];
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        return view('menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        $image = $request->file('image');
        $client = new Client();

        $multipart = [
            ['name' => 'name', 'contents' => $request->name],
            ['name' => 'description', 'contents' => $request->description ?? ''],
            ['name' => 'price', 'contents' => $request->price],
            ['name' => 'category', 'contents' => $request->category],
            [
                'name' => 'image',
                'contents' => Utils::tryFopen($image->getRealPath(), 'r'),
                'filename' => $image->getClientOriginalName(),
                'headers' => ['Content-Type' => $image->getMimeType()]
            ]
        ];

        try {
            $client->post($this->apiUrl, ['multipart' => $multipart]);
            return redirect()->route('restaurant.dashboard')->with('success', 'Menu ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $response = \Illuminate\Support\Facades\Http::get("{$this->apiUrl}/{$id}");
        if ($response->failed()) {
            return redirect()->route('restaurant.dashboard')
                ->withErrors(['error' => 'Menu tidak ditemukan.']);
        }

        $menu = $response->json();
        return view('menus.show', compact('menu'));
    }

    public function edit($id)
    {
        $response = \Illuminate\Support\Facades\Http::get("{$this->apiUrl}/{$id}");
        if ($response->failed()) {
            return redirect()->route('restaurant.dashboard')
                ->withErrors(['error' => 'Menu tidak ditemukan.']);
        }

        $menu = $response->json();
        return view('menus.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        $multipart = [
            ['name' => 'name', 'contents' => $request->name],
            ['name' => 'price', 'contents' => $request->price],
            ['name' => 'category', 'contents' => $request->category],
            ['name' => 'description', 'contents' => $request->description ?? '']
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $multipart[] = [
                'name' => 'image',
                'contents' => Utils::tryFopen($image->getRealPath(), 'r'),
                'filename' => $image->getClientOriginalName(),
                'headers' => ['Content-Type' => $image->getMimeType()]
            ];
        }

        $client = new Client();
        try {
            $client->put("{$this->apiUrl}/{$id}", ['multipart' => $multipart]);
            return redirect()->route('restaurant.dashboard')->with('success', 'Menu diupdate!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        \Illuminate\Support\Facades\Http::delete("{$this->apiUrl}/{$id}");
        return redirect()->route('restaurant.dashboard')->with('success', 'Menu dihapus!');
    }
}