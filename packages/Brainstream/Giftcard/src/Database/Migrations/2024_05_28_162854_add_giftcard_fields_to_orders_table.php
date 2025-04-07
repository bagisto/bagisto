<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGiftcardFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Adding new varchar field for giftcard number
            $table->string('giftcard_number', 191)->nullable()->after('coupon_code');
            $table->decimal('giftcard_amount', 12, 4)->nullable()->after('giftcard_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Dropping the columns in the reverse process
            $table->dropColumn(['giftcard_number', 'giftcard_amount']);
        });
    }
}
