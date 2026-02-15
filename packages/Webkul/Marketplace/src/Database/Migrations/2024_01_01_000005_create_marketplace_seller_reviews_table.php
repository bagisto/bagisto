<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_seller_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedInteger('customer_id');
            $table->integer('rating')->default(0);
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('seller_id')
                ->references('id')
                ->on('marketplace_sellers')
                ->onDelete('cascade');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_seller_reviews');
    }
};
