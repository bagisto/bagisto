<?php

namespace Webkul\Installer\Database\Seeders\RMA;

use Carbon\Carbon;
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
        $now = Carbon::now();

        $defaultReasons = [
            [
                'title'      => 'Manufacturer defect',
                'status'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'title'      => 'Damaged during shipping',
                'status'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'title'      => 'Wrong description online',
                'status'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'title'      => 'Dead on arrival',
                'status'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        /**
         * Insert default RMA reasons into the database.
         */
        foreach ($defaultReasons as $reason) {
            DB::table('rma_reasons')->updateOrInsert(
                ['title' => $reason['title']],
                [
                    'status'     => $reason['status'],
                    'created_at' => $reason['created_at'],
                    'updated_at' => $reason['updated_at'],
                ]
            );
        }

        $reasons = DB::table('rma_reasons')->get();

        $resolutionTypes = ['exchange', 'return', 'cancel-items'];

        /**
         * Insert default resolution mappings for RMA reasons.
         */
        foreach ($reasons as $reason) {
            foreach ($resolutionTypes as $resolutionType) {
                DB::table('rma_reason_resolutions')->updateOrInsert(
                    [
                        'rma_reason_id'   => $reason->id,
                        'resolution_type' => $resolutionType,
                    ], [
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        }

        /**
         * Cleanup duplicate resolution mappings.
         */
        DB::table('rma_reason_resolutions')
            ->select('rma_reason_id', 'resolution_type')
            ->groupBy('rma_reason_id', 'resolution_type')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->each(function ($duplicate) {
                DB::table('rma_reason_resolutions')
                    ->where('rma_reason_id', $duplicate->rma_reason_id)
                    ->where('resolution_type', $duplicate->resolution_type)
                    ->skip(1)
                    ->delete();
            });
    }
}
