<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class StatesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('country_states')->delete();

        $states = json_decode(file_get_contents(__DIR__ . '/../../Data/states.json'), true);

        DB::table('country_states')->insert($states);
    }
}