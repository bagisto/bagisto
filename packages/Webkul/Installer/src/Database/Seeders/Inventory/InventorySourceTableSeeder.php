<?php

namespace Webkul\Installer\Database\Seeders\Inventory;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySourceTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('inventory_sources')->delete();

        DB::table('inventory_sources')->insert([
            'id'             => 1,
            'code'           => 'default',
            'name'           => trans('installer::app.seeders.inventory.inventory-sources.name'),
            'contact_name'   => trans('installer::app.seeders.inventory.inventory-sources.name'),
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
