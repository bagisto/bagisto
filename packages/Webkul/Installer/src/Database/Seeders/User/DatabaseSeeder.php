<?php

namespace Webkul\Installer\Database\Seeders\User;

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
        $this->call(RolesTableSeeder::class, false, ['parameters' => $parameters]);
        $this->call(AdminsTableSeeder::class, false, ['parameters' => $parameters]);
    }
}
