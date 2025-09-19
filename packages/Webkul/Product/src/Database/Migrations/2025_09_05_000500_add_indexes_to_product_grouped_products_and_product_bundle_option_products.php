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
            if (! Schema::hasIndex('product_grouped_products', 'pgp_product_id_idx')) {
                $table->index('product_id', 'pgp_product_id_idx');
            }
        });

        Schema::table('product_bundle_option_products', function (Blueprint $table) {
            if (! Schema::hasIndex('product_bundle_option_products', 'pbop_option_id_idx')) {
                $table->index('product_bundle_option_id', 'pbop_option_id_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_grouped_products', function (Blueprint $table) {
            if (Schema::hasIndex('product_grouped_products', 'pgp_product_id_idx')) {
                $table->dropIndex('pgp_product_id_idx');
            }
        });
    }
};
