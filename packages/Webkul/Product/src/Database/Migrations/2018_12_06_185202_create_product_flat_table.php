<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductFlatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_flat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('url_key')->nullable();
            $table->boolean('new')->nullable();
            $table->boolean('featured')->nullable();
            $table->boolean('visibility')->nullable();
            $table->boolean('status')->nullable();
            $table->string('thumbnail')->nullable();

            $table->decimal('price', 12, 4)->nullable();
            $table->decimal('cost', 12, 4)->nullable();
            $table->boolean('special_price')->nullable();
            $table->date('special_price_from')->nullable();
            $table->date('special_price_to')->nullable();

            $table->decimal('weight', 12, 4)->nullable();
            $table->integer('color')->nullable();
            $table->string('color_label')->nullable();
            $table->integer('size')->nullable();
            $table->integer('size_label')->nullable();

            $table->date('created_at')->nullable();
            
            $table->string('locale')->nullable();
            $table->string('channel')->nullable();

            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_flat');
    }
}
