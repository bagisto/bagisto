<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('preorder_type');
            $table->double('preorder_percent')->default(0);
            $table->string('token');
            $table->string('status');
            $table->boolean('email_sent')->default(0);
            $table->double('paid_amount')->nullable();
            $table->double('base_paid_amount')->nullable();
            $table->double('base_remaining_amount')->nullable();

            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->integer('order_item_id')->unsigned();
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');

            $table->integer('payment_order_item_id')->nullable()->unsigned();
            $table->foreign('payment_order_item_id')->references('id')->on('order_items')->onDelete('set null');

            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_order_items');
    }
}