<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->boolean('is_maintenance_on')->after('footer_content')->default(0);
            $table->text('maintenance_mode_text')->after('is_maintenance_on')->nullable();
            $table->text('allowed_ips')->after('maintenance_mode_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('is_maintenance_on');
            $table->dropColumn('maintenance_mode_text');
            $table->dropColumn('allowed_ips');
        });
    }
}
