<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogRuleProductsPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_rule_products_price', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cart_rule_product_id')->unsigned();
            $table->foreign('cart_rule_product_id')->references('id')->on('cart_rules')->onDelete('cascade');
            $table->datetime('starts_from')->nullable();
            $table->datetime('ends_till')->nullable();
            $table->decimal('price', 12, 4)->default(0.0000);
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
        Schema::dropIfExists('cart_rule_products_price');
    }
}
