<?php

namespace Webkul\Installer\Database\Seeders\Category;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/*
 * Category table seeder.
 *
 * Command: php artisan db:seed --class=Webkul\\Category\\Database\\Seeders\\CategoryTableSeeder
 */
class CategoryTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('categories')->delete();

        DB::table('category_translations')->delete();

        $now = Carbon::now();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('categories')->insert([
            [
                'id'          => '1',
                'position'    => '1',
                'logo_path'   => null,
                'status'      => '1',
                '_lft'        => '1',
                '_rgt'        => '14',
                'parent_id'   => null,
                'banner_path' => null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        foreach ($locales as $locale) {
            DB::table('category_translations')->insert([
                [
                    'name'             => trans('installer::app.seeders.category.categories.name', [], $locale),
                    'slug'             => 'root',
                    'description'      => trans('installer::app.seeders.category.categories.description', [], $locale),
                    'meta_title'       => '',
                    'meta_description' => '',
                    'meta_keywords'    => '',
                    'category_id'      => '1',
                    'locale'           => $locale,
                ],
            ]);
        }
    }

    /**
     * Create Sample Categories.
     *
     * @return void
     */
    public function sampleCategories(array $parameters = [])
    {
        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        $now = Carbon::now();

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        DB::table('categories')->insert([
            [
                'id'            => 2,
                'position'      => 1,
                'logo_path'     => null,
                'status'        => 1,
                'display_mode'  => 'products_and_description',
                '_lft'          => 1,
                '_rgt'          => 1,
                'parent_id'     => 1,
                'additional'    => null,
                'banner_path'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,

            ], [
                'id'            => 3,
                'position'      => 1,
                'logo_path'     => null,
                'status'        => 1,
                'display_mode'  => 'products_and_description',
                '_lft'          => 1,
                '_rgt'          => 1,
                'parent_id'     => 2,
                'additional'    => null,
                'banner_path'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,

            ],
        ]);

        foreach ($locales as $locale) {
            DB::table('category_translations')->insert([
                [
                    'category_id'      => 2,
                    'name'             => trans('installer::app.seeders.sample-categories.category-translation.2.name', [], $locale),
                    'slug'             => trans('installer::app.seeders.sample-categories.category-translation.2.slug', [], $locale),
                    'url_path'         => '',
                    'description'      => trans('installer::app.seeders.sample-categories.category-translation.2.description', [], $locale),
                    'meta_title'       => trans('installer::app.seeders.sample-categories.category-translation.2.meta-title', [], $locale),
                    'meta_description' => trans('installer::app.seeders.sample-categories.category-translation.2.meta-description', [], $locale),
                    'meta_keywords'    => trans('installer::app.seeders.sample-categories.category-translation.2.meta-keywords', [], $locale),
                    'locale_id'        => null,
                    'locale'           => $locale,
                ], [
                    'category_id'      => 3,
                    'name'             => trans('installer::app.seeders.sample-categories.category-translation.3.name', [], $locale),
                    'slug'             => trans('installer::app.seeders.sample-categories.category-translation.3.slug', [], $locale),
                    'url_path'         => '',
                    'description'      => trans('installer::app.seeders.sample-categories.category-translation.3.description', [], $locale),
                    'meta_title'       => trans('installer::app.seeders.sample-categories.category-translation.3.meta-title', [], $locale),
                    'meta_description' => trans('installer::app.seeders.sample-categories.category-translation.3.meta-description', [], $locale),
                    'meta_keywords'    => trans('installer::app.seeders.sample-categories.category-translation.3.meta-keywords', [], $locale),
                    'locale_id'        => null,
                    'locale'           => $locale,
                ],
            ]);
        }

        DB::table('category_filterable_attributes')->insert([
            [
                'category_id'  => 2,
                'attribute_id' => 11,
            ], [
                'category_id'  => 2,
                'attribute_id' => 23,
            ], [
                'category_id'  => 2,
                'attribute_id' => 24,
            ], [
                'category_id'  => 2,
                'attribute_id' => 25,
            ], [
                'category_id'  => 3,
                'attribute_id' => 11,
            ], [
                'category_id'  => 3,
                'attribute_id' => 23,
            ], [
                'category_id'  => 3,
                'attribute_id' => 24,
            ], [
                'category_id'  => 3,
                'attribute_id' => 25,
            ],
        ]);
    }
}
