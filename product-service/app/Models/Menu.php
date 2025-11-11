<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model {
    use HasFactory;
    // Kolom yang boleh diisi (termasuk restaurant_id dan stock)
    protected $fillable = ['restaurant_id', 'name', 'description', 'price', 'stock'];

    // Relasi: Satu Menu dimiliki oleh satu Restoran
    public function restaurant() {
        return $this->belongsTo(Restaurant::class);
    }
}