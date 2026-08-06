<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi pivot lodging_place_facility.
     * Relasi Many-to-Many antara Penginapan dan Fasilitas Penginapan.
     */
    public function up(): void
    {
        Schema::create('lodging_place_facility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lodging_id')->constrained('lodgings')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('lodging_facilities')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['lodging_id', 'facility_id']);
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('lodging_place_facility');
    }
};
