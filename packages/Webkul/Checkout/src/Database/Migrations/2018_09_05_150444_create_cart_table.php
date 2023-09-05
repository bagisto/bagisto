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
        Schema::create('cart', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_email')->nullable();
            $table->string('customer_first_name')->nullable();
            $table->string('customer_last_name')->nullable();
            $table->string('shipping_method')->nullable();
            $table->string('coupon_code')->nullable();
            $table->boolean('is_gift')->default(0);
            $table->integer('items_count')->nullable();
            $table->decimal('items_qty', 12, 4)->nullable();
            $table->decimal('exchange_rate', 12, 4)->nullable();
            $table->string('global_currency_code')->nullable();
            $table->string('base_currency_code')->nullable();
            $table->string('channel_currency_code')->nullable();
            $table->string('cart_currency_code')->nullable();
            $table->decimal('grand_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total', 12, 4)->default(0)->nullable();
            $table->decimal('sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('tax_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_total', 12, 4)->default(0)->nullable();
            $table->decimal('discount_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_discount_amount', 12, 4)->default(0)->nullable();
            $table->string('checkout_method')->nullable();
            $table->boolean('is_guest')->nullable();
            $table->boolean('is_active')->nullable()->default(1);
            $table->string('applied_cart_rule_ids')->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('channel_id')->unsigned();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
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
};
