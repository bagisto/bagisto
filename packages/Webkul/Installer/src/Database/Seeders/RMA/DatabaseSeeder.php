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
        $this->call(RMAReasonSeeder::class, false, ['parameters' => $parameters]);
        $this->call(RMAStatusSeeder::class, false, ['parameters' => $parameters]);
        $this->call(RMARulesSeeder::class, false, ['parameters' => $parameters]);
    }
}
