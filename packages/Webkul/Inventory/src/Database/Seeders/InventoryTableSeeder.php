<?php

namespace Webkul\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class InventoryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('inventory_sources')->delete();

        DB::table('inventory_sources')->insert([
            'id' => 1,
            'code' => 'default',
            'name' => 'Default',
            'status' => 1,
        ]);
    }
}