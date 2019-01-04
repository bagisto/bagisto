<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CountriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('countries')->delete();

        $countries = json_decode(file_get_contents(__DIR__ . '/../../Data/countries.json'), true);

        DB::table('countries')->insert($countries);
    }
}