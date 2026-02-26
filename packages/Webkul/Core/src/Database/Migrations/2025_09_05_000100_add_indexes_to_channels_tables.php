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
        Schema::table('channels', function (Blueprint $table) {
            if (! Schema::hasIndex('channels', 'channels_hostname_idx')) {
                $table->index('hostname', 'channels_hostname_idx');
            }
        });

        Schema::table('channel_locales', function (Blueprint $table) {
            if (! Schema::hasIndex('channel_locales', 'channel_locales_cid_lid_idx')) {
                $table->index(['channel_id', 'locale_id'], 'channel_locales_cid_lid_idx');
            }
        });

        Schema::table('channel_currencies', function (Blueprint $table) {
            if (! Schema::hasIndex('channel_currencies', 'channel_currencies_cid_cyid_idx')) {
                $table->index(['channel_id', 'currency_id'], 'channel_currencies_cid_cyid_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            if (Schema::hasIndex('channels', 'channels_hostname_idx')) {
                $table->dropIndex('channels_hostname_idx');
            }
        });

        Schema::table('channel_locales', function (Blueprint $table) {
            if (Schema::hasIndex('channel_locales', 'channel_locales_cid_lid_idx')) {
                $table->dropIndex('channel_locales_cid_lid_idx');
            }
        });

        Schema::table('channel_currencies', function (Blueprint $table) {
            if (Schema::hasIndex('channel_currencies', 'channel_currencies_cid_cyid_idx')) {
                $table->dropIndex('channel_currencies_cid_cyid_idx');
            }
        });
    }
};
