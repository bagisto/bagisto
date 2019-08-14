<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogRuleProductPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_rule_product_price', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('channel_id')->unsigned();
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->integer('customer_group_id')->unsigned();
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('catalog_rule_id')->unsigned();
            $table->foreign('catalog_rule_id')->references('id')->on('catalog_rules')->onDelete('cascade');
            $table->decimal('rule_price', 20, 4)->default(0.0000);
            $table->datetime('starts_from')->nullable();
            $table->datetime('ends_till')->nullable();
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
        Schema::dropIfExists('catalog_rule_product_price');
    }
}
