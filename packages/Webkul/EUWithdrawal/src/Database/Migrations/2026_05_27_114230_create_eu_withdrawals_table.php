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
        Schema::create('eu_withdrawals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('customer_id')->nullable();
            $table->boolean('is_guest')->default(false);
            $table->string('customer_email');
            $table->unsignedInteger('channel_id');
            $table->string('locale', 10);
            $table->text('reason_text')->nullable();
            $table->timestamp('received_at');
            $table->timestamp('confirmation_sent_at')->nullable();
            $table->timestamp('final_confirmation_sent_at')->nullable();
            $table->string('confirmation_error', 500)->nullable();
            $table->string('status', 30)->default('received');
            $table->timestamp('declined_at')->nullable();
            $table->string('declined_reason', 500)->nullable();
            $table->unsignedInteger('declined_by_user_id')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->unsignedInteger('refunded_by_user_id')->nullable();
            $table->string('refund_note', 500)->nullable();
            $table->timestamps();

            $table->unique('order_id');
            $table->index('customer_id');
            $table->index(['channel_id', 'status']);
            $table->index('received_at');

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('channel_id')->references('id')->on('channels')->restrictOnDelete();
            $table->foreign('declined_by_user_id')->references('id')->on('admins')->nullOnDelete();
            $table->foreign('refunded_by_user_id')->references('id')->on('admins')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eu_withdrawals');
    }
};
