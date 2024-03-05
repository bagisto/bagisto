<?php

namespace Webkul\Installer\Database\Seeders\Attribute;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeOptionTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('attribute_options')->delete();

        DB::table('attribute_option_translations')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('attribute_options')->insert([
            [
                'id'           => 1,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.red', [], $defaultLocale),
                'sort_order'   => 1,
                'attribute_id' => 23,
            ], [
                'id'           => 2,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.green', [], $defaultLocale),
                'sort_order'   => 2,
                'attribute_id' => 23,
            ], [
                'id'           => 3,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.yellow', [], $defaultLocale),
                'sort_order'   => 3,
                'attribute_id' => 23,
            ], [
                'id'           => 4,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.black', [], $defaultLocale),
                'sort_order'   => 4,
                'attribute_id' => 23,
            ], [
                'id'           => 5,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.white', [], $defaultLocale),
                'sort_order'   => 5,
                'attribute_id' => 23,
            ], [
                'id'           => 6,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.s', [], $defaultLocale),
                'sort_order'   => 1,
                'attribute_id' => 24,
            ], [
                'id'           => 7,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.m', [], $defaultLocale),
                'sort_order'   => 2,
                'attribute_id' => 24,
            ], [
                'id'           => 8,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.l', [], $defaultLocale),
                'sort_order'   => 3,
                'attribute_id' => 24,
            ], [
                'id'           => 9,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.xl', [], $defaultLocale),
                'sort_order'   => 4,
                'attribute_id' => 24,
            ],
        ]);

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        foreach ($locales as $locale) {
            DB::table('attribute_option_translations')->insert([
                [
                    'locale'              => $locale,
                    'label'               => trans('installer::app.seeders.attribute.attribute-options.red', [], $locale),
                    'attribute_option_id' => 1,
                ], [
                    'locale'              => $locale,
                    'label'               => trans('installer::app.seeders.attribute.attribute-options.green', [], $locale),
                    'attribute_option_id' => 2,
                ], [
                    'locale'              => $locale,
                    'label'               => trans('installer::app.seeders.attribute.attribute-options.yellow', [], $locale),
                    'attribute_option_id' => 3,
                ], [
                    'locale'              => $locale,
                    'label'               => trans('installer::app.seeders.attribute.attribute-options.black', [], $locale),
                    'attribute_option_id' => 4,
                ], [
                    'locale'              => $locale,
                    'label'               => trans('installer::app.seeders.attribute.attribute-options.white', [], $locale),
                    'attribute_option_id' => 5,
                ], [
                    'locale'              => $locale,
                    'label'               => trans('installer::app.seeders.attribute.attribute-options.s', [], $locale),
                    'attribute_option_id' => 6,
                ], [
                    'locale'              => $locale,
                    'label'               => trans('installer::app.seeders.attribute.attribute-options.m', [], $locale),
                    'attribute_option_id' => 7,
                ], [
                    'locale'              => $locale,
                    'label'               => trans('installer::app.seeders.attribute.attribute-options.l', [], $locale),
                    'attribute_option_id' => 8,
                ], [
                    'locale'              => $locale,
                    'label'               => trans('installer::app.seeders.attribute.attribute-options.xl', [], $locale),
                    'attribute_option_id' => 9,
                ],
            ]);
        }
    }
}
