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
            if (!Schema::hasIndex('product_price_indices', 'product_price_indices_product_id_customer_group_id_index')) {
                $table->index(['product_id', 'customer_group_id']);
            }
        });

        Schema::table('product_channels', function (Blueprint $table) {
            if (!Schema::hasIndex('product_channels', 'product_channels_product_id_channel_id_index')) {
                $table->index(['product_id', 'channel_id']);
            }
        });

        // Primary key check removed as it's already handled by table creation
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_price_indices', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'customer_group_id']);
        });

        Schema::table('product_channels', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'channel_id']);
        });

        // Don't drop the primary key in down migration as it's fundamental
    }
};
