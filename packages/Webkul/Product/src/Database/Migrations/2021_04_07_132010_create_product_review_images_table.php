<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReviewImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_review_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->string('path');
            $table->integer('review_id')->unsigned();
            $table->foreign('review_id')->references('id')->on('product_reviews')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_review_images');
    }
}
