<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingProductRentalSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_product_rental_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('renting_type');
            $table->decimal('daily_price', 12, 4)->default(0)->nullable();
            $table->decimal('hourly_price', 12, 4)->default(0)->nullable();
            $table->boolean('slot_has_quantity')->nullable();
            $table->boolean('same_slot_all_days')->nullable();
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
        Schema::dropIfExists('booking_product_rental_slots');
    }
}
