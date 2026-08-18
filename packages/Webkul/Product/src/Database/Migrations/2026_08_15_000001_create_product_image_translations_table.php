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
        Schema::create('product_image_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_image_id')->unsigned();
            $table->string('locale');
            $table->text('alt_text')->nullable();

            $table->unique(['product_image_id', 'locale'], 'image_translations_image_id_locale_unique');

            $table->foreign('product_image_id', 'image_translations_image_id_foreign')
                ->references('id')
                ->on('product_images')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_image_translations');
    }
};
