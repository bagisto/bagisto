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

            $table->string('mime_type')->after('type')->nullable();
        });

        Schema::rename('product_review_images', 'product_review_attachments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('product_review_attachments', 'product_review_images');

        Schema::table('product_review_images', function (Blueprint $table) {
            $table->string('type')->nullable()->change();

            $table->dropColumn('mime_type');
        });
    }
};
