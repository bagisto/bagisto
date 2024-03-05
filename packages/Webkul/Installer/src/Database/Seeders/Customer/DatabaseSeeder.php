<?php

namespace Webkul\Installer\Database\Seeders\Customer;

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
        $this->call(CustomerGroupTableSeeder::class, false, ['parameters' => $parameters]);
    }
}
