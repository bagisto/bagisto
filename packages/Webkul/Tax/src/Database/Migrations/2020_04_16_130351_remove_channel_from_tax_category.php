<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_categories', function (Blueprint $table) {
            $table->dropForeign('tax_categories_channel_id_foreign');
            $table->dropColumn('channel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_categories', function (Blueprint $table) {
            $table->integer('channel_id')->unsigned()->after('id');
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
        });
    }
};
