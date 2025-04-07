<?php

namespace Webkul\SupplierInfo\Database\Seeders\Attribute;

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
        $this->call(AttributeTableSeeder::class, false, ['parameters' => $parameters]);
        $this->call(AttributeOptionTableSeeder::class, false, ['parameters' => $parameters]);
        $this->call(AttributeGroupTableSeeder::class, false, ['parameters' => $parameters]);
    }
}
