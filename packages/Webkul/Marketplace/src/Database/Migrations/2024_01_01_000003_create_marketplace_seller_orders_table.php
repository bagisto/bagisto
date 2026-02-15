<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_seller_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedInteger('order_item_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->decimal('base_sub_total', 12, 4)->default(0);
            $table->decimal('base_grand_total', 12, 4)->default(0);
            $table->decimal('base_commission', 12, 4)->default(0);
            $table->decimal('base_seller_total', 12, 4)->default(0);
            $table->decimal('commission_percentage', 5, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('seller_id')
                ->references('id')
                ->on('marketplace_sellers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_seller_orders');
    }
};
