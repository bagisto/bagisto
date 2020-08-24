<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalePricesToBookingProductEventTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_product_event_tickets', function (Blueprint $table) {
            $table->decimal('special_price', 12,4)->after('qty')->nullable();
            $table->dateTime('special_price_from')->after('special_price')->nullable();
            $table->dateTime('special_price_to')->after('special_price_from')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_product_event_tickets', function (Blueprint $table) {
            $table->dropColumn('special_price');
            $table->dropColumn('special_price_from');
            $table->dropColumn('special_price_to');
        });
    }
}
