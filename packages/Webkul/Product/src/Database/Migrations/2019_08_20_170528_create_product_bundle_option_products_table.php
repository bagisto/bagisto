<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBundleOptionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_bundle_option_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('qty')->default(0);
            $table->boolean('is_default')->default(0);
            $table->integer('sort_order')->default(0);

            $table->integer('product_bundle_option_id')->unsigned();
            $table->foreign('product_bundle_option_id')->references('id')->on('product_bundle_options')->onDelete('cascade');

            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_bundle_option_products');
    }
}
