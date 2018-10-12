<?php

namespace Webkul\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Attribute\Database\Seeders\DatabaseSeeder as AttributeSeeder;
use Webkul\Core\Database\Seeders\DatabaseSeeder as CoreSeeder;
use Webkul\User\Database\Seeders\DatabaseSeeder as UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AttributeSeeder::class);
        $this->call(CoreSeeder::class);
        $this->call(UserSeeder::class);
    }
}
