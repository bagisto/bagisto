<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_seller_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedInteger('product_id');
            $table->boolean('is_approved')->default(false);
            $table->string('condition')->default('new');
            $table->decimal('price', 12, 4)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('seller_id')
                ->references('id')
                ->on('marketplace_sellers')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->unique(['seller_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_seller_products');
    }
};
