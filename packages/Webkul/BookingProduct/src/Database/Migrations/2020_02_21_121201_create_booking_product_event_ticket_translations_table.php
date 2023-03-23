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
        Schema::create('booking_product_event_ticket_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale');
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('booking_product_event_ticket_id')->unsigned();
            $table->unique(['booking_product_event_ticket_id', 'locale'], 'booking_product_event_ticket_translations_locale_unique');
            $table->foreign('booking_product_event_ticket_id', 'booking_product_event_ticket_translations_locale_foreign')->references('id')->on('booking_product_event_tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_product_event_ticket_translations');
    }
};
