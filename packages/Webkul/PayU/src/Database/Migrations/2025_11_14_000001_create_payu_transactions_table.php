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
        Schema::create('payu_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cart_id')->index();
            $table->unsignedInteger('customer_id')->nullable()->index();
            $table->string('transaction_id')->unique()->index();
            $table->decimal('amount', 12, 4);
            $table->string('status')->default('pending');
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payu_transactions');
    }
};
