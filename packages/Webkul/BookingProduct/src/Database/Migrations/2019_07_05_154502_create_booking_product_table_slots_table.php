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
        Schema::create('booking_product_table_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('price_type');
            $table->integer('guest_limit')->default(0);
            $table->integer('duration');
            $table->integer('break_time');
            $table->integer('prevent_scheduling_before');
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
        Schema::dropIfExists('booking_product_table_slots');
    }
};
