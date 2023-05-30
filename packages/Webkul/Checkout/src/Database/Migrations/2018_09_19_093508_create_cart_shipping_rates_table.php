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
        Schema::create('cart_shipping_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('carrier');
            $table->string('carrier_title');
            $table->string('method');
            $table->string('method_title');
            $table->string('method_description')->nullable();
            $table->double('price')->default(0)->nullable();
            $table->double('base_price')->default(0)->nullable();
            $table->decimal('discount_amount', 12, 4)->default(0);
            $table->decimal('base_discount_amount', 12, 4)->default(0);
            $table->boolean('is_calculate_tax')->default(true);
            $table->integer('cart_address_id')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('cart_address_id')->references('id')->on('cart_address')->onDelete('cascade');
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
};
