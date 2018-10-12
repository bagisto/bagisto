<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CurrencyTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('currencies')->delete();

        DB::table('currencies')->insert([
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
        ]);
    }
}