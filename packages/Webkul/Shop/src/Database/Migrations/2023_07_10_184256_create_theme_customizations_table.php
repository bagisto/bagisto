<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('theme_customizations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->unsigned()->default(1);
            $table->string('type');
            $table->string('name');
            $table->integer('sort_order');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        $now = Carbon::now();

        DB::table('theme_customizations')
            ->insert([
                [
                    'id'         => 1,
                    'type'       => 'image_carousel',
                    'name'       => 'Image Carousel',
                    'sort_order' => 1,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 2,
                    'type'       => 'static_content',
                    'name'       => 'Offer Information',
                    'sort_order' => 2,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 3,
                    'type'       => 'category_carousel',
                    'name'       => 'Categories Collections',
                    'sort_order' => 3,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 4,
                    'type'       => 'product_carousel',
                    'name'       => 'New Products',
                    'sort_order' => 4,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 5,
                    'type'       => 'static_content',
                    'name'       => 'Top Collections',
                    'sort_order' => 5,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 6,
                    'type'       => 'static_content',
                    'name'       => 'Bold Collections',
                    'sort_order' => 6,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 7,
                    'type'       => 'product_carousel',
                    'name'       => 'Featured Collections',
                    'sort_order' => 7,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 8,
                    'type'       => 'static_content',
                    'name'       => 'Game Container',
                    'sort_order' => 8,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 9,
                    'type'       => 'product_carousel',
                    'name'       => 'All Products',
                    'sort_order' => 9,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 10,
                    'type'       => 'static_content',
                    'name'       => 'Bold Collections',
                    'sort_order' => 10,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], [
                    'id'         => 11,
                    'type'       => 'footer_links',
                    'name'       => 'Footer Links',
                    'sort_order' => 11,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_customizations');
    }
};
