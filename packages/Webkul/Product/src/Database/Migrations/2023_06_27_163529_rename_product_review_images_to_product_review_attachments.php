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
        });

        Schema::rename('product_review_images', 'product_review_attachments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_review_images', function (Blueprint $table) {
            $table->text('type')->nullable()->change();
        });

        Schema::rename('product_review_attachments', 'product_review_images');
    }
};
