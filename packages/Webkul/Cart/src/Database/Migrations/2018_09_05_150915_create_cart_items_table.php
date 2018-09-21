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
            $table->integer('tax_category_id')->unsigned()->nullable();
            $table->foreign('tax_category_id')->references('id')->on('tax_categories');
            $table->string('coupon_code')->nullable();
            $table->decimal('weight', 12,4)->nullable();
            $table->decimal('price', 12,4)->nullable();
            $table->decimal('base_price', 12,4)->nullable();
            $table->decimal('custom_price', 12,4)->nullable();
            $table->decimal('discount_percent', 12,4)->nullable();
            $table->decimal('discount_amount', 12,4)->nullable();
            $table->decimal('base_discount_amount', 12,4)->nullable();
            $table->boolean('no_discount')->nullable()->default(0);
            $table->json('additional')->nullable();
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
        Schema::dropIfExists('cart_items');
    }
}
