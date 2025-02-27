<?php

namespace Webkul\SupplierInfo\Database\Seeders\Attribute;

use Illuminate\Support\Facades\DB;
use Webkul\Installer\Database\Seeders\Attribute\AttributeOptionTableSeeder  as BaseAttributeOptionTableSeeder;

class AttributeOptionTableSeeder extends BaseAttributeOptionTableSeeder
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

        // Load the countries list from the JSON file
        $countries = json_decode(file_get_contents(base_path('packages/Webkul/SupplierInfo/src/Database/Seeders/countries.json')), true);

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        // After inserting predefined options, get the last inserted ID
        $lastId = DB::table('attribute_options')->max('id');

        // Insert each country as an attribute option with attribute_id 33
        foreach ($countries as $index => $country) {
            $attributeOptionId = $lastId + $index + 1; // Start from the next available ID

            DB::table('attribute_options')->insert([
                'id'           => $attributeOptionId,
                'admin_name'   => $country['name'],
                'sort_order'   => $attributeOptionId,
                'attribute_id' => 33,
            ]);
        }

        foreach ($locales as $locale) {
            // Add translations for countries
            foreach ($countries as $index => $country) {
                $attributeOptionId = $lastId + $index + 1; 

                DB::table('attribute_option_translations')->insert([
                    'locale'              => $locale,
                    'label'               => $country['name'], 
                    'attribute_option_id' => $attributeOptionId,
                ]);
            }
        }
    }
}
