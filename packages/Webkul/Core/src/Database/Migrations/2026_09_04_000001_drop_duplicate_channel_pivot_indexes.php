<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('channel_locales', function (Blueprint $table) {
            if (Schema::hasIndex('channel_locales', DB::getTablePrefix().'channel_locales_cid_lid_idx')) {
                $table->dropIndex('channel_locales_cid_lid_idx');
            }
        });

        Schema::table('channel_currencies', function (Blueprint $table) {
            if (Schema::hasIndex('channel_currencies', DB::getTablePrefix().'channel_currencies_cid_cyid_idx')) {
                $table->dropIndex('channel_currencies_cid_cyid_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('channel_locales', function (Blueprint $table) {
            if (! Schema::hasIndex('channel_locales', DB::getTablePrefix().'channel_locales_cid_lid_idx')) {
                $table->index(['channel_id', 'locale_id'], 'channel_locales_cid_lid_idx');
            }
        });

        Schema::table('channel_currencies', function (Blueprint $table) {
            if (! Schema::hasIndex('channel_currencies', DB::getTablePrefix().'channel_currencies_cid_cyid_idx')) {
                $table->index(['channel_id', 'currency_id'], 'channel_currencies_cid_cyid_idx');
            }
        });
    }
};
