<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Installer\Database\Seeders\DatabaseSeeder as BagistoDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BagistoDatabaseSeeder::class, false, [
            'parameters' => [
                'default_locale'      => 'en',
                'allowed_locales'     => ['en', 'ar'],
                'default_currency'    => 'USD',
                'allowed_currencies'  => ['USD', 'AED'],
            ],
        ]);
    }
}
