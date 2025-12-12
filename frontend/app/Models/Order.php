<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Pastikan semua kolom ini ada di $fillable biar bisa di-input
    protected $fillable = [
        'user_id',          // <--- PENTING: ID Customer
        'menu_id',
        'menu_name',
        'quantity',
        'total_price',
        'customer_name',
        'customer_address',
        'status',
        'is_favorite',      // Untuk favorite order / repeat order
    ];

    // 1. Relasi ke Menu (Order milik satu Menu)
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // 2. Relasi ke User (Order milik satu User/Customer)
    // INI YANG HILANG DAN BIKIN ERROR TADI
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}