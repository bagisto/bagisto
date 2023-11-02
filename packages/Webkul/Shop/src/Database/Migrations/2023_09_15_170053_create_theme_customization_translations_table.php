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
        Schema::create('theme_customization_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('theme_customization_id')->unsigned();
            $table->string('locale');
            $table->json('options');
            $table->foreign('theme_customization_id')->references('id')->on('theme_customizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_customization_translations');
    }
};
