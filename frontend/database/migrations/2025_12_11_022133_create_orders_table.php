<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel menus
            // Kita pakai constrained() agar terhubung ke id di tabel menus
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            
            // Snapshot Nama Menu (Sesuai Python: menu_name TEXT NOT NULL)
            // Penting disimpan agar jika nama menu berubah, riwayat pesanan tetap sama
            $table->string('menu_name'); 
            
            // Quantity (Sesuai Python: DEFAULT 1)
            $table->integer('quantity')->default(1);
            
            // Total Price (Sesuai Python: REAL NOT NULL)
            $table->decimal('total_price', 10, 2);
            
            // Nama Pemesan
            $table->string('customer_name');
            
            // Status (Sesuai Python: DEFAULT 'done')
            $table->string('status')->default('done');
            
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};