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
}
