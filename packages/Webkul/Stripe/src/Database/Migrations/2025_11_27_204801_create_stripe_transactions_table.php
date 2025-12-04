<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stripe_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cart_id')->unsigned();
            $table->integer('order_id')->unsigned()->nullable();
            $table->string('session_id')->unique()->index();
            $table->string('payment_intent_id')->nullable()->index();
            $table->decimal('amount', 12, 4)->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_transactions');
    }
};
