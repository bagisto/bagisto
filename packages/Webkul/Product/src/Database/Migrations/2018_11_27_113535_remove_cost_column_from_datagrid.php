<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCostColumnFromDatagrid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_grid', function (Blueprint $table) {
            $table->dropColumn('cost');
            $table->dropColumn('attribute_family_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_grid', function (Blueprint $table) {
            $table->string('cost');
            $table->string('attribute_family_name')->nullable();
        });
    }
}
