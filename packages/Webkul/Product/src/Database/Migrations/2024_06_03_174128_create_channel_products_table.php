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
        Schema::create('channel_products', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->integer('channel_id')->unsigned();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
        });

        $firstChannelId = DB::table('channels')->value('id');

        if (! $firstChannelId) {
            return;
        }

        DB::table('channel_products')->insertUsing(
            ['product_id', 'channel_id'],
            DB::table('products')
                ->select('id as product_id', DB::raw("$firstChannelId as channel_id"))
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_products');
    }
};
