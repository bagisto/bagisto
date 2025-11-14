<?php

namespace Webkul\Installer\Database\Seeders\RMA;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RMAStatusDataSeed extends Seeder
{
    /**
     * Create a new rma configuration
     */
    public function run(): void
    {
        /**
         * Default statuses to be inserted
         */
        $statuses = [
            [
                'title'   => 'Accept',
                'status'  => 1,
                'color'   => '#12af56',
                'default' => 1,
            ], [
                'title'   => 'Awaiting',
                'status'  => 1,
                'color'   => '#efb308',
                'default' => 1,
            ], [
                'title'   => 'Dispatched Package',
                'status'  => 1,
                'color'   => '#efb308',
                'default' => 1,
            ], [
                'title'   => 'Declined',
                'status'  => 1,
                'color'   => '#e11d48',
                'default' => 1,
            ], [
                'title'   => 'Item Canceled',
                'status'  => 1,
                'color'   => '#e11d48',
                'default' => 1,
            ], [
                'title'   => 'Pending',
                'status'  => 1,
                'color'   => '#efb308',
                'default' => 1,
            ], [
                'title'   => 'Received Package',
                'status'  => 1,
                'color'   => '#12af56',
                'default' => 1,
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('rma_statuses')->updateOrInsert(
                ['title' => $status['title']],
                array_merge($status, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}