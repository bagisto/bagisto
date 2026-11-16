<?php

namespace Webkul\Installer\Database\Seeders\Attribute;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Concerns\SyncsPostgresSequences;

class AttributeGroupTableSeeder extends Seeder
{
    use SyncsPostgresSequences;

    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('attribute_groups')->delete();

        DB::table('attribute_group_mappings')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('attribute_groups')->insert([
            [
                'id' => 1,
                'code' => 'general',
                'admin_name' => trans('installer::app.seeders.attribute.attribute-groups.general', [], $defaultLocale),
                'column' => 1,
                'is_user_defined' => 0,
                'position' => 1,
                'attribute_family_id' => 1,
            ], [
                'id' => 2,
                'code' => 'description',
                'admin_name' => trans('installer::app.seeders.attribute.attribute-groups.description', [], $defaultLocale),
                'column' => 1,
                'is_user_defined' => 0,
                'position' => 2,
                'attribute_family_id' => 1,
            ], [
                'id' => 3,
                'code' => 'meta_description',
                'admin_name' => trans('installer::app.seeders.attribute.attribute-groups.meta-description', [], $defaultLocale),
                'column' => 1,
                'is_user_defined' => 0,
                'position' => 3,
                'attribute_family_id' => 1,
            ], [
                'id' => 4,
                'code' => 'price',
                'admin_name' => trans('installer::app.seeders.attribute.attribute-groups.price', [], $defaultLocale),
                'column' => 2,
                'is_user_defined' => 0,
                'position' => 1,
                'attribute_family_id' => 1,
            ], [
                'id' => 5,
                'code' => 'shipping',
                'admin_name' => trans('installer::app.seeders.attribute.attribute-groups.shipping', [], $defaultLocale),
                'column' => 2,
                'is_user_defined' => 0,
                'position' => 2,
                'attribute_family_id' => 1,
            ], [
                'id' => 6,
                'code' => 'settings',
                'admin_name' => trans('installer::app.seeders.attribute.attribute-groups.settings', [], $defaultLocale),
                'column' => 2,
                'is_user_defined' => 0,
                'position' => 3,
                'attribute_family_id' => 1,
            ], [
                'id' => 7,
                'code' => 'inventories',
                'admin_name' => trans('installer::app.seeders.attribute.attribute-groups.inventories', [], $defaultLocale),
                'column' => 2,
                'is_user_defined' => 0,
                'position' => 4,
                'attribute_family_id' => 1,
            ], [
                'id' => 8,
                'code' => 'rma',
                'admin_name' => trans('installer::app.seeders.attribute.attribute-groups.rma', [], $defaultLocale),
                'column' => 2,
                'is_user_defined' => 0,
                'position' => 5,
                'attribute_family_id' => 1,
            ],
        ]);

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        foreach ($locales as $locale) {
            DB::table('attribute_group_translations')->insert([
                [
                    'locale' => $locale,
                    'name' => trans('installer::app.seeders.attribute.attribute-groups.general', [], $locale),
                    'attribute_group_id' => 1,
                ], [
                    'locale' => $locale,
                    'name' => trans('installer::app.seeders.attribute.attribute-groups.description', [], $locale),
                    'attribute_group_id' => 2,
                ], [
                    'locale' => $locale,
                    'name' => trans('installer::app.seeders.attribute.attribute-groups.meta-description', [], $locale),
                    'attribute_group_id' => 3,
                ], [
                    'locale' => $locale,
                    'name' => trans('installer::app.seeders.attribute.attribute-groups.price', [], $locale),
                    'attribute_group_id' => 4,
                ], [
                    'locale' => $locale,
                    'name' => trans('installer::app.seeders.attribute.attribute-groups.shipping', [], $locale),
                    'attribute_group_id' => 5,
                ], [
                    'locale' => $locale,
                    'name' => trans('installer::app.seeders.attribute.attribute-groups.settings', [], $locale),
                    'attribute_group_id' => 6,
                ], [
                    'locale' => $locale,
                    'name' => trans('installer::app.seeders.attribute.attribute-groups.inventories', [], $locale),
                    'attribute_group_id' => 7,
                ], [
                    'locale' => $locale,
                    'name' => trans('installer::app.seeders.attribute.attribute-groups.rma', [], $locale),
                    'attribute_group_id' => 8,
                ],
            ]);
        }

