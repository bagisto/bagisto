<?php

namespace Webkul\SupplierInfo\Database\Seeders\Attribute;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Rules\Decimal;
use Webkul\Installer\Database\Seeders\Attribute\AttributeTableSeeder as BaseAttributeTableSeeder;

class AttributeTableSeeder extends BaseAttributeTableSeeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        $attributeIds = [29, 30, 31, 32, 33];

        // Delete related records in product_super_attributes first
        DB::table('product_super_attributes')->whereIn('attribute_id', $attributeIds)->delete();

        // Now delete from the attributes and attribute_translations tables
        DB::table('attributes')->whereIn('id', $attributeIds)->delete();
        DB::table('attribute_translations')->whereIn('attribute_id', $attributeIds)->delete();

        $now = Carbon::now();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('attributes')->insert([
            [
                'id'                  => 29,
                'code'                => 'product_code',
                'admin_name'          => trans('supplierinfo::app.supplierinfo.attribute.attributes.product_code', [], $defaultLocale),
                'type'                => 'textarea',
                'validation'          => 'decimal',
                'position'            => 9,
                'is_required'         => 1,
                'is_unique'           => 0,
                'value_per_locale'    => 0,
                'value_per_channel'   => 0,
                'default_value'       => null,
                'is_filterable'       => 0,
                'is_configurable'     => 0,
                'is_user_defined'     => 0,
                'is_visible_on_front' => 1,
                'is_comparable'       => 0,
                'enable_wysiwyg'      => 0,
                'created_at'          => $now,
                'updated_at'          => $now,
            ],[
                'id'                  => 30,
                'code'                => 'manufacturer_detail',
                'admin_name'          => trans('supplierinfo::app.supplierinfo.attribute.attributes.manufacturer_detail', [], $defaultLocale),
                'type'                => 'textarea',
                'validation'          => null,
                'position'            => 10,
                'is_required'         => 1,
                'is_unique'           => 0,
                'value_per_locale'    => 0,
                'value_per_channel'   => 0,
                'default_value'       => null,
                'is_filterable'       => 0,
                'is_configurable'     => 0,
                'is_user_defined'     => 0,
                'is_visible_on_front' => 1,
                'is_comparable'       => 0,
                'enable_wysiwyg'      => 0,
                'created_at'          => $now,
                'updated_at'          => $now,
            ],[
                'id'                  => 31,
                'code'                => 'packer_detail',
                'admin_name'          => trans('supplierinfo::app.supplierinfo.attribute.attributes.packer_detail', [], $defaultLocale),
                'type'                => 'textarea',
                'validation'          => null,
                'position'            => 11,
                'is_required'         => 1,
                'is_unique'           => 0,
                'value_per_locale'    => 0,
                'value_per_channel'   => 0,
                'default_value'       => null,
                'is_filterable'       => 0,
                'is_configurable'     => 0,
                'is_user_defined'     => 0,
                'is_visible_on_front' => 1,
                'is_comparable'       => 0,
                'enable_wysiwyg'      => 0,
                'created_at'          => $now,
                'updated_at'          => $now,
            ],[
                'id'                  => 32,
                'code'                => 'importer_detail',
                'admin_name'          => trans('supplierinfo::app.supplierinfo.attribute.attributes.importer_detail', [], $defaultLocale),
                'type'                => 'textarea',
                'validation'          => null,
                'position'            => 12,
                'is_required'         => 0,
                'is_unique'           => 0,
                'value_per_locale'    => 0,
                'value_per_channel'   => 0,
                'default_value'       => null,
                'is_filterable'       => 0,
                'is_configurable'     => 0,
                'is_user_defined'     => 0,
                'is_visible_on_front' => 1,
                'is_comparable'       => 0,
                'enable_wysiwyg'      => 0,
                'created_at'          => $now,
                'updated_at'          => $now,
            ],[
                'id'                  => 33,
                'code'                => 'country_of_origin',
                'admin_name'          => trans('supplierinfo::app.supplierinfo.attribute.attributes.country_of_origin', [], $defaultLocale),
                'type'                => 'select',
                'validation'          => null,
                'position'            => 13,
                'is_required'         => 1,
                'is_unique'           => 0,
                'value_per_locale'    => 0,
                'value_per_channel'   => 0,
                'default_value'       => null,
                'is_filterable'       => 0,
                'is_configurable'     => 1,
                'is_user_defined'     => 0,
                'is_visible_on_front' => 1,
                'is_comparable'       => 0,
                'enable_wysiwyg'      => 0,
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
        ]);

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        foreach ($locales as $locale) {
            DB::table('attribute_translations')->insert([
                [
                    'locale'       => $locale,
                    'name'         => trans('supplierinfo::app.supplierinfo.attribute.attributes.product_code', [], $locale),
                    'attribute_id' => 29,
                ],[
                    'locale'       => $locale,
                    'name'         => trans('supplierinfo::app.supplierinfo.attribute.attributes.manufacturer_detail', [], $locale),
                    'attribute_id' => 30,
                ],[
                    'locale'       => $locale,
                    'name'         => trans('supplierinfo::app.supplierinfo.attribute.attributes.packer_detail', [], $locale),
                    'attribute_id' => 31,
                ],[
                    'locale'       => $locale,
                    'name'         => trans('supplierinfo::app.supplierinfo.attribute.attributes.importer_detail', [], $locale),
                    'attribute_id' => 32,
                ],[
                    'locale'       => $locale,
                    'name'         => trans('supplierinfo::app.supplierinfo.attribute.attributes.country_of_origin', [], $locale),
                    'attribute_id' => 33,
                ],
            ]);
        }
    }
}
