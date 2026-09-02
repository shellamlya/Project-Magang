<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom verifikasi listing ke semua tabel tempat usaha (Verifikasi Tingkat 2).
     *
     * Berlaku untuk: lodgings, tourist_places, hangout_places
     *
     * Alur:
     *   Owner submit listing → status: 'pending' (kolom existing)
     *   Admin verifikasi KTP + data → listing_verified_at & listing_verified_by diisi
     *   Jika tolak → listing_rejection_reason diisi
     */
    public function up(): void
    {
        $tables = ['lodgings', 'tourist_places', 'hangout_places'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {

                // Waktu listing disetujui (approved)
                if (!Schema::hasColumn($tableName, 'listing_verified_at')) {
                    $table->timestamp('listing_verified_at')->nullable()->after('status')
                          ->comment('Timestamp saat listing disetujui & dipublikasikan oleh Admin');
                }

                // Admin yang menyetujui/menolak listing
                if (!Schema::hasColumn($tableName, 'listing_verified_by')) {
                    $table->unsignedBigInteger('listing_verified_by')->nullable()->after('listing_verified_at')
                          ->comment('ID Admin yang melakukan verifikasi listing (Verifikasi Tingkat 2)');
                }

                // Alasan penolakan listing (jika rejected)
                if (!Schema::hasColumn($tableName, 'listing_rejection_reason')) {
                    $table->text('listing_rejection_reason')->nullable()->after('listing_verified_by')
                          ->comment('Alasan penolakan listing oleh Admin, dikirim sebagai notifikasi ke Owner');
                }
            });
        }
    }

    /**
     * Rollback: hapus kolom verifikasi listing.
     */
    public function down(): void
    {
        $tables = ['lodgings', 'tourist_places', 'hangout_places'];
        $cols   = ['listing_verified_at', 'listing_verified_by', 'listing_rejection_reason'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $cols) {
                foreach ($cols as $col) {
                    if (Schema::hasColumn($tableName, $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
