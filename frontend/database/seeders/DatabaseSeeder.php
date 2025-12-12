<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Restoran (Satu aja buat login)
        User::create([
            'name' => 'Admin Restoran',
            'email' => 'admin@resto.com',
            'password' => Hash::make('password123'),
            'role' => 'restaurant',
        ]);

        // 2. Data Restoran (Wajib ada biar Create Menu gak error)
        Restaurant::create([
            'name' => 'Restoran Pusat',
            'address' => 'Jl. Cloud No. 99',
            'phone' => '08123456789'
        ]);

        // Tidak ada user customer yang dibuat otomatis.
        // Customer harus register sendiri lewat web.
    }
}