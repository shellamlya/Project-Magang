<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom verifikasi akun owner (Verifikasi Tingkat 1).
     *
     * Alur:
     *   Owner register → account_status: 'pending_account'
     *   Admin hubungi via WA (ke kolom users.phone / HP Personal)
     *   Admin setujui → account_status: 'account_verified'
     *   Admin tolak   → account_status: 'rejected'
     */
    public function up(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            // Foto KTP Penanggung Jawab — diupload saat daftar listing pertama
            if (!Schema::hasColumn('owners', 'ktp_photo')) {
                $table->string('ktp_photo')->nullable()->after('address')
                      ->comment('Path foto KTP Owner/Penanggung Jawab (privat, hanya Admin yang bisa lihat)');
            }

            // No. WA Bisnis — tampil publik di halaman listing, berbeda dari HP Personal
            if (!Schema::hasColumn('owners', 'business_phone')) {
                $table->string('business_phone', 20)->nullable()->after('ktp_photo')
                      ->comment('No. WhatsApp Bisnis (tampil publik di listing & digunakan untuk fitur booking)');
            }

            // Status verifikasi akun (Verifikasi Tingkat 1)
            if (!Schema::hasColumn('owners', 'account_status')) {
                $table->enum('account_status', [
                    'pending_account',   // Baru daftar, menunggu verifikasi Admin
                    'account_verified',  // Akun terverifikasi, bisa daftar listing
                    'rejected',          // Akun ditolak Admin
                ])->default('pending_account')->after('status')
                  ->comment('Status verifikasi akun owner (Verifikasi Tingkat 1)');
            }

            // Waktu verifikasi akun berhasil
            if (!Schema::hasColumn('owners', 'account_verified_at')) {
                $table->timestamp('account_verified_at')->nullable()->after('account_status')
                      ->comment('Timestamp saat akun owner berhasil diverifikasi oleh Admin');
            }

            // Admin yang melakukan verifikasi akun
            if (!Schema::hasColumn('owners', 'account_verified_by')) {
                $table->foreignId('account_verified_by')->nullable()->after('account_verified_at')
                      ->constrained('users')->onDelete('set null')
                      ->comment('ID Admin yang melakukan verifikasi akun owner (Verifikasi Tingkat 1)');
            }

            // Alasan penolakan akun (jika rejected)
            if (!Schema::hasColumn('owners', 'account_rejection_reason')) {
                $table->text('account_rejection_reason')->nullable()->after('account_verified_by')
                      ->comment('Alasan penolakan akun owner oleh Admin (diisi saat account_status = rejected)');
            }
        });
    }

    /**
     * Rollback: hapus kolom verifikasi owner.
     */
    public function down(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            // Drop foreign key terlebih dahulu
            if (Schema::hasColumn('owners', 'account_verified_by')) {
                $table->dropForeign(['account_verified_by']);
                $table->dropColumn('account_verified_by');
            }

            $cols = ['ktp_photo', 'business_phone', 'account_status', 'account_verified_at', 'account_rejection_reason'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('owners', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
