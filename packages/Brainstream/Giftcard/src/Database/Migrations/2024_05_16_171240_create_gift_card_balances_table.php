<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardBalancesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('gift_card_balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('giftcard_number');
            $table->decimal('giftcard_amount', 10, 2);
            $table->decimal('used_giftcard_amount', 10, 2)->default(0.00);
            $table->decimal('remaining_giftcard_amount', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('gift_card_balances');
    }
}
