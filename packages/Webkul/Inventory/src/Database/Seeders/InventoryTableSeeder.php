<?php

namespace Webkul\Inventory\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class InventoryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('inventory_sources')->delete();

        DB::table('inventory_sources')->insert([
            'id'             => 1,
            'code'           => 'default',
            'name'           => 'Default',
            'contact_name'   => 'Detroit Warehouse',
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