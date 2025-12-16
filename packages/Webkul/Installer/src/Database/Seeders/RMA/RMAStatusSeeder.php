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
                'id'      => 1,
                'title'   => 'Pending',
                'status'  => 1,
                'color'   => '#efb308',
                'default' => 1,
            ], [
                'id'      => 2,
                'title'   => 'Accept',
                'status'  => 1,
                'color'   => '#12af56',
                'default' => 1,
            ], [
                'id'      => 3,
                'title'   => 'Awaiting',
                'status'  => 1,
                'color'   => '#f59e0b',
                'default' => 1,
            ], [
                'id'      => 4,
                'title'   => 'Dispatched Package',
                'status'  => 1,
                'color'   => '#3b82f6',
                'default' => 1,
            ], [
                'id'      => 5,
                'title'   => 'Received Package',
                'status'  => 1,
                'color'   => '#10b981',
                'default' => 1,
            ], [
                'id'      => 6,
                'title'   => 'Solved',
                'status'  => 1,
                'color'   => '#47b84f',
                'default' => 1,
            ], [
                'id'      => 7,
                'title'   => 'Declined',
                'status'  => 1,
                'color'   => '#e11d48',
                'default' => 1,
            ], [
                'id'      => 8,
                'title'   => 'Item Canceled',
                'status'  => 1,
                'color'   => '#dc2626',
                'default' => 1,
            ], [
                'id'      => 9,
                'title'   => 'Canceled',
                'status'  => 1,
                'color'   => '#991b1b',
                'default' => 1,
            ],
        ];

        DB::table('rma_statuses')->insert($defaultStatuses);
    }
}