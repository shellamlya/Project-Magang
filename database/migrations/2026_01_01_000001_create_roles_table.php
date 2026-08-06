<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi tabel roles.
     * Roles digunakan untuk membedakan hak akses pengguna:
     * 1 = Admin, 2 = Owner, 3 = User
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, owner, user
            $table->string('label'); // Administrator, Pemilik Penginapan, Pengguna Umum
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi (drop tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
