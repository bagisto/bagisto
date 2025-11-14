<?php

namespace Webkul\Installer\Database\Seeders\RMA;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run($parameters = [])
    {
        $this->call([
            DefaultReasons::class,
            RMAStatusDataSeed::class,
        ], false, ['parameters' => $parameters]);
    }
}