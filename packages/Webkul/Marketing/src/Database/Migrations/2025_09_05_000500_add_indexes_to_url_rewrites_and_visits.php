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
            if (! Schema::hasIndex('url_rewrites', 'url_rewrites_et_rp_lc_idx')) {
                $table->index(
                    ['entity_type', 'request_path', 'locale'],
                    'url_rewrites_et_rp_lc_idx'
                );
            }
        });

        Schema::table('visits', function (Blueprint $table) {
            if (! Schema::hasIndex('visits', 'visits_cid_ip_m_vid_vt_ca_idx')) {
                $table->index(
                    ['channel_id', 'ip', 'method', 'visitor_id', 'visitor_type', 'created_at'],
                    'visits_cid_ip_m_vid_vt_ca_idx'
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
            if (Schema::hasIndex('url_rewrites', 'url_rewrites_et_rp_lc_idx')) {
                $table->dropIndex('url_rewrites_et_rp_lc_idx');
            }
        });
    }
};
