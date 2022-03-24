<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownloadableLinkPurchasedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloadable_link_purchased', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name')->nullable();
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->string('file')->nullable();
            $table->string('file_name')->nullable();
            $table->string('type');
            $table->integer('download_bought')->default(0);
            $table->integer('download_used')->default(0);
            $table->string('status')->nullable();
            
            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->integer('order_item_id')->unsigned();
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('downloadable_link_purchased');
    }
}
