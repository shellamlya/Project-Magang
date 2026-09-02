<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom ai_summary ke semua tabel tempat usaha.
     *
     * ai_summary: Ringkasan singkat 1-2 kalimat yang mendeskripsikan keunikan tempat.
     * Digunakan sebagai context snippet cepat untuk VinoAI Chatbot
     * agar tidak perlu parsing seluruh kolom deskripsi.
     *
     * Sumber pengisian:
     *   - Auto-generated oleh AIController (feature existing AI Description Generator)
     *   - Bisa diedit manual oleh Owner melalui Owner Panel
     */
    public function up(): void
    {
        $tables = ['lodgings', 'tourist_places', 'hangout_places'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'ai_summary')) {
                    $table->text('ai_summary')->nullable()->after('description')
                          ->comment('Ringkasan singkat AI-generated (1-2 kalimat) untuk context VinoAI Chatbot. Bisa digenerate otomatis atau diedit manual oleh Owner.');
                }
            });
        }
    }

    /**
     * Rollback: hapus kolom ai_summary.
     */
    public function down(): void
    {
        $tables = ['lodgings', 'tourist_places', 'hangout_places'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'ai_summary')) {
                    $table->dropColumn('ai_summary');
                }
            });
        }
    }
};
