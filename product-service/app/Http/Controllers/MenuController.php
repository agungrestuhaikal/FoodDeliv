<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    protected $apiUrl = 'http://localhost:5002/menus';

    public function index()
    {
        $response = Http::get($this->apiUrl);
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        $data = $request->only(['name', 'description', 'price', 'category']);
        $data['image_url'] = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $data['image_url'] = asset('storage/' . $path);
        }

        $response = Http::post($this->apiUrl, $data);

        if ($response->failed()) {
            if (isset($path)) Storage::disk('public')->delete($path);
            return back()->withErrors(['error' => 'Gagal menyimpan menu.']);
        }

        return redirect()->route('menus.index')->with('success', 'Menu ditambahkan!');
    }

    public function show($id)
    {
        $response = Http::get("{$this->apiUrl}/{$id}");
        if ($response->failed()) {
            return redirect()->route('menus.index')->withErrors(['error' => 'Menu tidak ditemukan.']);
        }
        $menu = $response->json();
        return view('menus.show', compact('menu'));
    }

    public function edit($id)
    {
        $response = Http::get("{$this->apiUrl}/{$id}");
        if ($response->failed()) {
            return redirect()->route('menus.index')->withErrors(['error' => 'Menu tidak ditemukan.']);
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

        $old = Http::get("{$this->apiUrl}/{$id}")->json();
        $data = $request->only(['name', 'description', 'price', 'category']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $data['image_url'] = asset('storage/' . $path);
        } else {
            $data['image_url'] = $old['image_url'] ?? null;
        }

        $response = Http::put("{$this->apiUrl}/{$id}", $data);

        if ($response->failed()) {
            if (isset($path)) Storage::disk('public')->delete($path);
            return back()->withErrors(['error' => 'Gagal update.']);
        }

        if (isset($path) && $old['image_url'] && str_contains($old['image_url'], '/storage/')) {
            $oldPath = str_replace(asset('storage/'), '', $old['image_url']);
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()->route('menus.index')->with('success', 'Menu diupdate!');
    }

    public function destroy($id)
    {
        $menu = Http::get("{$this->apiUrl}/{$id}")->json();
        $response = Http::delete("{$this->apiUrl}/{$id}");

        if ($response->failed()) {
            return back()->withErrors(['error' => 'Gagal hapus.']);
        }

        if ($menu['image_url'] && str_contains($menu['image_url'], '/storage/')) {
            $path = str_replace(asset('storage/'), '', $menu['image_url']);
            Storage::disk('public')->delete($path);
        }

        return redirect()->route('menus.index')->with('success', 'Menu dihapus!');
    }
}