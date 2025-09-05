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
        Schema::table('product_grouped_products', function (Blueprint $table) {
            if (!Schema::hasIndex('product_grouped_products', 'product_grouped_products_product_id_index')) {
                $table->index('product_id');
            }
        });

        Schema::table('product_bundle_option_products', function (Blueprint $table) {
            if (!Schema::hasIndex('product_bundle_option_products', 'product_bundle_option_products_option_id_index')) {
                $table->index('product_bundle_option_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_grouped_products', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('product_bundle_option_products', function (Blueprint $table) {
            $table->dropIndex(['product_bundle_option_id']);
        });
    }
};
