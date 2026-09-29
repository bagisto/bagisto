<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The settings that used to size the cached product images and carry their placeholders, now
     * fixed in the image templates and shipped by the theme.
     */
    public const CODES = [
        'catalog.products.cache_small_image.width',
        'catalog.products.cache_small_image.height',
        'catalog.products.cache_small_image.url',
        'catalog.products.cache_medium_image.width',
        'catalog.products.cache_medium_image.height',
        'catalog.products.cache_medium_image.url',
        'catalog.products.cache_large_image.width',
        'catalog.products.cache_large_image.height',
        'catalog.products.cache_large_image.url',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('core_config')->whereIn('code', self::CODES)->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
