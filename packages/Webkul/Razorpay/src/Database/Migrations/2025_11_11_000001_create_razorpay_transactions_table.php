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
        Schema::create('razorpay_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('order_id')->nullable();
            $table->string('razorpay_receipt', 100);
            $table->string('razorpay_order_id', 80)->nullable();
            $table->string('razorpay_payment_id', 40)->nullable();
            $table->string('razorpay_invoice_status', 40)->default('issued');
            $table->string('razorpay_signature')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('razorpay_transactions');
    }
};
