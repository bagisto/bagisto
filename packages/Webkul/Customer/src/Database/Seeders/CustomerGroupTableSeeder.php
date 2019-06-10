<?php

namespace Webkul\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CustomerGroupTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('customer_groups')->delete();

        DB::table('customer_groups')->insert([
            'id' => 1,
            'name' => 'General',
            'is_user_defined' => 0,
        ], [
            'id' => 2,
            'name' => 'Wholesale',
            'is_user_defined' => 0,
        ], [
            'id' => 3,
            'name' => 'Not Logged In',
            'is_user_defined' => 0,
        ]);
    }
}