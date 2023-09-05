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
        Schema::create('product_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('qty')->default(0);
            $table->integer('product_id')->unsigned();
            $table->integer('vendor_id')->default(0);
            $table->integer('inventory_source_id')->unsigned();
            
            $table->unique(['product_id', 'inventory_source_id', 'vendor_id'], 'product_source_vendor_index_unique');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('inventory_source_id')->references('id')->on('inventory_sources')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_inventories');
    }
};
