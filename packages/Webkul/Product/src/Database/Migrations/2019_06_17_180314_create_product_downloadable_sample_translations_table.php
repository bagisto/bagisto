<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDownloadableSampleTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_downloadable_sample_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale');
            $table->text('title')->nullable();
            
            $table->integer('product_downloadable_sample_id')->unsigned();
            $table->foreign('product_downloadable_sample_id', 'sample_translations_sample_id_foreign')->references('id')->on('product_downloadable_samples')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_downloadable_sample_translations');
    }
}
