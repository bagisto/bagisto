<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('marketplace_sellers')->cascadeOnDelete();
            $table->decimal('amount', 12, 4);
            $table->string('currency', 3)->default('BRL');
            $table->enum('status', ['requested', 'processing', 'paid', 'rejected'])->default('requested');
            $table->string('payment_method')->nullable();     // PIX, bank_transfer, etc.
            $table->json('payment_details')->nullable();      // PIX key, bank account, etc.
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_payouts');
    }
};
