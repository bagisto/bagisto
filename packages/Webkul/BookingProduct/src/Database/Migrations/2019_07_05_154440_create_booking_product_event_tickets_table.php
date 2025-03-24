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
        Schema::create('booking_product_event_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('booking_product_id');
            $table->decimal('price', 12, 4)->default(0)->nullable();
            $table->integer('qty')->default(0)->nullable();
            $table->decimal('special_price', 12, 4)->nullable();
            $table->dateTime('special_price_from')->nullable();
            $table->dateTime('special_price_to')->nullable();

            $table->foreign('booking_product_id')
                ->references('id')
                ->on('booking_products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_product_event_tickets');
    }
};
