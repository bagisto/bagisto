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
        Schema::create('rma_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rma_id')->unsigned()->nullable();
            $table->integer('rma_reason_id')->unsigned()->nullable();
            $table->integer('order_item_id')->unsigned()->nullable();
            $table->integer('variant_id')->unsigned()->nullable();
            $table->integer('quantity')->unsigned();
            $table->string('resolution')->nullable();
            $table->timestamps();

            $table->foreign('rma_id')->references('id')->on('rma')->onDelete('cascade');
            $table->foreign('rma_reason_id')->references('id')->on('rma_reasons')->onDelete('set null');
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('products')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rma_items');
    }
};
