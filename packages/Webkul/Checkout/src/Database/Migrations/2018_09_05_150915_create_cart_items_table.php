<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('quantity')->unsigned()->default(1);
            $table->integer('cart_id')->unsigned();
            $table->foreign('cart_id')->references('id')->on('cart');
            $table->string('sku')->nullable();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('tax_category_id')->unsigned()->nullable();
            $table->foreign('tax_category_id')->references('id')->on('tax_categories');
            $table->string('coupon_code')->nullable();
            $table->decimal('weight', 12,4)->default(1);
            $table->decimal('total_weight', 12,4)->default(0);
            $table->decimal('base_total_weight', 12,4)->default(0);
            $table->decimal('price', 12,4)->default(1);
            $table->decimal('total', 12,4)->default(0);
            $table->decimal('base_total', 12,4)->default(0);
            $table->decimal('total_with_discount', 12,4)->default(0);
            $table->decimal('base_total_with_discount', 12,4)->default(0);
            $table->decimal('base_price', 12,4)->default(0);
            $table->decimal('custom_price', 12,4)->default(0);
            $table->decimal('discount_percent', 12,4)->default(0);
            $table->decimal('discount_amount', 12,4)->default(0);
            $table->decimal('base_discount_amount', 12,4)->default(0);
            $table->boolean('no_discount')->nullable()->default(0);
            $table->boolean('free_shipping')->nullable()->default(0);
            $table->json('additional')->nullable();
            $table->timestamps();
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('cart_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
