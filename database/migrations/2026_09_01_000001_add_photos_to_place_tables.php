<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom media/foto ke semua tabel tempat usaha.
     *
     * lodgings        → sudah punya 'thumbnail', tambah photo_1 & photo_2
     * tourist_places  → tambah thumbnail, photo_1, photo_2
     * hangout_places  → tambah thumbnail, photo_1, photo_2
     */
    public function up(): void
    {
        // ── Lodging: sudah punya thumbnail, cukup tambah photo_1 & photo_2 ──
        Schema::table('lodgings', function (Blueprint $table) {
            if (!Schema::hasColumn('lodgings', 'photo_1')) {
                $table->string('photo_1')->nullable()->after('thumbnail')
                      ->comment('Foto tambahan #1 (opsional): menu, fasilitas, atau galeri');
            }
            if (!Schema::hasColumn('lodgings', 'photo_2')) {
                $table->string('photo_2')->nullable()->after('photo_1')
                      ->comment('Foto tambahan #2 (opsional)');
            }
        });

        // ── Tourist Places: tambah thumbnail + photo_1 & photo_2 ──
        Schema::table('tourist_places', function (Blueprint $table) {
            if (!Schema::hasColumn('tourist_places', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('ticket_price')
                      ->comment('Foto thumbnail utama (WAJIB saat listing aktif) untuk Landing Page & Card View');
            }
            if (!Schema::hasColumn('tourist_places', 'photo_1')) {
                $table->string('photo_1')->nullable()->after('thumbnail')
                      ->comment('Foto tambahan #1 (opsional)');
            }
            if (!Schema::hasColumn('tourist_places', 'photo_2')) {
                $table->string('photo_2')->nullable()->after('photo_1')
                      ->comment('Foto tambahan #2 (opsional)');
            }
        });

        // ── Hangout Places: tambah thumbnail + photo_1 & photo_2 ──
        Schema::table('hangout_places', function (Blueprint $table) {
            if (!Schema::hasColumn('hangout_places', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('phone')
                      ->comment('Foto thumbnail utama (WAJIB saat listing aktif) untuk Landing Page & Card View');
            }
            if (!Schema::hasColumn('hangout_places', 'photo_1')) {
                $table->string('photo_1')->nullable()->after('thumbnail')
                      ->comment('Foto tambahan #1 (opsional): foto menu, suasana, dll');
            }
            if (!Schema::hasColumn('hangout_places', 'photo_2')) {
                $table->string('photo_2')->nullable()->after('photo_1')
                      ->comment('Foto tambahan #2 (opsional)');
            }
        });
    }

    /**
     * Rollback: hapus kolom media yang ditambahkan.
     */
    public function down(): void
    {
        Schema::table('lodgings', function (Blueprint $table) {
            $table->dropColumn(array_filter(['photo_1', 'photo_2'], fn($col) => Schema::hasColumn('lodgings', $col)));
        });

        Schema::table('tourist_places', function (Blueprint $table) {
            $cols = array_filter(['thumbnail', 'photo_1', 'photo_2'], fn($col) => Schema::hasColumn('tourist_places', $col));
            if ($cols) $table->dropColumn($cols);
        });

        Schema::table('hangout_places', function (Blueprint $table) {
            $cols = array_filter(['thumbnail', 'photo_1', 'photo_2'], fn($col) => Schema::hasColumn('hangout_places', $col));
            if ($cols) $table->dropColumn($cols);
        });
    }
};
