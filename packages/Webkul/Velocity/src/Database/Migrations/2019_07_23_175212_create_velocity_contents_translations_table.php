<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVelocityContentsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('velocity_contents_translations', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('content_id')->unsigned()->nullable();
            $table->foreign('content_id')->references('id')->on('velocity_contents')->onDelete('cascade');

            $table->string('title', 100)->nullable();
            $table->string('custom_title', 100)->nullable();
            $table->string('custom_heading', 250)->nullable();

            $table->string('page_link', 500)->nullable();
            $table->boolean('link_target')->default(0);

            $table->string('catalog_type', 100)->nullable();
            $table->text('products')->nullable();

            $table->text('description')->nullable();

            $table->string('locale')->nullable();

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
        Schema::dropIfExists('velocity_contents_translations');
    }
}
