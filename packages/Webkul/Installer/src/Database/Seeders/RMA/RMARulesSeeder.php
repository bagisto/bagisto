<?php

namespace Webkul\Installer\Database\Seeders\RMA;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RMARulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('rma_rules')->delete();

        $defaultRules = [
            [
                'name'            => 'Basic',
                'description'     => 1,
                'status'          => 1,
                'return_period'   => 10,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ];

        DB::table('rma_rules')->insert($defaultRules);
    }
}
