<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'menu_id',
    ];

    // Relasi: Favorite milik satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Favorite milik satu Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}

