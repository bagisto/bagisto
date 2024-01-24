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
        $connection = config('database.default');

        $driver = config("database.connections.{$connection}.driver");

        switch ($driver) {
            case 'mysql':
                Schema::table('product_price_indices', function (Blueprint $table) {
                    $table->dropForeign('product_price_indices_product_id_foreign');
                    $table->dropForeign('product_price_indices_customer_group_id_foreign');
                    $table->dropUnique('product_price_indices_product_id_customer_group_id_unique');

                    $table->integer('channel_id')->unsigned()->default(1)->after('customer_group_id');

                    $table->unique(['product_id', 'customer_group_id', 'channel_id'], 'price_indices_product_id_customer_group_id_channel_id_unique');
                    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                    $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
                    $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
                });

                break;

            case 'sqlite':
                Schema::dropIfExists('product_price_indices');

                Schema::create('product_price_indices', function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('product_id')->unsigned();
                    $table->integer('customer_group_id')->unsigned()->nullable();
                    $table->integer('channel_id')->unsigned()->default(1);
                    $table->decimal('min_price', 12, 4)->default(0);
                    $table->decimal('regular_min_price', 12, 4)->default(0);
                    $table->decimal('max_price', 12, 4)->default(0);
                    $table->decimal('regular_max_price', 12, 4)->default(0);
                    $table->timestamps();

                    $table->unique(['product_id', 'customer_group_id', 'channel_id'], 'price_indices_product_id_customer_group_id_channel_id_unique');
                    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                    $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
                    $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
                });

                break;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_price_indices', function (Blueprint $table) {
            $table->dropForeign('product_price_indices_channel_id_foreign');
            $table->dropForeign('product_price_indices_customer_group_id_foreign');
            $table->dropForeign('product_price_indices_product_id_foreign');
            $table->dropUnique('price_indices_product_id_customer_group_id_channel_id_unique');

            $table->dropColumn('channel_id');

            $table->unique(['product_id', 'customer_group_id']);
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};
