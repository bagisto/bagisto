<?php

namespace Webkul\Installer\Database\Seeders\Customer;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerGroupTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('customer_groups')->delete();

        DB::table('customer_groups')->insert([
            [
                'id'              => 1,
                'code'            => 'guest',
                'name'            => trans('installer::app.seeders.customer.customer-groups.guest'),
                'is_user_defined' => 0,
            ], [
                'id'              => 2,
                'code'            => 'general',
                'name'            => trans('installer::app.seeders.customer.customer-groups.general'),
                'is_user_defined' => 0,
            ], [
                'id'              => 3,
                'code'            => 'wholesale',
                'name'            => trans('installer::app.seeders.customer.customer-groups.wholesale'),
                'is_user_defined' => 0,
            ],
        ]);
    }
}
