<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('countries')->delete();

        $countries = json_decode(file_get_contents(__DIR__ . '/../../../Data/countries.json'), true);

        DB::table('countries')->insert($countries);
    }
}
