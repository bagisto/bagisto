<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('giftcard_number', 100)->index();
            $table->decimal('giftcard_amount', 10, 0);
            $table->date('expirationdate');
            $table->string('expirein', 190);
            $table->string('giftcard_status', 191);
            $table->string('sendername', 191);
            $table->string('senderemail', 255);
            $table->string('recipientname', 255);
            $table->string('recipientemail', 255);
            $table->string('message', 500);
            $table->date('creationdate');
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
        Schema::dropIfExists('gift_cards');
    }
}
