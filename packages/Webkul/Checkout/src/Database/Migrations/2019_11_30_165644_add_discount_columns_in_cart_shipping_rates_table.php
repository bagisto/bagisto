<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountColumnsInCartShippingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_shipping_rates', function (Blueprint $table) {
            $table->decimal('discount_amount', 12, 4)->default(0);
            $table->decimal('base_discount_amount', 12, 4)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_shipping_rates', function (Blueprint $table) {
            //
        });
    }
}
