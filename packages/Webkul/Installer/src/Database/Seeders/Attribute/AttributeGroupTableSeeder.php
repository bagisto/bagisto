<?php

namespace Webkul\Installer\Database\Seeders\Attribute;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeGroupTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('attribute_groups')->delete();

        DB::table('attribute_group_mappings')->delete();

        DB::table('attribute_groups')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('attribute_groups')->insert([
            [
                'id'                  => 1,
                'code'                => 'general',
                'name'                => trans('installer::app.seeders.attribute.attribute-groups.general', [], $defaultLocale),
                'column'              => 1,
                'is_user_defined'     => 0,
                'position'            => 1,
                'attribute_family_id' => 1,
            ], [
                'id'                  => 2,
                'code'                => 'description',
                'name'                => trans('installer::app.seeders.attribute.attribute-groups.description', [], $defaultLocale),
                'column'              => 1,
                'is_user_defined'     => 0,
                'position'            => 2,
                'attribute_family_id' => 1,
            ], [
                'id'                  => 3,
                'code'                => 'meta_description',
                'name'                => trans('installer::app.seeders.attribute.attribute-groups.meta-description', [], $defaultLocale),
                'column'              => 1,
                'is_user_defined'     => 0,
                'position'            => 3,
                'attribute_family_id' => 1,
            ], [
                'id'                  => 4,
                'code'                => 'price',
                'name'                => trans('installer::app.seeders.attribute.attribute-groups.price', [], $defaultLocale),
                'column'              => 2,
                'is_user_defined'     => 0,
                'position'            => 1,
                'attribute_family_id' => 1,
            ], [
                'id'                  => 5,
                'code'                => 'shipping',
                'name'                => trans('installer::app.seeders.attribute.attribute-groups.shipping', [], $defaultLocale),
                'column'              => 2,
                'is_user_defined'     => 0,
                'position'            => 2,
                'attribute_family_id' => 1,
            ], [
                'id'                  => 6,
                'code'                => 'settings',
                'name'                => trans('installer::app.seeders.attribute.attribute-groups.settings', [], $defaultLocale),
                'column'              => 2,
                'is_user_defined'     => 0,
                'position'            => 3,
                'attribute_family_id' => 1,
            ], [
                'id'                  => 7,
                'code'                => 'inventories',
                'name'                => trans('installer::app.seeders.attribute.attribute-groups.inventories', [], $defaultLocale),
                'column'              => 2,
                'is_user_defined'     => 0,
                'position'            => 4,
                'attribute_family_id' => 1,
            ],
        ]);

        DB::table('attribute_group_mappings')->insert([
            /**
             * General Group Attributes
             */
            [
                'attribute_id'        => 1,
                'attribute_group_id'  => 1,
                'position'            => 1,
            ], [
                'attribute_id'        => 27,
                'attribute_group_id'  => 1,
                'position'            => 2,
            ], [
                'attribute_id'        => 2,
                'attribute_group_id'  => 1,
                'position'            => 3,
            ], [
                'attribute_id'        => 3,
                'attribute_group_id'  => 1,
                'position'            => 4,
            ], [
                'attribute_id'        => 4,
                'attribute_group_id'  => 1,
                'position'            => 5,
            ], [
                'attribute_id'        => 23,
                'attribute_group_id'  => 1,
                'position'            => 6,
            ], [
                'attribute_id'        => 24,
                'attribute_group_id'  => 1,
                'position'            => 7,
            ], [
                'attribute_id'        => 25,
                'attribute_group_id'  => 1,
                'position'            => 8,
            ],

            /**
             * Description Group Attributes
             */
            [
                'attribute_id'        => 9,
                'attribute_group_id'  => 2,
                'position'            => 1,
            ], [
                'attribute_id'        => 10,
                'attribute_group_id'  => 2,
                'position'            => 2,
            ],

            /**
             * Meta Description Group Attributes
             */
            [
                'attribute_id'        => 11,
                'attribute_group_id'  => 4,
                'position'            => 1,
            ], [
                'attribute_id'        => 12,
                'attribute_group_id'  => 4,
                'position'            => 2,
            ], [
                'attribute_id'        => 13,
                'attribute_group_id'  => 4,
                'position'            => 3,
            ], [
                'attribute_id'        => 14,
                'attribute_group_id'  => 4,
                'position'            => 4,
            ], [
                'attribute_id'        => 15,
                'attribute_group_id'  => 4,
                'position'            => 5,
            ],

            /**
             * Price Group Attributes
             */
            [
                'attribute_id'        => 16,
                'attribute_group_id'  => 3,
                'position'            => 1,
            ], [
                'attribute_id'        => 17,
                'attribute_group_id'  => 3,
                'position'            => 2,
            ], [
                'attribute_id'        => 18,
                'attribute_group_id'  => 3,
                'position'            => 3,
            ],

            /**
             * Shipping Group Attributes
             */
            [
                'attribute_id'        => 19,
                'attribute_group_id'  => 5,
                'position'            => 1,
            ], [
                'attribute_id'        => 20,
                'attribute_group_id'  => 5,
                'position'            => 2,
            ], [
                'attribute_id'        => 21,
                'attribute_group_id'  => 5,
                'position'            => 3,
            ], [
                'attribute_id'        => 22,
                'attribute_group_id'  => 5,
                'position'            => 4,
            ],

            /**
             * Settings Group Attributes
             */
            [
                'attribute_id'        => 5,
                'attribute_group_id'  => 6,
                'position'            => 1,
            ], [
                'attribute_id'        => 6,
                'attribute_group_id'  => 6,
                'position'            => 2,
            ], [
                'attribute_id'        => 7,
                'attribute_group_id'  => 6,
                'position'            => 3,
            ], [
                'attribute_id'        => 8,
                'attribute_group_id'  => 6,
                'position'            => 4,
            ], [
                'attribute_id'        => 26,
                'attribute_group_id'  => 6,
                'position'            => 5,
            ],

            /**
             * Inventories Group Attributes
             */
            [
                'attribute_id'        => 28,
                'attribute_group_id'  => 7,
                'position'            => 1,
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }
}
