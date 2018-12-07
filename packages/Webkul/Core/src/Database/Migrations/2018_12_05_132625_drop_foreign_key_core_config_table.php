<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignKeyCoreConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('core_config', function (Blueprint $table) {
            $table->dropForeign('core_config_channel_id_foreign');
            $table->renameColumn('channel_id', 'channel_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_config', function (Blueprint $table) {
            $table->dropForeign('core_config_channel_id_foreign');
            $table->renameColumn('channel_id', 'channel_code');
        });
    }
}
