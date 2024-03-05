<?php

namespace Webkul\Installer\Database\Seeders\Inventory;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySourceTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('inventory_sources')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('inventory_sources')->insert([
            'id'             => 1,
            'code'           => 'default',
            'name'           => trans('installer::app.seeders.inventory.inventory-sources.name', [], $defaultLocale),
            'contact_name'   => trans('installer::app.seeders.inventory.inventory-sources.name', [], $defaultLocale),
            'contact_email'  => 'warehouse@example.com',
            'contact_number' => 1234567899,
            'status'         => 1,
            'country'        => 'US',
            'state'          => 'MI',
            'street'         => '12th Street',
            'city'           => 'Detroit',
            'postcode'       => '48127',
        ]);
    }
}
