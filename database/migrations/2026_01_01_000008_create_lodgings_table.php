<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi tabel lodgings (Penginapan - Shella).
     */
    public function up(): void
    {
        Schema::create('lodgings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('owners')->onDelete('set null');
            
            $table->string('name');
            $table->text('description');
            $table->string('operational_hours')->default('24 Jam');
            $table->string('check_in')->nullable()->default('14.00 WIB');
            $table->string('check_out')->nullable()->default('12.00 WIB');
            $table->text('address')->nullable();
            $table->string('district')->nullable();
            $table->string('village')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('google_maps')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            
            $table->decimal('price_start', 12, 2)->nullable();
            $table->decimal('price_end', 12, 2)->nullable();
            
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('lodgings');
    }
};
