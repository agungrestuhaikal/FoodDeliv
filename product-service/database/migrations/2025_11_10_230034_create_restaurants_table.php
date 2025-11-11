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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id(); // Kolom ID (Primary Key, Auto-Increment)
            $table->string('name', 100); // Nama Restoran
            $table->text('address');    // Alamat Restoran
            $table->string('phone', 20)->nullable(); // Nomor Telepon (Boleh kosong)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};