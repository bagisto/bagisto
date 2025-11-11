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
        Schema::create('razorpay_events', function (Blueprint $table) {
            $table->integer('id', 11);
            $table->string('order_id', 50)->nullable();
            $table->string('razorpay_event_id', 50)->nullable();
            $table->string('razorpay_invoice_id', 40)->nullable();
            $table->string('razorpay_order_id', 80)->nullable();
            $table->string('razorpay_payment_id', 40)->nullable();
            $table->string('razorpay_invoice_status', 40)->default('issued');
            $table->string('razorpay_invoice_receipt', 40)->nullable();
            $table->string('razorpay_signature')->nullable();
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
        Schema::dropIfExists('razorpay_events');
    }
};
