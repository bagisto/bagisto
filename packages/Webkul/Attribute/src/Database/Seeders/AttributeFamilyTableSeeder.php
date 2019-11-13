<?php

namespace Webkul\Attribute\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class AttributeFamilyTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('attribute_families')->delete();

        DB::table('attribute_families')->insert([
            ['id' => '1','code' => 'default','name' => 'Default','status' => '0','is_user_defined' => '1']
        ]);
    }
}