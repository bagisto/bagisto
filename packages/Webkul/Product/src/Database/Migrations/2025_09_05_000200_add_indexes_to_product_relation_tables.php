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
        Schema::table('product_price_indices', function (Blueprint $table) {
            if (! Schema::hasIndex('product_price_indices', 'ppi_product_id_customer_group_id_idx')) {
                $table->index(['product_id', 'customer_group_id'], 'ppi_product_id_customer_group_id_idx');
            }
        });

        Schema::table('product_channels', function (Blueprint $table) {
            if (! Schema::hasIndex('product_channels', 'pc_product_id_channel_id_idx')) {
                $table->index(['product_id', 'channel_id'], 'pc_product_id_channel_id_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_price_indices', function (Blueprint $table) {
            if (Schema::hasIndex('product_price_indices', 'ppi_product_id_customer_group_id_idx')) {
                $table->dropIndex('ppi_product_id_customer_group_id_idx');
            }
        });

        Schema::table('product_channels', function (Blueprint $table) {
            if (Schema::hasIndex('product_channels', 'pc_product_id_channel_id_idx')) {
                $table->dropIndex('pc_product_id_channel_id_idx');
            }
        });
    }
};
