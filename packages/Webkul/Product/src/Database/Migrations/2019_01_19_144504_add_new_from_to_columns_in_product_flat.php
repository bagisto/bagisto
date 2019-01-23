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
                $table->integer('tax_category_id')->unsigned()->nullable()->after('size_label');
                $table->date('new_to')->after('new');
                $table->date('new_from')->after('new');
                $table->unique(['product_id', 'channel', 'locale'], 'product_flat_unique_index');
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
                $table->dropColumn('tax_category_id')->unsigned();
                $table->dropColumn('new_from');
                $table->dropColumn('new_to');
                $table->dropIndex('product_flat_unique_index');
            });
        }
    }
}
