<?php

namespace Webkul\Installer\Database\Seeders\Category;

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
        $this->call(CategoryTableSeeder::class, false, ['parameters' => $parameters]);
    }
}
