<?php

namespace Webkul\Installer\Database\Seeders\Attribute;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeOptionTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('attribute_options')->delete();

        DB::table('attribute_option_translations')->delete();

        DB::table('attribute_options')->insert([
            [
                'id'           => 1,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.red'),
                'sort_order'   => 1,
                'attribute_id' => 23,
            ], [
                'id'           => 2,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.green'),
                'sort_order'   => 2,
                'attribute_id' => 23,
            ], [
                'id'           => 3,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.yellow'),
                'sort_order'   => 3,
                'attribute_id' => 23,
            ], [
                'id'           => 4,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.black'),
                'sort_order'   => 4,
                'attribute_id' => 23,
            ], [
                'id'           => 5,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.white'),
                'sort_order'   => 5,
                'attribute_id' => 23,
            ], [
                'id'           => 6,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.s'),
                'sort_order'   => 1,
                'attribute_id' => 24,
            ], [
                'id'           => 7,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.m'),
                'sort_order'   => 2,
                'attribute_id' => 24,
            ], [
                'id'           => 8,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.l'),
                'sort_order'   => 3,
                'attribute_id' => 24,
            ], [
                'id'           => 9,
                'admin_name'   => trans('installer::app.seeders.attribute.attribute-options.xl'),
                'sort_order'   => 4,
                'attribute_id' => 24,
            ],
        ]);

        DB::table('attribute_option_translations')->insert([
            [
                'id'                  => 1,
                'locale'              => config('app.locale'),
                'label'               => trans('installer::app.seeders.attribute.attribute-options.red'),
                'attribute_option_id' => 1,
            ], [
                'id'                  => 2,
                'locale'              => config('app.locale'),
                'label'               => trans('installer::app.seeders.attribute.attribute-options.green'),
                'attribute_option_id' => 2,
            ], [
                'id'                  => 3,
                'locale'              => config('app.locale'),
                'label'               => trans('installer::app.seeders.attribute.attribute-options.yellow'),
                'attribute_option_id' => 3,
            ], [
                'id'                  => 4,
                'locale'              => config('app.locale'),
                'label'               => trans('installer::app.seeders.attribute.attribute-options.black'),
                'attribute_option_id' => 4,
            ], [
                'id'                  => 5,
                'locale'              => config('app.locale'),
                'label'               => trans('installer::app.seeders.attribute.attribute-options.white'),
                'attribute_option_id' => 5,
            ], [
                'id'                  => 6,
                'locale'              => config('app.locale'),
                'label'               => trans('installer::app.seeders.attribute.attribute-options.s'),
                'attribute_option_id' => 6,
            ], [
                'id'                  => 7,
                'locale'              => config('app.locale'),
                'label'               => trans('installer::app.seeders.attribute.attribute-options.m'),
                'attribute_option_id' => 7,
            ], [
                'id'                  => 8,
                'locale'              => config('app.locale'),
                'label'               => trans('installer::app.seeders.attribute.attribute-options.l'),
                'attribute_option_id' => 8,
            ], [
                'id'                  => 9,
                'locale'              => config('app.locale'),
                'label'               => trans('installer::app.seeders.attribute.attribute-options.xl'),
                'attribute_option_id' => 9,
            ],
        ]);
    }
}
