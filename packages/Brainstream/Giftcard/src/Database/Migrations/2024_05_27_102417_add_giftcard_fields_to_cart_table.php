<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGiftcardFieldsToCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->string('giftcard_number', 191)->nullable()->after('applied_cart_rule_ids');
            $table->decimal('giftcard_amount', 10, 0)->nullable()->after('giftcard_number');
            $table->decimal('remaining_giftcard_amount', 10, 2)->default(0.00)->after('giftcard_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('giftcard_number');
            $table->dropColumn('giftcard_amount');
            $table->dropColumn('remaining_giftcard_amount');
        });
    }
}
