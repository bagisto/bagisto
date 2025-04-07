<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_card_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('giftcard_number')->index();
            $table->string('order_id')->index();
            $table->string('payment_id')->index();
            $table->string('payer_id');
            $table->string('payer_email');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->string('status');
            $table->json('payment_data');
            $table->string('payment_type');
            $table->timestamps();

            // Adding the foreign key constraint
            $table->foreign('giftcard_number')
            ->references('giftcard_number')
            ->on('gift_cards')
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
        Schema::dropIfExists('gift_card_payments');
    }
}
