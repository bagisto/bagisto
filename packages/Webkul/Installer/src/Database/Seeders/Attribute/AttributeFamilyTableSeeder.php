<?php

namespace Webkul\Installer\Database\Seeders\Attribute;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeFamilyTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('attribute_families')->delete();

        DB::table('attribute_families')->insert([
            [
                'id'              => 1,
                'code'            => 'default',
                'name'            => trans('installer::app.seeders.attribute.attribute-families.default'),
                'status'          => 0,
                'is_user_defined' => 1,
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
