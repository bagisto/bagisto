<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
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
        DB::table('velocity_customer_compare_products')->delete();

        Schema::table('velocity_customer_compare_products', function (Blueprint $table) {
            $table->dropForeign(['product_flat_id']);
            $table->dropColumn('product_flat_id');

            $table->integer('product_id')->after('customer_id')->unsigned();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('velocity_customer_compare_products', function (Blueprint $table) {
            $table->integer('product_flat_id')->unsigned();
            $table->foreign('product_flat_id')
                ->references('id')
                ->on('product_flat')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->dropForeign('product_id');
            $table->dropColumn('product_id');
        });
    }
};
