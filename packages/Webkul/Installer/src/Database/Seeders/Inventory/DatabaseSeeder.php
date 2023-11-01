<?php

namespace Webkul\Installer\Database\Seeders\Inventory;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(InventorySourceTableSeeder::class);
    }
}
