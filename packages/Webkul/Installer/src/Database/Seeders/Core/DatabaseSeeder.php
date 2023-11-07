<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        $this->call(LocalesTableSeeder::class, false, ['parameters' => $parameters]);
        $this->call(CurrencyTableSeeder::class, false, ['parameters' => $parameters]);
        $this->call(CountriesTableSeeder::class, false, ['parameters' => $parameters]);
        $this->call(StatesTableSeeder::class, false, ['parameters' => $parameters]);
        $this->call(ChannelTableSeeder::class, false, ['parameters' => $parameters]);
        $this->call(ConfigTableSeeder::class, false, ['parameters' => $parameters]);
    }
}
