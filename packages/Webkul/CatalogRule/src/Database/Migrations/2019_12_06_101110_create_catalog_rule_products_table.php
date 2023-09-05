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
        Schema::create('catalog_rule_products', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('starts_from')->nullable();
            $table->datetime('ends_till')->nullable();
            $table->boolean('end_other_rules')->default(0);
            $table->string('action_type')->nullable();
            $table->decimal('discount_amount', 12, 4)->default(0);
            $table->integer('sort_order')->unsigned()->default(0);
            $table->integer('product_id')->unsigned();
            $table->integer('customer_group_id')->unsigned();
            $table->integer('catalog_rule_id')->unsigned();
            $table->integer('channel_id')->unsigned();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
            $table->foreign('catalog_rule_id')->references('id')->on('catalog_rules')->onDelete('cascade');
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
        Schema::dropIfExists('catalog_rule_products');
    }
};
