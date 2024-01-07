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
            $table->integer('channel_id')->unsigned()->default(1)->after('customer_group_id');

            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');

            $table->dropForeign('product_price_indices_product_id_foreign');
            $table->dropForeign('product_price_indices_customer_group_id_foreign');

            $table->dropUnique('product_price_indices_product_id_customer_group_id_unique');

            $table->unique(['product_id', 'customer_group_id', 'channel_id'], 'price_indices_product_id_customer_group_id_channel_id_unique');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_price_indices', function (Blueprint $table) {
            $table->dropForeign('product_price_indices_product_id_foreign');
            $table->dropForeign('product_price_indices_customer_group_id_foreign');
            $table->dropForeign('product_price_indices_channel_id_foreign');

            $table->dropUnique('price_indices_product_id_customer_group_id_channel_id_unique');

            $table->dropColumn('channel_id');

            $table->unique(['product_id', 'customer_group_id']);

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
        });
    }
};
