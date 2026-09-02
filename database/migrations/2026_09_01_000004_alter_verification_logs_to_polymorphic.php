<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah tabel verification_logs menjadi polymorphic.
     *
     * Sebelum: hanya terikat ke lodgings (lodging_id FK)
     * Sesudah:
     *   verifiable_type → 'owner_account' | 'lodging' | 'tourist_place' | 'hangout_place'
     *   verifiable_id   → ID record yang diverifikasi
     *
     * Ini memungkinkan satu tabel menyimpan log verifikasi untuk semua entitas,
     * termasuk verifikasi akun Owner (Verifikasi Tingkat 1).
     */
    public function up(): void
    {
        Schema::table('verification_logs', function (Blueprint $table) {
            // Tambah kolom polymorphic baru
            if (!Schema::hasColumn('verification_logs', 'verifiable_type')) {
                $table->string('verifiable_type')->nullable()->after('id')
                      ->comment('Jenis entitas: owner_account | lodging | tourist_place | hangout_place');
            }
            if (!Schema::hasColumn('verification_logs', 'verifiable_id')) {
                $table->unsignedBigInteger('verifiable_id')->nullable()->after('verifiable_type')
                      ->comment('ID dari entitas yang diverifikasi (sesuai verifiable_type)');
            }

            // Tambah kolom action (lebih eksplisit dari status)
            if (!Schema::hasColumn('verification_logs', 'action')) {
                $table->enum('action', ['approve', 'reject'])->nullable()->after('admin_id')
                      ->comment('Aksi yang dilakukan Admin: approve atau reject');
            }

            // Tambah index composite untuk pencarian log by entitas
            $table->index(['verifiable_type', 'verifiable_id'], 'idx_verlog_polymorphic');
        });

        // Migrasi data lama: isi verifiable_type & verifiable_id dari lodging_id yang sudah ada
        \DB::table('verification_logs')
            ->whereNotNull('lodging_id')
            ->whereNull('verifiable_type')
            ->update([
                'verifiable_type' => 'lodging',
                'verifiable_id'   => \DB::raw('lodging_id'),
                'action'          => \DB::raw("CASE WHEN status = 'approved' THEN 'approve' ELSE 'reject' END"),
            ]);
    }

    /**
     * Rollback: hapus kolom polymorphic & kembalikan ke versi lama.
     */
    public function down(): void
    {
        Schema::table('verification_logs', function (Blueprint $table) {
            // Hapus index terlebih dahulu
            if (Schema::hasIndex('verification_logs', 'idx_verlog_polymorphic')) {
                $table->dropIndex('idx_verlog_polymorphic');
            }

            $cols = ['verifiable_type', 'verifiable_id', 'action'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('verification_logs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
