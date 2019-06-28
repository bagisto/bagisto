<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDownloadableLinkTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_downloadable_link_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale');
            $table->text('title')->nullable();
            
            $table->integer('product_downloadable_link_id')->unsigned();
            $table->foreign('product_downloadable_link_id', 'link_translations_link_id_foreign')->references('id')->on('product_downloadable_links')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_downloadable_link_translations');
    }
}
