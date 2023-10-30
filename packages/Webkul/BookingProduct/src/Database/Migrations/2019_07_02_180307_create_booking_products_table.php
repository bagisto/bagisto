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
        Schema::create('booking_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('qty')->default(0)->nullable();
            $table->string('location')->nullable();
            $table->boolean('show_location')->default(0);
            $table->boolean('available_every_week')->nullable();
            $table->datetime('available_from')->nullable();
            $table->datetime('available_to')->nullable();

            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('booking_products');
    }
};
