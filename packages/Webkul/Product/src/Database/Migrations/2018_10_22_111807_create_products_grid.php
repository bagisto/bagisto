<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsGrid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_grid', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('sku')->nullable();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->string('quantity')->default(0);
            $table->string('cost')->default(0);
            $table->string('price')->nullable();
            $table->string('status')->nullable();
            $table->string('attribute_family_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_grid');
    }
}
