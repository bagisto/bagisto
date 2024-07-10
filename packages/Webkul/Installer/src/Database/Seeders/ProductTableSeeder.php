<?php

namespace Webkul\Installer\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\Installer\Database\Seeders\Category\CategoryTableSeeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Attribute Type Fields.
     *
     * @var array
     */
    public $attributeTypeFields = [
        'text'        => 'text_value',
        'textarea'    => 'text_value',
        'price'       => 'float_value',
        'boolean'     => 'boolean_value',
        'select'      => 'integer_value',
        'multiselect' => 'text_value',
        'datetime'    => 'datetime_value',
        'date'        => 'date_value',
        'file'        => 'text_value',
        'image'       => 'text_value',
        'checkbox'    => 'text_value',
    ];

    /**
     * Base path for the images.
     */
    const BASE_PATH = 'packages/Webkul/Installer/src/Resources/assets/images/seeders/products/';

    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('products')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        $now = Carbon::now();

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        $localeProductsData = $this->prepareProductsData($locales);

        $products = Arr::map($localeProductsData[$defaultLocale], function ($row) {
            return Arr::only($row, ['parent_id', 'sku', 'type', 'attribute_family_id', 'created_at', 'updated_at']);
        });

        // Category seeder.
        $seeder = new CategoryTableSeeder();

        $seeder->sampleCategories($parameters);

        DB::table('products')->insert($products);

        $createdProducts = DB::table('products')->get();

        $attributes = DB::table('attributes')->get();

        $productsAttributeValues = [];

        $attributeValues = [];

        foreach ($localeProductsData as $locale => $productsData) {
            $productsFlatData = Arr::map($localeProductsData[$locale], function ($row) {
                return Arr::except($row, ['color', 'size', 'parent_id']);
            });

            DB::table('product_flat')->insert($productsFlatData);

            $skipAttributes = ['product_id', 'parent_id', 'type', 'attribute_family_id', 'locale', 'channel', 'created_at', 'updated_at'];

            $localeSpecificAttributes = ['name', 'url_key', 'short_description', 'description', 'meta_title', 'meta_keywords', 'meta_description'];

            foreach ($productsData as $productData) {
                foreach ($productData as $attributeCode => $value) {
                    if (in_array($attributeCode, $skipAttributes)) {
                        continue;
                    }

                    if ($locale !== 'en' && ! in_array($attributeCode, $localeSpecificAttributes)) {
                        continue;
                    }

                    $attribute = $attributes->where('code', $attributeCode)->first();

                    $uniqueId = implode('|', array_filter([
                        $attribute->value_per_channel ? 'default' : null,
                        $attribute->value_per_locale ? $locale : null,
                        $productData['product_id'],
                        $attribute->id,
                    ]));

                    if (array_key_exists($uniqueId, $productsAttributeValues)) {
                        continue;
                    }

                    $attributeTypeValues = array_fill_keys(array_values($this->attributeTypeFields), null);

                    $attributeValues[] = array_merge($attributeTypeValues, [
                        'attribute_id'                               => $attribute->id,
                        'product_id'                                 => $productData['product_id'],
                        $this->attributeTypeFields[$attribute->type] => $value,
                        'channel'                                    => $attribute->value_per_channel ? 'default' : null,
                        'locale'                                     => $attribute->value_per_locale ? $locale : null,
                        'unique_id'                                  => $uniqueId,
                        'json_value'                                 => null,
                    ]);

                }
            }
        }

        DB::table('product_attribute_values')->insert($attributeValues);

        foreach ($createdProducts as $product) {
            $product = (array) $product;

            DB::table('product_channels')->insert([
                'product_id' => $product['id'],
                'channel_id' => 1,
            ]);
        }

        DB::table('product_grouped_products')->insert([
            [
                'id'                    => 1,
                'product_id'            => 5,
                'associated_product_id' => 1,
                'qty'                   => 5,
                'sort_order'            => 1,
            ], [
                'id'                    => 2,
                'product_id'            => 5,
                'associated_product_id' => 3,
                'qty'                   => 5,
                'sort_order'            => 2,
            ], [
                'id'                    => 3,
                'product_id'            => 5,
                'associated_product_id' => 4,
                'qty'                   => 5,
                'sort_order'            => 3,
            ],
        ]);

        DB::table('product_bundle_options')->insert([
            [
                'id'          => 1,
                'product_id'  => 6,
                'type'        => 'radio',
                'is_required' => 1,
                'sort_order'  => 0,
            ], [
                'id'          => 2,
                'product_id'  => 6,
                'type'        => 'radio',
                'is_required' => 1,
                'sort_order'  => 1,
            ], [
                'id'          => 3,
                'product_id'  => 6,
                'type'        => 'checkbox',
                'is_required' => 1,
                'sort_order'  => 2,
            ], [
                'id'          => 4,
                'product_id'  => 6,
                'type'        => 'checkbox',
                'is_required' => 1,
                'sort_order'  => 3,
            ],
        ]);

        DB::table('product_bundle_option_products')->insert([
            [
                'id'                       => 1,
                'product_id'               => 1,
                'product_bundle_option_id' => 1,
                'qty'                      => 1,
                'is_user_defined'          => 1,
                'is_default'               => 0,
                'sort_order'               => 0,
            ], [
                'id'                       => 2,
                'product_id'               => 2,
                'product_bundle_option_id' => 2,
                'qty'                      => 2,
                'is_user_defined'          => 1,
                'is_default'               => 1,
                'sort_order'               => 1,
            ], [
                'id'                       => 3,
                'product_id'               => 3,
                'product_bundle_option_id' => 3,
                'qty'                      => 1,
                'is_user_defined'          => 1,
                'is_default'               => 1,
                'sort_order'               => 2,
            ], [
                'id'                       => 4,
                'product_id'               => 4,
                'product_bundle_option_id' => 4,
                'qty'                      => 2,
                'is_user_defined'          => 1,
                'is_default'               => 0,
                'sort_order'               => 3,
            ],
        ]);

        foreach ($locales as $locale) {
            DB::table('product_bundle_option_translations')->insert([
                [
                    'locale'                   => $locale,
                    'label'                    => trans('installer::app.seeders.sample-products.product-bundle-option-translations.1.label', [], $locale),
                    'product_bundle_option_id' => 1,
                ], [
                    'locale'                   => $locale,
                    'label'                    => trans('installer::app.seeders.sample-products.product-bundle-option-translations.2.label', [], $locale),
                    'product_bundle_option_id' => 2,
                ], [
                    'locale'                   => $locale,
                    'label'                    => trans('installer::app.seeders.sample-products.product-bundle-option-translations.3.label', [], $locale),
                    'product_bundle_option_id' => 3,
                ], [
                    'locale'                   => $locale,
                    'label'                    => trans('installer::app.seeders.sample-products.product-bundle-option-translations.4.label', [], $locale),
                    'product_bundle_option_id' => 4,
                ],
            ]);
        }

        DB::table('product_super_attributes')->insert([
            [
                'product_id'   => 7,
                'attribute_id' => 23,
            ], [
                'product_id'   => 7,
                'attribute_id' => 24,
            ],
        ]);

        DB::table('product_price_indices')->insert([
            [
                'id'                   => 1,
                'product_id'           => 1,
                'customer_group_id'    => 1,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 14,
                'regular_max_price'    => 14,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 2,
                'product_id'           => 1,
                'customer_group_id'    => 2,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 14,
                'regular_max_price'    => 14,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 3,
                'product_id'           => 1,
                'customer_group_id'    => 3,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 14,
                'regular_max_price'    => 14,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 4,
                'product_id'           => 2,
                'customer_group_id'    => 1,
                'channel_id'           => 1,
                'min_price'            => 17,
                'regular_min_price'    => 17,
                'max_price'            => 17,
                'regular_max_price'    => 17,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 5,
                'product_id'           => 2,
                'customer_group_id'    => 2,
                'channel_id'           => 1,
                'min_price'            => 17,
                'regular_min_price'    => 17,
                'max_price'            => 17,
                'regular_max_price'    => 17,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 6,
                'product_id'           => 2,
                'customer_group_id'    => 3,
                'channel_id'           => 1,
                'min_price'            => 17,
                'regular_min_price'    => 17,
                'max_price'            => 17,
                'regular_max_price'    => 17,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 7,
                'product_id'           => 3,
                'customer_group_id'    => 1,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 8,
                'product_id'           => 3,
                'customer_group_id'    => 2,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 9,
                'product_id'           => 3,
                'customer_group_id'    => 3,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 10,
                'product_id'           => 4,
                'customer_group_id'    => 1,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 11,
                'product_id'           => 4,
                'customer_group_id'    => 2,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 12,
                'product_id'           => 4,
                'customer_group_id'    => 3,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 13,
                'product_id'           => 5,
                'customer_group_id'    => 1,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 14,
                'product_id'           => 5,
                'customer_group_id'    => 2,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 15,
                'product_id'           => 5,
                'customer_group_id'    => 3,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 16,
                'product_id'           => 6,
                'customer_group_id'    => 1,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 17,
                'product_id'           => 6,
                'customer_group_id'    => 2,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 18,
                'product_id'           => 6,
                'customer_group_id'    => 3,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 19,
                'product_id'           => 8,
                'customer_group_id'    => 1,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 14,
                'regular_max_price'    => 14,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 20,
                'product_id'           => 8,
                'customer_group_id'    => 2,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 14,
                'regular_max_price'    => 14,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 21,
                'product_id'           => 8,
                'customer_group_id'    => 3,
                'channel_id'           => 1,
                'min_price'            => 14,
                'regular_min_price'    => 14,
                'max_price'            => 14,
                'regular_max_price'    => 14,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 22,
                'product_id'           => 9,
                'customer_group_id'    => 1,
                'channel_id'           => 1,
                'min_price'            => 17,
                'regular_min_price'    => 17,
                'max_price'            => 17,
                'regular_max_price'    => 17,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 23,
                'product_id'           => 9,
                'customer_group_id'    => 2,
                'channel_id'           => 1,
                'min_price'            => 17,
                'regular_min_price'    => 17,
                'max_price'            => 17,
                'regular_max_price'    => 17,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 24,
                'product_id'           => 9,
                'customer_group_id'    => 3,
                'channel_id'           => 1,
                'min_price'            => 17,
                'regular_min_price'    => 17,
                'max_price'            => 17,
                'regular_max_price'    => 17,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 25,
                'product_id'           => 10,
                'customer_group_id'    => 1,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 26,
                'product_id'           => 10,
                'customer_group_id'    => 2,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 27,
                'product_id'           => 10,
                'customer_group_id'    => 3,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 28,
                'product_id'           => 11,
                'customer_group_id'    => 1,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 29,
                'product_id'           => 11,
                'customer_group_id'    => 2,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 30,
                'product_id'           => 11,
                'customer_group_id'    => 3,
                'channel_id'           => 1,
                'min_price'            => 21,
                'regular_min_price'    => 21,
                'max_price'            => 21,
                'regular_max_price'    => 21,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 31,
                'product_id'           => 7,
                'customer_group_id'    => 1,
                'channel_id'           => 1,
                'min_price'            => 17,
                'regular_min_price'    => 17,
                'max_price'            => 17,
                'regular_max_price'    => 17,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 32,
                'product_id'           => 7,
                'customer_group_id'    => 2,
                'channel_id'           => 1,
                'min_price'            => 17,
                'regular_min_price'    => 17,
                'max_price'            => 17,
                'regular_max_price'    => 17,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 33,
                'product_id'           => 7,
                'customer_group_id'    => 3,
                'channel_id'           => 1,
                'min_price'            => 17,
                'regular_min_price'    => 17,
                'max_price'            => 17,
                'regular_max_price'    => 17,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
        ]);

        DB::table('product_customer_group_prices')->insert([
            [
                'id'                => 1,
                'qty'               => 2,
                'value_type'        => 'fixed',
                'value'             => 12,
                'product_id'        => 1,
                'customer_group_id' => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
                'unique_id'         => '002|1|1',
            ], [
                'id'                => 2,
                'qty'               => 2,
                'value_type'        => 'fixed',
                'value'             => 12,
                'product_id'        => 1,
                'customer_group_id' => 2,
                'created_at'        => $now,
                'updated_at'        => $now,
                'unique_id'         => '002|1|2',
            ], [
                'id'                => 3,
                'qty'               => 2,
                'value_type'        => 'fixed',
                'value'             => 12,
                'product_id'        => 1,
                'customer_group_id' => 3,
                'created_at'        => $now,
                'updated_at'        => $now,
                'unique_id'         => '002|1|3',
            ], [
                'id'                => 4,
                'qty'               => 3,
                'value_type'        => 'fixed',
                'value'             => 50,
                'product_id'        => 1,
                'customer_group_id' => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
                'unique_id'         => '003|1|1',
            ], [
                'id'                => 5,
                'qty'               => 3,
                'value_type'        => 'fixed',
                'value'             => 50,
                'product_id'        => 1,
                'customer_group_id' => 2,
                'created_at'        => $now,
                'updated_at'        => $now,
                'unique_id'         => '003|1|2',
            ], [
                'id'                => 6,
                'qty'               => 3,
                'value_type'        => 'fixed',
                'value'             => 50,
                'product_id'        => 1,
                'customer_group_id' => 3,
                'created_at'        => $now,
                'updated_at'        => $now,
                'unique_id'         => '003|1|3',
            ],
        ]);

        // Product Categories
        DB::table('product_inventories')->insert([
            [
                'id'                   => 1,
                'product_id'           => 1,
                'vendor_id'            => 0,
                'inventory_source_id'  => 1,
                'qty'                  => 100,
            ], [
                'id'                   => 2,
                'product_id'           => 2,
                'vendor_id'            => 0,
                'inventory_source_id'  => 1,
                'qty'                  => 100,
            ], [
                'id'                   => 3,
                'product_id'           => 3,
                'vendor_id'            => 0,
                'inventory_source_id'  => 1,
                'qty'                  => 100,
            ], [
                'id'                   => 4,
                'product_id'           => 4,
                'vendor_id'            => 0,
                'inventory_source_id'  => 1,
                'qty'                  => 100,
            ], [
                'id'                   => 5,
                'product_id'           => 8,
                'vendor_id'            => 0,
                'inventory_source_id'  => 1,
                'qty'                  => 100,
            ], [
                'id'                   => 6,
                'product_id'           => 9,
                'vendor_id'            => 0,
                'inventory_source_id'  => 1,
                'qty'                  => 100,
            ], [
                'id'                   => 7,
                'product_id'           => 10,
                'vendor_id'            => 0,
                'inventory_source_id'  => 1,
                'qty'                  => 100,
            ], [
                'id'                   => 8,
                'product_id'           => 11,
                'vendor_id'            => 0,
                'inventory_source_id'  => 1,
                'qty'                  => 100,
            ],
        ]);

        DB::table('product_inventory_indices')->insert([
            [
                'id'                   => 1,
                'qty'                  => 100,
                'product_id'           => 1,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 2,
                'qty'                  => 100,
                'product_id'           => 2,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 3,
                'qty'                  => 100,
                'product_id'           => 3,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 4,
                'qty'                  => 100,
                'product_id'           => 4,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 5,
                'qty'                  => 100,
                'product_id'           => 8,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 6,
                'qty'                  => 100,
                'product_id'           => 9,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 7,
                'qty'                  => 100,
                'product_id'           => 10,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ], [
                'id'                   => 8,
                'qty'                  => 100,
                'product_id'           => 11,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],  [
                'id'                   => 9,
                'qty'                  => 0,
                'product_id'           => 7,
                'channel_id'           => 1,
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
        ]);

        // Product Categories
        DB::table('product_categories')->insert([
            [
                'product_id'  => 1,
                'category_id' => 3,
            ], [
                'product_id'  => 2,
                'category_id' => 3,
            ], [
                'product_id'  => 3,
                'category_id' => 3,
            ], [
                'product_id'  => 4,
                'category_id' => 3,
            ], [
                'product_id'  => 5,
                'category_id' => 3,
            ], [
                'product_id'  => 6,
                'category_id' => 3,
            ], [
                'product_id'  => 7,
                'category_id' => 3,
            ], [
                'product_id'  => 8,
                'category_id' => 3,
            ], [
                'product_id'  => 9,
                'category_id' => 3,
            ],
        ]);

        DB::table('product_images')->insert([
            [
                'id'         => 1,
                'type'       => 'image',
                'path'       => $this->productImages('product/1', '1.webp'),
                'product_id' => 1,
                'position'   => 1,
            ], [
                'id'         => 2,
                'type'       => 'image',
                'path'       => $this->productImages('product/2', '2.webp'),
                'product_id' => 2,
                'position'   => 1,
            ], [
                'id'         => 3,
                'type'       => 'image',
                'path'       => $this->productImages('product/3', '3.webp'),
                'product_id' => 3,
                'position'   => 1,
            ], [
                'id'         => 4,
                'type'       => 'image',
                'path'       => $this->productImages('product/4', '4.webp'),
                'product_id' => 4,
                'position'   => 1,
            ], [
                'id'         => 5,
                'type'       => 'image',
                'path'       => $this->productImages('product/5', '5.webp'),
                'product_id' => 5,
                'position'   => 1,
            ], [
                'id'         => 6,
                'type'       => 'image',
                'path'       => $this->productImages('product/6', '5.webp'),
                'product_id' => 6,
                'position'   => 1,
            ], [
                'id'         => 7,
                'type'       => 'image',
                'path'       => $this->productImages('product/7', '6.webp'),
                'product_id' => 7,
                'position'   => 1,
            ], [
                'id'         => 8,
                'type'       => 'image',
                'path'       => $this->productImages('product/7', '7.webp'),
                'product_id' => 7,
                'position'   => 2,
            ], [
                'id'         => 9,
                'type'       => 'image',
                'path'       => $this->productImages('product/7', '8.webp'),
                'product_id' => 7,
                'position'   => 3,
            ], [
                'id'         => 10,
                'type'       => 'image',
                'path'       => $this->productImages('product/8', '9.webp'),
                'product_id' => 8,
                'position'   => 1,
            ], [
                'id'         => 11,
                'type'       => 'image',
                'path'       => $this->productImages('product/8', '10.webp'),
                'product_id' => 8,
                'position'   => 2,
            ], [
                'id'         => 12,
                'type'       => 'image',
                'path'       => $this->productImages('product/9', '9.webp'),
                'product_id' => 9,
                'position'   => 1,
            ], [
                'id'         => 13,
                'type'       => 'image',
                'path'       => $this->productImages('product/9', '10.webp'),
                'product_id' => 9,
                'position'   => 2,
            ], [
                'id'         => 14,
                'type'       => 'image',
                'path'       => $this->productImages('product/10', '11.webp'),
                'product_id' => 10,
                'position'   => 1,
            ], [
                'id'         => 15,
                'type'       => 'image',
                'path'       => $this->productImages('product/10', '12.webp'),
                'product_id' => 10,
                'position'   => 2,
            ], [
                'id'         => 16,
                'type'       => 'image',
                'path'       => $this->productImages('product/11', '11.webp'),
                'product_id' => 11,
                'position'   => 1,
            ], [
                'id'         => 17,
                'type'       => 'image',
                'path'       => $this->productImages('product/11', '12.webp'),
                'product_id' => 11,
                'position'   => 2,
            ],
        ]);

        DB::table('product_up_sells')->insert([
            [
                'parent_id'  => 4,
                'child_id'   => 1,
            ], [
                'parent_id'  => 1,
                'child_id'   => 2,
            ], [
                'parent_id'  => 1,
                'child_id'   => 3,
            ], [
                'parent_id'  => 2,
                'child_id'   => 3,
            ], [
                'parent_id'  => 1,
                'child_id'   => 4,
            ], [
                'parent_id'  => 2,
                'child_id'   => 4,
            ],
        ]);

        DB::table('product_cross_sells')->insert([
            [
                'parent_id'  => 4,
                'child_id'   => 1,
            ], [
                'parent_id'  => 1,
                'child_id'   => 2,
            ], [
                'parent_id'  => 1,
                'child_id'   => 3,
            ], [
                'parent_id'  => 2,
                'child_id'   => 3,
            ], [
                'parent_id'  => 1,
                'child_id'   => 4,
            ], [
                'parent_id'  => 2,
                'child_id'   => 4,
            ],
        ]);

        DB::table('product_relations')->insert([
            [
                'parent_id' => 1,
                'child_id'  => 4,
            ], [
                'parent_id' => 2,
                'child_id'  => 1,
            ], [
                'parent_id' => 3,
                'child_id'  => 1,
            ], [
                'parent_id' => 3,
                'child_id'  => 2,
            ], [
                'parent_id' => 4,
                'child_id'  => 1,
            ], [
                'parent_id' => 4,
                'child_id'  => 2,
            ],
        ]);
    }

    /**
     * Retrieve all product data in array format.
     *
     * @return array
     */
    public function prepareProductsData($locales)
    {
        $products = [];

        $currentDate = Carbon::now();

        $now = $currentDate->format('Y-m-d H:i:s');

        foreach ($locales as $locale) {
            $products[$locale] = [
                [
                    'sku'                  => 'SP-001',
                    'type'                 => 'simple',
                    'product_number'       => null,
                    'name'                 => trans('installer::app.seeders.sample-products.product-flat.1.name', [], $locale),
                    'short_description'    => trans('installer::app.seeders.sample-products.product-flat.1.short-description', [], $locale),
                    'description'          => trans('installer::app.seeders.sample-products.product-flat.1.description', [], $locale),
                    'url_key'              => 'arctic-cozy-knit-unisex-beanie',
                    'new'                  => 1,
                    'featured'             => 1,
                    'status'               => 1,
                    'meta_title'           => trans('installer::app.seeders.sample-products.product-flat.1.meta-title', [], $locale),
                    'meta_keywords'        => trans('installer::app.seeders.sample-products.product-flat.1.meta-keywords', [], $locale),
                    'meta_description'     => trans('installer::app.seeders.sample-products.product-flat.1.meta-description', [], $locale),
                    'price'                => 14,
                    'special_price'        => null,
                    'special_price_from'   => null,
                    'special_price_to'     => null,
                    'weight'               => 1.23,
                    'created_at'           => $now,
                    'locale'               => $locale,
                    'channel'              => 'default',
                    'attribute_family_id'  => 1,
                    'product_id'           => 1,
                    'updated_at'           => $now,
                    'parent_id'            => null,
                    'visible_individually' => 1,
                ], [
                    'sku'                  => 'SP-002',
                    'type'                 => 'simple',
                    'product_number'       => null,
                    'name'                 => trans('installer::app.seeders.sample-products.product-flat.2.name', [], $locale),
                    'short_description'    => trans('installer::app.seeders.sample-products.product-flat.2.short-description', [], $locale),
                    'description'          => trans('installer::app.seeders.sample-products.product-flat.2.description', [], $locale),
                    'url_key'              => 'arctic-bliss-stylish-winter-scarf',
                    'new'                  => 1,
                    'featured'             => 1,
                    'status'               => 1,
                    'meta_title'           => trans('installer::app.seeders.sample-products.product-flat.2.meta-title', [], $locale),
                    'meta_keywords'        => trans('installer::app.seeders.sample-products.product-flat.2.meta-keywords', [], $locale),
                    'meta_description'     => trans('installer::app.seeders.sample-products.product-flat.2.meta-description', [], $locale),
                    'price'                => 17,
                    'special_price'        => null,
                    'special_price_from'   => null,
                    'special_price_to'     => null,
                    'weight'               => 1.23,
                    'created_at'           => $now,
                    'locale'               => $locale,
                    'channel'              => 'default',
                    'attribute_family_id'  => 1,
                    'product_id'           => 2,
                    'updated_at'           => $now,
                    'parent_id'            => null,
                    'visible_individually' => 1,
                ], [
                    'sku'                  => 'SP-003',
                    'type'                 => 'simple',
                    'product_number'       => null,
                    'name'                 => trans('installer::app.seeders.sample-products.product-flat.3.name', [], $locale),
                    'short_description'    => trans('installer::app.seeders.sample-products.product-flat.3.short-description', [], $locale),
                    'description'          => trans('installer::app.seeders.sample-products.product-flat.3.description', [], $locale),
                    'url_key'              => 'arctic-touchscreen-winter-gloves',
                    'new'                  => 1,
                    'featured'             => 1,
                    'status'               => 1,
                    'meta_title'           => trans('installer::app.seeders.sample-products.product-flat.3.meta-title', [], $locale),
                    'meta_keywords'        => trans('installer::app.seeders.sample-products.product-flat.3.meta-keywords', [], $locale),
                    'meta_description'     => trans('installer::app.seeders.sample-products.product-flat.3.meta-description', [], $locale),
                    'price'                => 21,
                    'special_price'        => 17,
                    'special_price_from'   => $now,
                    'special_price_to'     => Carbon::tomorrow(),
                    'weight'               => 1,
                    'created_at'           => $now,
                    'locale'               => $locale,
                    'channel'              => 'default',
                    'attribute_family_id'  => 1,
                    'product_id'           => 3,
                    'updated_at'           => $now,
                    'parent_id'            => null,
                    'visible_individually' => 1,
                ], [
                    'sku'                  => 'SP-004',
                    'type'                 => 'simple',
                    'product_number'       => null,
                    'name'                 => trans('installer::app.seeders.sample-products.product-flat.4.name', [], $locale),
                    'short_description'    => trans('installer::app.seeders.sample-products.product-flat.4.short-description', [], $locale),
                    'description'          => trans('installer::app.seeders.sample-products.product-flat.4.description', [], $locale),
                    'url_key'              => 'arctic-warmth-wool-blend-socks',
                    'new'                  => 0,
                    'featured'             => 0,
                    'status'               => 1,
                    'meta_title'           => trans('installer::app.seeders.sample-products.product-flat.4.meta-title', [], $locale),
                    'meta_keywords'        => trans('installer::app.seeders.sample-products.product-flat.4.meta-keywords', [], $locale),
                    'meta_description'     => trans('installer::app.seeders.sample-products.product-flat.4.meta-description', [], $locale),
                    'price'                => 21,
                    'special_price'        => null,
                    'special_price_from'   => null,
                    'special_price_to'     => null,
                    'weight'               => 1,
                    'created_at'           => $now,
                    'locale'               => $locale,
                    'channel'              => 'default',
                    'attribute_family_id'  => 1,
                    'product_id'           => 4,
                    'updated_at'           => $now,
                    'parent_id'            => null,
                    'visible_individually' => 1,
                ], [
                    'sku'                  => 'GP-001',
                    'type'                 => 'grouped',
                    'product_number'       => null,
                    'name'                 => trans('installer::app.seeders.sample-products.product-flat.5.name', [], $locale),
                    'short_description'    => trans('installer::app.seeders.sample-products.product-flat.5.short-description', [], $locale),
                    'description'          => trans('installer::app.seeders.sample-products.product-flat.5.description', [], $locale),
                    'url_key'              => 'arctic-frost-winter-accessories',
                    'new'                  => 0,
                    'featured'             => 0,
                    'status'               => 1,
                    'meta_title'           => trans('installer::app.seeders.sample-products.product-flat.5.meta-title', [], $locale),
                    'meta_keywords'        => trans('installer::app.seeders.sample-products.product-flat.5.meta-keywords', [], $locale),
                    'meta_description'     => trans('installer::app.seeders.sample-products.product-flat.5.meta-description', [], $locale),
                    'price'                => null,
                    'special_price'        => null,
                    'special_price_from'   => null,
                    'special_price_to'     => null,
                    'weight'               => 1,
                    'created_at'           => $now,
                    'locale'               => $locale,
                    'channel'              => 'default',
                    'attribute_family_id'  => 1,
                    'product_id'           => 5,
                    'updated_at'           => $now,
                    'parent_id'            => null,
                    'visible_individually' => 1,
                ], [
                    'sku'                  => 'BP-001',
                    'type'                 => 'bundle',
                    'product_number'       => null,
                    'name'                 => trans('installer::app.seeders.sample-products.product-flat.6.name', [], $locale),
                    'short_description'    => trans('installer::app.seeders.sample-products.product-flat.6.short-description', [], $locale),
                    'description'          => trans('installer::app.seeders.sample-products.product-flat.6.description', [], $locale),
                    'url_key'              => 'arctic-frost-winter-accessories-bundle',
                    'new'                  => 0,
                    'featured'             => 0,
                    'status'               => 1,
                    'meta_title'           => trans('installer::app.seeders.sample-products.product-flat.6.meta-title', [], $locale),
                    'meta_keywords'        => trans('installer::app.seeders.sample-products.product-flat.6.meta-keywords', [], $locale),
                    'meta_description'     => trans('installer::app.seeders.sample-products.product-flat.6.meta-description', [], $locale),
                    'price'                => null,
                    'special_price'        => null,
                    'special_price_from'   => null,
                    'special_price_to'     => null,
                    'weight'               => 1,
                    'created_at'           => $now,
                    'locale'               => $locale,
                    'channel'              => 'default',
                    'attribute_family_id'  => 1,
                    'product_id'           => 6,
                    'updated_at'           => $now,
                    'parent_id'            => null,
                    'visible_individually' => 1,
                ], [
                    'sku'                  => 'CP-001',
                    'type'                 => 'configurable',
                    'product_number'       => '',
                    'name'                 => trans('installer::app.seeders.sample-products.product-flat.7.name', [], $locale),
                    'short_description'    => trans('installer::app.seeders.sample-products.product-flat.7.short-description', [], $locale),
                    'description'          => trans('installer::app.seeders.sample-products.product-flat.7.description', [], $locale),
                    'url_key'              => 'omniheat-mens-solid-hooded-puffer-jacket',
                    'new'                  => 0,
                    'featured'             => 0,
                    'status'               => 1,
                    'meta_title'           => trans('installer::app.seeders.sample-products.product-flat.7.meta-title', [], $locale),
                    'meta_keywords'        => trans('installer::app.seeders.sample-products.product-flat.7.meta-keywords', [], $locale),
                    'meta_description'     => trans('installer::app.seeders.sample-products.product-flat.7.meta-description', [], $locale),
                    'price'                => null,
                    'special_price'        => null,
                    'special_price_from'   => null,
                    'special_price_to'     => null,
                    'weight'               => null,
                    'created_at'           => $now,
                    'locale'               => $locale,
                    'channel'              => 'default',
                    'attribute_family_id'  => 1,
                    'product_id'           => 7,
                    'updated_at'           => $now,
                    'parent_id'            => null,
                    'visible_individually' => 1,
                ], [
                    'sku'                   => 'SP-005',
                    'type'                  => 'simple',
                    'product_number'        => null,
                    'name'                  => trans('installer::app.seeders.sample-products.product-flat.8.name', [], $locale),
                    'short_description'     => trans('installer::app.seeders.sample-products.product-flat.8.short-description', [], $locale),
                    'description'           => trans('installer::app.seeders.sample-products.product-flat.8.description', [], $locale),
                    'url_key'               => 'omniheat-mens-solid-hooded-puffer-jacket-blue-yellow-m',
                    'new'                   => 0,
                    'featured'              => 0,
                    'status'                => 1,
                    'meta_title'            => trans('installer::app.seeders.sample-products.product-flat.8.meta-title', [], $locale),
                    'meta_keywords'         => trans('installer::app.seeders.sample-products.product-flat.8.meta-keywords', [], $locale),
                    'meta_description'      => trans('installer::app.seeders.sample-products.product-flat.8.meta-description', [], $locale),
                    'price'                 => 14,
                    'special_price'         => null,
                    'special_price_from'    => null,
                    'special_price_to'      => null,
                    'weight'                => 1.23,
                    'created_at'            => $now,
                    'locale'                => $locale,
                    'channel'               => 'default',
                    'attribute_family_id'   => 1,
                    'product_id'            => 8,
                    'updated_at'            => $now,
                    'parent_id'             => 7,
                    'visible_individually'  => 1,
                    'color'                 => 3,
                    'size'                  => 2,
                ], [
                    'sku'                   => 'SP-006',
                    'type'                  => 'simple',
                    'product_number'        => null,
                    'name'                  => trans('installer::app.seeders.sample-products.product-flat.9.name', [], $locale),
                    'short_description'     => trans('installer::app.seeders.sample-products.product-flat.9.short-description', [], $locale),
                    'description'           => trans('installer::app.seeders.sample-products.product-flat.9.description', [], $locale),
                    'url_key'               => 'omniheat-mens-solid-hooded-puffer-jacket-blue-yellow-l',
                    'new'                   => 0,
                    'featured'              => 0,
                    'status'                => 1,
                    'meta_title'            => trans('installer::app.seeders.sample-products.product-flat.9.meta-title', [], $locale),
                    'meta_keywords'         => trans('installer::app.seeders.sample-products.product-flat.9.meta-keywords', [], $locale),
                    'meta_description'      => trans('installer::app.seeders.sample-products.product-flat.9.meta-description', [], $locale),
                    'price'                 => 17,
                    'special_price'         => null,
                    'special_price_from'    => null,
                    'special_price_to'      => null,
                    'weight'                => 1,
                    'created_at'            => $now,
                    'locale'                => $locale,
                    'channel'               => 'default',
                    'attribute_family_id'   => 1,
                    'product_id'            => 9,
                    'updated_at'            => $now,
                    'parent_id'             => 7,
                    'visible_individually'  => 1,
                    'color'                 => 3,
                    'size'                  => 3,
                ], [
                    'sku'                   => 'SP-007',
                    'type'                  => 'simple',
                    'product_number'        => null,
                    'name'                  => trans('installer::app.seeders.sample-products.product-flat.10.name', [], $locale),
                    'short_description'     => trans('installer::app.seeders.sample-products.product-flat.10.short-description', [], $locale),
                    'description'           => trans('installer::app.seeders.sample-products.product-flat.10.description', [], $locale),
                    'url_key'               => 'omniheat-mens-solid-hooded-puffer-jacket-blue-green-m',
                    'new'                   => 0,
                    'featured'              => 0,
                    'status'                => 1,
                    'meta_title'            => trans('installer::app.seeders.sample-products.product-flat.10.meta-title', [], $locale),
                    'meta_keywords'         => trans('installer::app.seeders.sample-products.product-flat.10.meta-keywords', [], $locale),
                    'meta_description'      => trans('installer::app.seeders.sample-products.product-flat.10.meta-description', [], $locale),
                    'price'                 => 21,
                    'special_price'         => 17,
                    'special_price_from'    => $now,
                    'special_price_to'      => $currentDate->addDays(5)->format('Y-m-d H:i:s'),
                    'weight'                => 1,
                    'created_at'            => $now,
                    'locale'                => $locale,
                    'channel'               => 'default',
                    'attribute_family_id'   => 1,
                    'product_id'            => 10,
                    'updated_at'            => $now,
                    'parent_id'             => 7,
                    'visible_individually'  => 1,
                    'color'                 => 2,
                    'size'                  => 2,
                ], [
                    'sku'                   => 'SP-008',
                    'type'                  => 'simple',
                    'product_number'        => null,
                    'name'                  => trans('installer::app.seeders.sample-products.product-flat.11.name', [], $locale),
                    'short_description'     => trans('installer::app.seeders.sample-products.product-flat.11.short-description', [], $locale),
                    'description'           => trans('installer::app.seeders.sample-products.product-flat.11.description', [], $locale),
                    'url_key'               => 'omniheat-mens-solid-hooded-puffer-jacket-blue-green-l',
                    'new'                   => 0,
                    'featured'              => 0,
                    'status'                => 1,
                    'meta_title'            => trans('installer::app.seeders.sample-products.product-flat.11.meta-title', [], $locale),
                    'meta_keywords'         => trans('installer::app.seeders.sample-products.product-flat.11.meta-keywords', [], $locale),
                    'meta_description'      => trans('installer::app.seeders.sample-products.product-flat.11.meta-description', [], $locale),
                    'price'                 => 21,
                    'special_price'         => 17,
                    'special_price_from'    => $now,
                    'special_price_to'      => $currentDate->addDays(5)->format('Y-m-d H:i:s'),
                    'weight'                => 1,
                    'created_at'            => $now,
                    'locale'                => $locale,
                    'channel'               => 'default',
                    'attribute_family_id'   => 1,
                    'product_id'            => 11,
                    'updated_at'            => $now,
                    'parent_id'             => 7,
                    'visible_individually'  => 1,
                    'color'                 => 2,
                    'size'                  => 3,
                ],
            ];
        }

        return $products;
    }

    /**
     * Store image in storage.
     *
     * @return string|null
     */
    public function productImages($targetPath, $file, $default = null)
    {
        if (file_exists(base_path(self::BASE_PATH.$file))) {
            return Storage::putFile($targetPath, new File(base_path(self::BASE_PATH.$file)));
        }

        if (! $default) {
            return;
        }

        if (file_exists(base_path(self::BASE_PATH.$default))) {
            return Storage::putFile($targetPath, new File(base_path(self::BASE_PATH.$default)));
        }
    }
}
