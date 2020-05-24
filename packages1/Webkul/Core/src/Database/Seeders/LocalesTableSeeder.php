<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class LocalesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('channels')->delete();

        DB::table('locales')->delete();

        DB::table('locales')->insert([
            [
                'id'   => 1,
                'code' => 'en',
                'name' => 'English',
            ], [
                'id'   => 2,
                'code' => 'fr',
                'name' => 'French',
            ], [
                'id'   => 3,
                'code' => 'nl',
                'name' => 'Dutch',
            ]]);
    }
}