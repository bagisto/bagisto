<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeOptionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_option_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale');
            $table->text('label')->nullable();
            $table->integer('attribute_option_id')->unsigned();
            $table->unique(['attribute_option_id', 'locale']);
            $table->foreign('attribute_option_id')->references('id')->on('attribute_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_option_translations');
    }
}
