<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            if (!Schema::hasColumn('owners', 'verification_status')) {
                $table->enum('verification_status', ['pending', 'approved', 'rejected'])
                      ->default('pending')
                      ->after('account_status')
                      ->comment('Status verifikasi akun owner: pending, approved, rejected');
            }
        });

        // Sinkronisasi data awal berdasarkan account_status yang sudah ada
        DB::table('owners')
            ->where('account_status', 'account_verified')
            ->update(['verification_status' => 'approved']);

        DB::table('owners')
            ->where('account_status', 'rejected')
            ->update(['verification_status' => 'rejected']);

        DB::table('owners')
            ->where('account_status', 'pending_account')
            ->update(['verification_status' => 'pending']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            if (Schema::hasColumn('owners', 'verification_status')) {
                $table->dropColumn('verification_status');
            }
        });
    }
};
