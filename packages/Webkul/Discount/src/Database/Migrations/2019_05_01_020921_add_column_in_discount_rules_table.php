<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInDiscountRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discount_rules', function (Blueprint $table) {
            Schema::table('discount_rules', function (Blueprint $table) {
                $table->json('conditions');
                $table->json('actions');
                $table->integer('discount_id')->unsigned();
                $table->foreign('discount_id')->references('id')->on('discounts');
                $table->integer('coupon_id')->unsigned()->nullable();
                $table->foreign('coupon_id')->references('id')->on('coupons');
                $table->json('label')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discount_rules', function (Blueprint $table) {
            $table->dropColumn('conditions');
            $table->dropColumn('actions');
            $table->dropForeign('discount_id');
            $table->dropColumn('discount_id');
            $table->dropForeign('coupon_id');
            $table->dropColumn('coupon_id');
            $table->dropColumn('label');
        });
    }
}
