<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class LocalesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('locales')->delete();

        DB::table('locales')->insert([
                'code' => 'en',
                'name' => 'English',
            ], [
                'code' => 'fr',
                'name' => 'French',
            ]);
    }
}