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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Menu (Kalau menu dihapus, review ikut hilang)
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            
            // Nama Pengulas
            $table->string('customer_name');
            
            // Rating (1-5) - Validasi angka nanti di Controller
            $table->integer('rating');
            
            // Komentar (Boleh kosong/nullable)
            $table->text('comment')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};