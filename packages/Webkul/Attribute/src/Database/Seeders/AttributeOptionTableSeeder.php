<?php

namespace Webkul\Attribute\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeOptionTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('attribute_options')->delete();
        DB::table('attribute_option_translations')->delete();

        DB::table('attribute_options')->insert([
            ['id' => '1', 'admin_name' => 'Red', 'sort_order' => '1', 'attribute_id' => '23'],
            ['id' => '2', 'admin_name' => 'Green', 'sort_order' => '2', 'attribute_id' => '23'],
            ['id' => '3', 'admin_name' => 'Yellow', 'sort_order' => '3', 'attribute_id' => '23'],
            ['id' => '4', 'admin_name' => 'Black', 'sort_order' => '4', 'attribute_id' => '23'],
            ['id' => '5', 'admin_name' => 'White', 'sort_order' => '5', 'attribute_id' => '23'],
            ['id' => '6', 'admin_name' => 'S', 'sort_order' => '1', 'attribute_id' => '24'],
            ['id' => '7', 'admin_name' => 'M', 'sort_order' => '2', 'attribute_id' => '24'],
            ['id' => '8', 'admin_name' => 'L', 'sort_order' => '3', 'attribute_id' => '24'],
            ['id' => '9', 'admin_name' => 'XL', 'sort_order' => '4', 'attribute_id' => '24']
        ]);

        DB::table('attribute_option_translations')->insert([
            ['id' => '1', 'locale' => 'en', 'label' => 'Red', 'attribute_option_id' => '1'],
            ['id' => '2', 'locale' => 'en', 'label' => 'Green', 'attribute_option_id' => '2'],
            ['id' => '3', 'locale' => 'en', 'label' => 'Yellow', 'attribute_option_id' => '3'],
            ['id' => '4', 'locale' => 'en', 'label' => 'Black', 'attribute_option_id' => '4'],
            ['id' => '5', 'locale' => 'en', 'label' => 'White', 'attribute_option_id' => '5'],
            ['id' => '6', 'locale' => 'en', 'label' => 'S', 'attribute_option_id' => '6'],
            ['id' => '7', 'locale' => 'en', 'label' => 'M', 'attribute_option_id' => '7'],
            ['id' => '8', 'locale' => 'en', 'label' => 'L', 'attribute_option_id' => '8'],
            ['id' => '9', 'locale' => 'en', 'label' => 'XL', 'attribute_option_id' => '9']
        ]);
    }
}