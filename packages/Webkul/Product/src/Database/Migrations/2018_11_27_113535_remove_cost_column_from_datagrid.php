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
        if (Schema::hasTable('product_grid')) {
            Schema::table('products_grid', function (Blueprint $table) {
                $table->dropColumn('cost');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('product_grid')) {
            Schema::table('products_grid', function (Blueprint $table) {
                $table->string('cost');
            });
        }
    }
}
