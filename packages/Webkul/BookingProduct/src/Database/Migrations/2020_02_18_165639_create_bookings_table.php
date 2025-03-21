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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('order_item_id')->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->integer('qty')->default(0)->nullable();
            $table->integer('from')->nullable();
            $table->integer('to')->nullable();

            $table->foreign('order_item_id')
                ->references('id')
                ->on('order_items')
                ->nullOnDelete();

            $table->foreignId('booking_product_event_ticket_id')
                ->nullable()
                ->constrained('booking_product_event_tickets')
                ->nullOnDelete();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->nullOnDelete();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->nullOnDelete();

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
        Schema::dropIfExists('bookings');
    }
};
