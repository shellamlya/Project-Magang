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
        Schema::create('tourist_place_facility', function (Blueprint $table) {
            $table->foreignId('tourist_place_id')->constrained('tourist_places')->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained('tourist_facilities')->cascadeOnDelete();
            $table->primary(['tourist_place_id', 'facility_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourist_place_facility');
    }
};
