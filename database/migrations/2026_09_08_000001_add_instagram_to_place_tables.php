<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom instagram ke seluruh tabel tempat usaha.
     *
     * Tabel terkait:
     * - lodgings (Penginapan)
     * - tourist_places (Tempat Wisata)
     * - hangout_places (Kafe & Tempat Nongkrong)
     */
    public function up(): void
    {
        $tables = ['lodgings', 'tourist_places', 'hangout_places'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'instagram')) {
                    $table->string('instagram')->nullable()->after('phone')
                          ->comment('Username atau Link akun Instagram tempat usaha');
                }
            });
        }
    }

    /**
     * Rollback migrasi: hapus kolom instagram dari seluruh tabel tempat usaha.
     */
    public function down(): void
    {
        $tables = ['lodgings', 'tourist_places', 'hangout_places'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'instagram')) {
                    $table->dropColumn('instagram');
                }
            });
        }
    }
};
