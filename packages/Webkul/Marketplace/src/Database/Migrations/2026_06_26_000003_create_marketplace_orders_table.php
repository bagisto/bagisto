<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Links a Bagisto order to a seller and records commission split
        Schema::create('marketplace_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('marketplace_sellers')->cascadeOnDelete();
            $table->decimal('base_total', 12, 4)->default(0);       // order items belonging to this seller
            $table->decimal('commission_amount', 12, 4)->default(0); // admin's cut
            $table->decimal('seller_total', 12, 4)->default(0);      // seller receives this
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->enum('commission_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_orders');
    }
};
