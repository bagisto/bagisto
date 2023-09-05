<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('channels')->delete();

        DB::table('channels')->insert([
            'id'                => 1,
            'code'              => 'default',
            'theme'             => 'default',
            'hostname'          => config('app.url'),
            'root_category_id'  => 1,
            'default_locale_id' => 1,
            'base_currency_id'  => 1,
        ]);

        DB::table('channel_translations')->insert([
            [
                'id'                => 1,
                'channel_id'        => 1,
                'locale'            => 'en',
                'name'              => 'Default',
                'home_seo'          => '{"meta_title": "Demo store", "meta_keywords": "Demo store meta keyword", "meta_description": "Demo store meta description"}',
            ], [
                'id'                => 2,
                'channel_id'        => 1,
                'locale'            => 'fr',
                'name'              => 'Default',
                'home_seo'          => '{"meta_title": "Demo store", "meta_keywords": "Demo store meta keyword", "meta_description": "Demo store meta description"}',
            ], [
                'id'                => 3,
                'channel_id'        => 1,
                'locale'            => 'nl',
                'name'              => 'Default',
                'home_seo'          => '{"meta_title": "Demo store", "meta_keywords": "Demo store meta keyword", "meta_description": "Demo store meta description"}',
            ], [
                'id'                => 4,
                'channel_id'        => 1,
                'locale'            => 'tr',
                'name'              => 'Default',
                'home_seo'          => '{"meta_title": "Demo store", "meta_keywords": "Demo store meta keyword", "meta_description": "Demo store meta description"}',
            ], [
                'id'                => 5,
                'channel_id'        => 1,
                'locale'            => 'es',
                'name'              => 'Default',
                'home_seo'          => '{"meta_title": "Demo store", "meta_keywords": "Demo store meta keyword", "meta_description": "Demo store meta description"}',
            ],
        ]);

        DB::table('channel_currencies')->insert([
            'channel_id'  => 1,
            'currency_id' => 1,
        ]);

        DB::table('channel_locales')->insert([
            'channel_id' => 1,
            'locale_id'  => 1,
        ]);

        DB::table('channel_inventory_sources')->insert([
            'channel_id'          => 1,
            'inventory_source_id' => 1,
        ]);
    }
}
