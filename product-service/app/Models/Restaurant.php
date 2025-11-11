<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model {
    use HasFactory;
    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = ['name', 'address', 'phone'];

    // Relasi: Satu Restoran punya banyak Menu
    public function menus() {
        return $this->hasMany(Menu::class);
    }
}