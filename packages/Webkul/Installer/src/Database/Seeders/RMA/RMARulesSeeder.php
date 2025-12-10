<?php

namespace Webkul\Installer\Database\Seeders\RMA;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RMARulesSeeder extends Seeder
{
    /**
     * Seed the rma rules table
     *
     * @return void
     */
    public function run($parameters = [])
    {
        $timestamp = now();

        $rules = [
            [
                'name'            => 'Basic',
                'description'     => 1,
                'status'          => 1,
                'exchange_period' => 10,
                'return_period'   => 10,
                'created_at'      => $timestamp,
                'updated_at'      => $timestamp,
            ],
        ];

        foreach ($rules as $rule) {
            DB::table('rma_rules')->updateOrInsert(
                ['name' => $rule['name']],
                $rule
            );
        }
    }
}
