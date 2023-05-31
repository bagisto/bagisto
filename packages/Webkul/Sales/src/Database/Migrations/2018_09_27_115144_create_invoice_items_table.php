<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('sku')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('price', 12, 4)->default(0);
            $table->decimal('base_price', 12, 4)->default(0);
            $table->decimal('total', 12, 4)->default(0);
            $table->decimal('base_total', 12, 4)->default(0);
            $table->decimal('tax_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount', 12, 4)->default(0)->nullable();
            $table->decimal('discount_percent', 12, 4)->default(0)->nullable();
            $table->decimal('discount_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_discount_amount', 12, 4)->default(0)->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->string('product_type')->nullable();
            $table->integer('order_item_id')->unsigned()->nullable();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->json('additional')->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('invoice_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
