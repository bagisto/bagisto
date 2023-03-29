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
        Schema::table('cart_shipping_rates', function (Blueprint $table) {
            $table->boolean('is_calculate_tax')->default(true);
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
            $table->dropColumn('is_calculate_tax');
        });
    }
};
