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
        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status')->nullable();
            $table->integer('total_qty')->nullable();
            $table->integer('total_weight')->nullable();
            $table->string('carrier_code')->nullable();
            $table->string('carrier_title')->nullable();
            $table->text('track_number')->nullable();
            $table->boolean('email_sent')->default(0);
            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('customer_type')->nullable();
            $table->integer('order_id')->unsigned();
            $table->integer('order_address_id')->unsigned()->nullable();
            $table->integer('inventory_source_id')->unsigned()->nullable();
            $table->string('inventory_source_name')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('inventory_source_id')->references('id')->on('inventory_sources')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
};
