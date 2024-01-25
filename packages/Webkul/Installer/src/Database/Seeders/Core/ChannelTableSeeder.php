<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('channels')->delete();

        DB::table('channel_translations')->delete();

        DB::table('channel_currencies')->delete();

        DB::table('channel_locales')->delete();

        DB::table('channel_inventory_sources')->delete();

        DB::table('channels')->insert([
            [
                'id'                => 1,
                'code'              => 'default',
                'theme'             => 'default',
                'hostname'          => config('app.url'),
                'root_category_id'  => 1,
                'default_locale_id' => 1,
                'base_currency_id'  => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        foreach ($locales as $locale) {
            DB::table('channel_translations')->insert([
                [
                    'channel_id' => 1,
                    'locale'     => $locale,
                    'name'       => trans('installer::app.seeders.core.channels.name', [], $locale),
                    'home_seo'   => json_encode([
                        'meta_title'       => trans('installer::app.seeders.core.channels.meta-title', [], $locale),
                        'meta_description' => trans('installer::app.seeders.core.channels.meta-description', [], $locale),
                        'meta_keywords'    => trans('installer::app.seeders.core.channels.meta-keywords', [], $locale),
                    ]),
                ],
            ]);
        }

        $currencies = DB::table('currencies')->get();

        foreach ($currencies as $currency) {
            DB::table('channel_currencies')->insert([
                [
                    'channel_id'  => 1,
                    'currency_id' => $currency->id,
                ],
            ]);
        }

        $locales = DB::table('locales')->get();

        foreach ($locales as $locale) {
            DB::table('channel_locales')->insert([
                [
                    'channel_id' => 1,
                    'locale_id'  => $locale->id,
                ],
            ]);
        }

        DB::table('channel_inventory_sources')->insert([
            'channel_id'          => 1,
            'inventory_source_id' => 1,
        ]);
    }
}
