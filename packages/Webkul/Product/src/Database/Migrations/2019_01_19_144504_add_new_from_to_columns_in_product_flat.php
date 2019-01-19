<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFromToColumnsInProductFlat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('product_flat')) {
            Schema::table('product_flat', function (Blueprint $table) {
                $table->date('new_to')->after('new');
                $table->date('new_from')->after('new');
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
        if (Schema::hasTable('product_flat')) {
            Schema::table('product_flat', function (Blueprint $table) {
                $table->dropColumn('new_from');
                $table->dropColumn('new_to');
            });
        }
    }
}
