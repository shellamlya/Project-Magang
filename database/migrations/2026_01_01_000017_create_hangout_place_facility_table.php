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
        Schema::create('hangout_place_facility', function (Blueprint $table) {
            $table->foreignId('hangout_place_id')->constrained('hangout_places')->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained('hangout_facilities')->cascadeOnDelete();
            $table->primary(['hangout_place_id', 'facility_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hangout_place_facility');
    }
};
