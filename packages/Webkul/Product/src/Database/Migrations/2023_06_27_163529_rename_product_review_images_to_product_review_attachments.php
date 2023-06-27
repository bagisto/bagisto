<?php

namespace Webkul\Product\Database\Migrations;

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
        Schema::table('product_review_images', function (Blueprint $table) {
            $table->string('type')->default('image')->change();
            $table->dropForeign(['review_id']);
        });

        Schema::rename('product_review_images', 'product_review_attachments');

        Schema::table('product_review_attachments', function (Blueprint $table) {
            $table->foreign('review_id')->references('id')->on('product_reviews');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_review_attachments', function (Blueprint $table) {
            $table->dropForeign(['review_id']);
        });

        Schema::rename('product_review_attachments', 'product_review_images');

        Schema::table('product_review_images', function (Blueprint $table) {
            $table->text('type')->nullable()->change();

            $table->foreign('review_id')->references('id')->on('product_reviews');
        });
    }
};
