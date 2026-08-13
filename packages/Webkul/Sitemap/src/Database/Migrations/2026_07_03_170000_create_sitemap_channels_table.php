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
        Schema::create('sitemap_channels', function (Blueprint $table) {
            $table->unsignedInteger('sitemap_id');
            $table->unsignedInteger('channel_id');

            $table->unique(['sitemap_id', 'channel_id']);

            $table->foreign('sitemap_id')->references('id')->on('sitemaps')->cascadeOnDelete();
            $table->foreign('channel_id')->references('id')->on('channels')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sitemap_channels');
    }
};
