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
        Schema::create('product_price_indices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('customer_group_id')->unsigned()->nullable();
            $table->decimal('min_price', 12, 4)->default(0);
            $table->decimal('regular_min_price', 12, 4)->default(0);
            $table->decimal('max_price', 12, 4)->default(0);
            $table->decimal('regular_max_price', 12, 4)->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'customer_group_id']);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_price_indices');
    }
};
