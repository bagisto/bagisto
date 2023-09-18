<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('theme_customization_channels', function (Blueprint $table) {
            $table->integer('theme_customization_id')->unsigned();
            $table->integer('channel_id')->unsigned();

            $table->unique(['theme_customization_id', 'channel_id'], 'theme_customization_channel_id');

            $table->foreign('theme_customization_id')->references('id')->on('theme_customizations')->onDelete('cascade');
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_customization_channels');
    }
};
