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
        Schema::create('cms_page_channels', function (Blueprint $table) {
            $table->integer('cms_page_id')->unsigned();
            $table->integer('channel_id')->unsigned();

            $table->unique(['cms_page_id', 'channel_id']);
            $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
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
        Schema::dropIfExists('cms_page_channels');
    }
};
