<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnsFromChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('description');
            $table->dropColumn('home_page_content');
            $table->dropColumn('footer_content');
            $table->dropColumn('maintenance_mode_text');
            $table->dropColumn('home_seo');
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
            //
        });
    }
}
