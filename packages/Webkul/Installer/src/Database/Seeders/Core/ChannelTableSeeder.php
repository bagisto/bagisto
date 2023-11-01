<?php

namespace Webkul\Installer\Database\Seeders\Core;

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
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        DB::table('channel_translations')->insert([
            [
                'id'                => 1,
                'channel_id'        => 1,
                'locale'            => config('app.locale'),
                'name'              => trans('installer::app.seeders.core.channels.name'),
                'home_seo'          => json_encode([
                    'meta_title'       => trans('installer::app.seeders.core.channels.meta-title'),
                    'meta_description' => trans('installer::app.seeders.core.channels.meta-description'),
                    'meta_keywords'    => trans('installer::app.seeders.core.channels.meta-keywords'),
                ]),
            ],
        ]);

        DB::table('channel_currencies')->insert([
            [
                'channel_id'  => 1,
                'currency_id' => 1,
            ],
        ]);

        DB::table('channel_locales')->insert([
            [
                'channel_id' => 1,
                'locale_id'  => 1,
            ],
        ]);

        DB::table('channel_inventory_sources')->insert([
            'channel_id'          => 1,
            'inventory_source_id' => 1,
        ]);
    }
}
