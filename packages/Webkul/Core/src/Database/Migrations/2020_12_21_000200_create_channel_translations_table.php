<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('channel_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('home_page_content')->nullable();
            $table->text('footer_content')->nullable();
            $table->text('maintenance_mode_text')->nullable();
            $table->json('home_seo')->nullable();
            $table->timestamps();

            $table->unique(['channel_id', 'locale']);
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_translations');
    }
}
