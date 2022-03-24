<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->integer('inventory_source_id')->unsigned()->nullable();
            $table->foreign('inventory_source_id')->references('id')->on('inventory_sources')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign('shipments_inventory_source_id_foreign')->references('id')->on('inventory_sources')->onDelete('set null');
            $table->dropColumn('inventory_source_id');
        });
    }
}
