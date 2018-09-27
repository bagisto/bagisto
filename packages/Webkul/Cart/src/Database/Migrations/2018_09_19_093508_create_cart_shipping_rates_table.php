<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartShippingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_shipping_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('carrier');
            $table->string('carrier_title');
            $table->string('method');
            $table->string('method_title');
            $table->string('method_description')->nullable();
            $table->double('price')->default(0)->nullable();
            $table->double('base_price')->default(0)->nullable();
            $table->integer('cart_address_id')->nullable()->unsigned();
            $table->foreign('cart_address_id')->references('id')->on('cart_address');
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
        Schema::dropIfExists('cart_shipping_rates');
    }
}
