<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi tabel lodging_facilities.
     */
    public function up(): void
    {
        Schema::create('lodging_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('facility_name');
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('lodging_facilities');
    }
};
