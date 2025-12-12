<?php

namespace Webkul\Installer\Database\Seeders\RMA;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RMAReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('rma_reasons')->delete();

        DB::table('rma_reason_resolutions')->delete();

        $defaultReasons = [
            [
                'title'      => 'Manufacturer Defect',
                'status'     => 1,
                'position'   => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'title'      => 'Damaged During Shipping',
                'status'     => 1,
                'position'   => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'title'      => 'Wrong Description Online',
                'status'     => 1,
                'position'   => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'title'      => 'Dead On Arrival',
                'status'     => 1,
                'position'   => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('rma_reasons')->insert($defaultReasons);

        $reasons = DB::table('rma_reasons')->get();

        $resolutionTypes = ['return', 'cancel-items'];

        foreach ($reasons as $reason) {
            foreach ($resolutionTypes as $resolutionType) {
                DB::table('rma_reason_resolutions')->insert([
                    'rma_reason_id'   => $reason->id,
                    'resolution_type' => $resolutionType,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }
    }
}
