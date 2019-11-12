<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartruleCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_rule_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cart_rule_id')->unsigned();
            $table->foreign('cart_rule_id')->references('id')->on('cart_rules')->onDelete('cascade');
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->string('code')->nullable();
            $table->integer('usage_limit')->unsigned()->default(0);
            $table->integer('usage_per_customer')->unsigned()->default(0);
            $table->integer('usage_throttle')->unsigned()->default(0);
            $table->integer('type')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_rule_coupons');
    }
}
