<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Menu milik satu restoran
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Relasi: Menu bisa punya banyak pesanan
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    // Relasi: Menu punya banyak review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relasi: Menu punya banyak favorite
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}