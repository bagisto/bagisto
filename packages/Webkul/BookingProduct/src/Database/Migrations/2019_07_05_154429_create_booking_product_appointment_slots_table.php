<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingProductAppointmentSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_product_appointment_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('duration')->nullable();
            $table->integer('break_time')->nullable();
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
        Schema::dropIfExists('booking_product_appointment_slots');
    }
}
