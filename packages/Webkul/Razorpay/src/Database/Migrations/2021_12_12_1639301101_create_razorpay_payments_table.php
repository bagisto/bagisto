<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRazorpayPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('rzp_payments', function (Blueprint $table) {
            $table->integer('id',11);
            $table->string('core_order_id',50)->nullable()->default('NULL');
            $table->string('razorpay_event_id',50)->nullable()->default('NULL');
            $table->string('razorpay_invoice_id',40)->nullable()->default('NULL');
            $table->string('razorpay_order_id',80)->nullable()->default('NULL');
            $table->string('razorpay_payment_id',40)->nullable()->default('NULL');
            $table->string('razorpay_invoice_status',40)->default('issued');
            $table->string('razorpay_invoice_receipt',40)->nullable()->default('NULL');
            $table->string('razorpay_signature')->nullable()->default('NULL');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rzp_payments');
    }
}