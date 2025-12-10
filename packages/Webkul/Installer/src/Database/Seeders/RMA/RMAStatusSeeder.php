<?php

namespace Webkul\Installer\Database\Seeders\RMA;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RMAStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('rma_statuses')->delete();

        $defaultStatuses = [
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

        DB::table('rma_statuses')->insert($defaultStatuses);
    }
}
