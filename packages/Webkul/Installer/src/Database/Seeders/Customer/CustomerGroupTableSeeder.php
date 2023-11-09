<?php

namespace Webkul\Installer\Database\Seeders\Customer;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerGroupTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('customer_groups')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('customer_groups')->insert([
            [
                'id'              => 1,
                'code'            => 'guest',
                'name'            => trans('installer::app.seeders.customer.customer-groups.guest', [], $defaultLocale),
                'is_user_defined' => 0,
            ], [
                'id'              => 2,
                'code'            => 'general',
                'name'            => trans('installer::app.seeders.customer.customer-groups.general', [], $defaultLocale),
                'is_user_defined' => 0,
            ], [
                'id'              => 3,
                'code'            => 'wholesale',
                'name'            => trans('installer::app.seeders.customer.customer-groups.wholesale', [], $defaultLocale),
                'is_user_defined' => 0,
            ],
        ]);
    }
}
