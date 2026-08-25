<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = ['lodgings', 'tourist_places', 'hangout_places'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'is_verified_official')) {
                    $table->boolean('is_verified_official')->default(false)->after('status');
                }
                if (!Schema::hasColumn($tableName, 'status_claim')) {
                    $table->string('status_claim')->default('unclaimed')->after('is_verified_official');
                }
                if (!Schema::hasColumn($tableName, 'views_count')) {
                    $table->unsignedBigInteger('views_count')->default(0)->after('status_claim');
                }
                if (!Schema::hasColumn($tableName, 'maps_clicks_count')) {
                    $table->unsignedBigInteger('maps_clicks_count')->default(0)->after('views_count');
                }
            });
        }

        if (!Schema::hasTable('place_analytics_logs')) {
            Schema::create('place_analytics_logs', function (Blueprint $table) {
                $table->id();
                $table->string('place_type'); // lodging, tourist_place, hangout_place
                $table->unsignedBigInteger('place_id');
                $table->string('event_type'); // view, map_click
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->index(['place_type', 'place_id', 'event_type', 'created_at'], 'idx_analytics_lookup');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['lodgings', 'tourist_places', 'hangout_places'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'is_verified_official')) {
                    $table->dropColumn('is_verified_official');
                }
                if (Schema::hasColumn($tableName, 'status_claim')) {
                    $table->dropColumn('status_claim');
                }
                if (Schema::hasColumn($tableName, 'views_count')) {
                    $table->dropColumn('views_count');
                }
                if (Schema::hasColumn($tableName, 'maps_clicks_count')) {
                    $table->dropColumn('maps_clicks_count');
                }
            });
        }

        Schema::dropIfExists('place_analytics_logs');
    }
};
