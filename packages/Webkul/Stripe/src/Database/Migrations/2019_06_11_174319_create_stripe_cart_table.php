<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_cart', function (Blueprint $table) {
            $table->increments('id');
            $table->foreign('cart_id')->references('id')->on('cart')->onDelete('cascade');
            $table->integer('cart_id')->unsigned();
            $table->json('stripe_token');
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
        Schema::dropIfExists('stripe_cart');
    }
}
