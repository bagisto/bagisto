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
        Schema::table('url_rewrites', function (Blueprint $table) {
            // Add composite index for url rewrites with optimal column order
            if (!Schema::hasIndex('url_rewrites', 'idx_url_rewrites_entity_type_request_path_locale')) {
                $table->index(
                    ['entity_type', 'request_path', 'locale'],
                    'idx_url_rewrites_entity_type_request_path_locale'
                );
            }
        });

        Schema::table('visits', function (Blueprint $table) {
            // Add composite index for visits tracking with optimal column order
            if (!Schema::hasIndex('visits', 'idx_visits_channel_url_ip_method')) {
                $table->index(
                    ['channel_id', 'ip', 'method', 'visitor_id', 'visitor_type', 'created_at'],
                    'idx_visits_channel_url_ip_method'
                );
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('url_rewrites', function (Blueprint $table) {
            $table->dropIndex('idx_url_rewrites_entity_type_request_path_locale');
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->dropIndex('idx_visits_channel_url_ip_method');
        });
    }
};
