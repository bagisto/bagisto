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
        Schema::table('product_images', function (Blueprint $table) {
            if (!Schema::hasIndex('product_images', 'product_images_product_id_index')) {
                $table->index('product_id');
            }
        });

        Schema::table('product_videos', function (Blueprint $table) {
            if (!Schema::hasIndex('product_videos', 'product_videos_product_id_index')) {
                $table->index('product_id');
            }
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            if (!Schema::hasIndex('product_reviews', 'product_reviews_product_id_index')) {
                $table->index('product_id');
            }
        });

        Schema::table('product_inventory_indices', function (Blueprint $table) {
            if (!Schema::hasIndex('product_inventory_indices', 'product_inventory_indices_product_id_index')) {
                $table->index('product_id');
            }
        });

        Schema::table('product_attribute_values', function (Blueprint $table) {
            if (!Schema::hasIndex('product_attribute_values', 'product_attribute_values_product_id_index')) {
                $table->index('product_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('product_videos', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('product_inventory_indices', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });
    }
};
