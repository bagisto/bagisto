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
        Schema::create('product_downloadable_link_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_downloadable_link_id')->unsigned();
            $table->string('locale');
            $table->text('title')->nullable();

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
};
