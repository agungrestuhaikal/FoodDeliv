<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    // PENTING: guarded = [] atau ['id'] agar Restaurant::create($request->all()) 
    // di controller kamu bisa jalan tanpa error.
    protected $guarded = ['id'];

    // Relasi: Satu restoran punya banyak menu
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}