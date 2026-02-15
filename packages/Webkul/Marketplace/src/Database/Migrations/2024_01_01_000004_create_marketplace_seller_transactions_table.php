<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_seller_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->string('transaction_id')->unique();
            $table->string('type')->default('credit');
            $table->decimal('amount', 12, 4)->default(0);
            $table->decimal('base_amount', 12, 4)->default(0);
            $table->text('comment')->nullable();
            $table->string('method')->default('manual');
            $table->timestamps();

            $table->foreign('seller_id')
                ->references('id')
                ->on('marketplace_sellers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_seller_transactions');
    }
};
