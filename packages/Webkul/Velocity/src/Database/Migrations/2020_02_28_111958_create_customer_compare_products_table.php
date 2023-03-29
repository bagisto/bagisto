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
        Schema::create('velocity_customer_compare_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_flat_id');
            $table->foreign('product_flat_id')
                  ->references('id')
                  ->on('product_flat')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
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
        Schema::dropIfExists('velocity_customer_compare_products');
    }
};
