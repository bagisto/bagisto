<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogRuleProductsTable extends Migration
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
            $table->integer('catalog_rule_id')->unsigned();
            $table->foreign('catalog_rule_id')->references('id')->on('catalog_rules')->onDelete('cascade');
            $table->datetime('starts_from')->nullable();
            $table->datetime('ends_till')->nullable();
            $table->integer('customer_group_id')->unsigned();
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
            $table->integer('channel_id')->unsigned();
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->string('action_operator')->nullable();
            $table->decimal('action_amount', 12, 4)->default(0);
            $table->decimal('price', 12, 4)->default(0);
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
        Schema::dropIfExists('catalog_rule_products');
    }
}