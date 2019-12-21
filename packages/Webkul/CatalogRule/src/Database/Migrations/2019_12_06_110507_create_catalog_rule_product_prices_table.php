<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogRuleProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_rule_product_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', 12, 4)->default(0);
            $table->date('rule_date');
            $table->datetime('starts_from')->nullable();
            $table->datetime('ends_till')->nullable();
            
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->integer('customer_group_id')->unsigned();
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
            
            $table->integer('catalog_rule_id')->unsigned();
            $table->foreign('catalog_rule_id')->references('id')->on('catalog_rules')->onDelete('cascade');

            $table->integer('channel_id')->unsigned();
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
        Schema::dropIfExists('catalog_rule_product_prices');
    }
}
