<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->string('session_id')->nullable();
            $table->integer('channel_id')->unsigned();
            $table->foreign('channel_id')->references('id')->on('channels');
            $table->string('shipping_method')->nullable();
            $table->string('coupon_code')->nullable();
            $table->boolean('is_gift')->nullable();
            $table->integer('items_count')->nullable();
            $table->decimal('items_qty', 12, 4)->nullable();
            $table->string('global_currency_code')->nullable();
            $table->string('base_currency_code')->nullable();
            $table->string('store_currency_code')->nullable();
            $table->string('quote_currency_code')->nullable();
            $table->decimal('grand_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total', 12, 4)->default(0)->nullable();
            $table->decimal('sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('sub_total_with_discount', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total_with_discount', 12, 4)->default(0)->nullable();
            $table->string('checkout_method')->nullable();
            $table->boolean('is_guest')->nullable();
            $table->string('customer_full_name')->nullable();
            $table->dateTime('conversion_time')->nullable();
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
        Schema::dropIfExists('cart');
    }
}
