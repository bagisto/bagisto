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
            if (!Schema::hasIndex('channels', 'channels_hostname_index')) {
                $table->index('hostname');
            }
        });

        Schema::table('channel_locales', function (Blueprint $table) {
            if (!Schema::hasIndex('channel_locales', 'channel_locales_channel_id_locale_id_index')) {
                $table->index(['channel_id', 'locale_id']);
            }
        });

        Schema::table('channel_currencies', function (Blueprint $table) {
            if (!Schema::hasIndex('channel_currencies', 'channel_currencies_channel_id_currency_id_index')) {
                $table->index(['channel_id', 'currency_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropIndex(['hostname']);
        });

        Schema::table('channel_locales', function (Blueprint $table) {
            $table->dropIndex(['channel_id', 'locale_id']);
        });

        Schema::table('channel_currencies', function (Blueprint $table) {
            $table->dropIndex(['channel_id', 'currency_id']);
        });
    }
};