        DB::table('attribute_group_mappings')->insert([
            /**
             * General group attributes.
             */
            [
                'attribute_id' => 1,
                'attribute_group_id' => 1,
                'position' => 1,
            ], [
                'attribute_id' => 27,
                'attribute_group_id' => 1,
                'position' => 2,
            ], [
                'attribute_id' => 2,
                'attribute_group_id' => 1,
                'position' => 3,
            ], [
                'attribute_id' => 3,
                'attribute_group_id' => 1,
                'position' => 4,
            ], [
                'attribute_id' => 4,
                'attribute_group_id' => 1,
                'position' => 5,
            ], [
                'attribute_id' => 23,
                'attribute_group_id' => 1,
                'position' => 6,
            ], [
                'attribute_id' => 24,
                'attribute_group_id' => 1,
                'position' => 7,
            ], [
                'attribute_id' => 25,
                'attribute_group_id' => 1,
                'position' => 8,
            ],

            /**
             * Description group attributes.
             */
            [
                'attribute_id' => 9,
                'attribute_group_id' => 2,
                'position' => 1,
            ], [
                'attribute_id' => 10,
                'attribute_group_id' => 2,
                'position' => 2,
            ],

            /**
             * Meta description group attributes.
             */
            [
                'attribute_id' => 11,
                'attribute_group_id' => 4,
                'position' => 1,
            ], [
                'attribute_id' => 12,
                'attribute_group_id' => 4,
                'position' => 2,
            ], [
                'attribute_id' => 13,
                'attribute_group_id' => 4,
                'position' => 3,
            ], [
                'attribute_id' => 14,
                'attribute_group_id' => 4,
                'position' => 4,
            ], [
                'attribute_id' => 15,
                'attribute_group_id' => 4,
                'position' => 5,
            ],

            /**
             * Price group attributes.
             */
            [
                'attribute_id' => 16,
                'attribute_group_id' => 3,
                'position' => 1,
            ], [
                'attribute_id' => 17,
                'attribute_group_id' => 3,
                'position' => 2,
            ], [
                'attribute_id' => 18,
                'attribute_group_id' => 3,
                'position' => 3,
            ],

            /**
             * Shipping group attributes.
             */
            [
                'attribute_id' => 19,
                'attribute_group_id' => 5,
                'position' => 1,
            ], [
                'attribute_id' => 20,
                'attribute_group_id' => 5,
                'position' => 2,
            ], [
                'attribute_id' => 21,
                'attribute_group_id' => 5,
                'position' => 3,
            ], [
                'attribute_id' => 22,
                'attribute_group_id' => 5,
                'position' => 4,
            ],

            /**
             * Settings group attributes.
             */
            [
                'attribute_id' => 5,
                'attribute_group_id' => 6,
                'position' => 1,
            ], [
                'attribute_id' => 6,
                'attribute_group_id' => 6,
                'position' => 2,
            ], [
                'attribute_id' => 7,
                'attribute_group_id' => 6,
                'position' => 3,
            ], [
                'attribute_id' => 8,
                'attribute_group_id' => 6,
                'position' => 4,
            ], [
                'attribute_id' => 26,
                'attribute_group_id' => 6,
                'position' => 5,
            ],

            /**
             * Inventories group attributes.
             */
            [
                'attribute_id' => 28,
                'attribute_group_id' => 7,
                'position' => 1,
            ],

            /**
             * RMA group attributes.
             */
            [
                'attribute_id' => 29,
                'attribute_group_id' => 8,
                'position' => 1,
            ], [
                'attribute_id' => 30,
                'attribute_group_id' => 8,
                'position' => 2,
            ],
        ]);

        $this->syncPostgresSequences(['attribute_groups']);
    }
}
