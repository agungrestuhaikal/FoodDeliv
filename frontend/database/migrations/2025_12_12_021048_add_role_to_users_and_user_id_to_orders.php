<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // 1. Tambah kolom ROLE di tabel users
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')->default('customer'); // Default daftar jadi customer
    });

    // 2. Tambah kolom USER_ID di tabel orders
    Schema::table('orders', function (Blueprint $table) {
        // nullable() dulu biar data lama gak error
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
    });
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}
};
