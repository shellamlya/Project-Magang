<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi tabel verification_logs.
     * Mencatat histori aksi verifikasi yang dilakukan oleh Admin (Approve / Reject).
     */
    public function up(): void
    {
        Schema::create('verification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lodging_id')->constrained('lodgings')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('status'); // approved / rejected
            $table->text('notes')->nullable(); // Alasan penolakan atau catatan verifikasi
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_logs');
    }
};
