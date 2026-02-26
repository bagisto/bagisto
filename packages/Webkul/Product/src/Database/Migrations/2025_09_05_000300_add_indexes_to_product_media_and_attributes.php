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
            if (! Schema::hasIndex('product_images', 'prod_img_product_id_idx')) {
                $table->index('product_id', 'prod_img_product_id_idx');
            }
        });

        Schema::table('product_videos', function (Blueprint $table) {
            if (! Schema::hasIndex('product_videos', 'prod_vid_product_id_idx')) {
                $table->index('product_id', 'prod_vid_product_id_idx');
            }
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            if (! Schema::hasIndex('product_reviews', 'prod_rev_product_id_idx')) {
                $table->index('product_id', 'prod_rev_product_id_idx');
            }
        });

        Schema::table('product_inventory_indices', function (Blueprint $table) {
            if (! Schema::hasIndex('product_inventory_indices', 'prod_inv_product_id_idx')) {
                $table->index('product_id', 'prod_inv_product_id_idx');
            }
        });

        Schema::table('product_attribute_values', function (Blueprint $table) {
            if (! Schema::hasIndex('product_attribute_values', 'prod_attr_product_id_idx')) {
                $table->index('product_id', 'prod_attr_product_id_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_inventory_indices', function (Blueprint $table) {
            if (Schema::hasIndex('product_inventory_indices', 'prod_inv_product_id_idx')) {
                $table->dropIndex('prod_inv_product_id_idx');
            }
        });
    }
};
