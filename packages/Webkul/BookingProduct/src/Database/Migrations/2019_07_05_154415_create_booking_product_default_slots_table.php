<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingProductDefaultSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_product_default_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('booking_type');
            $table->date('available_from')->nullable();
            $table->date('available_to')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('break_time')->nullable();
            $table->json('slots')->nullable();

            $table->integer('booking_product_id')->unsigned();
            $table->foreign('booking_product_id')->references('id')->on('booking_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_product_default_slots');
    }
}
