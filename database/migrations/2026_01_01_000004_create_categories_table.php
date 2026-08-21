<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi tabel categories.
     * Kategori dapat digunakan bersama untuk GREX (Penginapan, Wisata, Tempat Nongkrong).
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Hotel, Guest House, Villa, Homestay, dll.
            $table->string('slug')->unique();
            $table->string('service_type')->default('penginapan'); // Penginapan, Wisata, Nongkrong
            $table->string('icon')->nullable(); // FontAwesome / Bootstrap Icon class name
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
