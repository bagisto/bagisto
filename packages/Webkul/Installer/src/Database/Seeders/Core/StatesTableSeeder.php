<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('country_states')->delete();

        $states = json_decode(file_get_contents(__DIR__.'/../../../Data/states.json'), true);

        DB::table('country_states')->insert($states);
    }
}
