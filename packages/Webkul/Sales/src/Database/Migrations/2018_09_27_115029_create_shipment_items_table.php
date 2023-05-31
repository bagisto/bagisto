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
        Schema::create('shipment_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('sku')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('weight')->nullable();
            $table->decimal('price', 12, 4)->default(0)->nullable();
            $table->decimal('base_price', 12, 4)->default(0)->nullable();
            $table->decimal('total', 12, 4)->default(0)->nullable();
            $table->decimal('base_total', 12, 4)->default(0)->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->string('product_type')->nullable();
            $table->integer('order_item_id')->unsigned()->nullable();
            $table->integer('shipment_id')->unsigned();
            $table->json('additional')->nullable();
            $table->timestamps();

            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_items');
    }
};
