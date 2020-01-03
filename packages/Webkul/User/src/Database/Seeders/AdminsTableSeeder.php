<?php

namespace Webkul\User\Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use DB;

class AdminsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('admins')->delete();

        DB::table('admins')->insert([
                'id' => 1,
                'name' => 'Example',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
                'api_token' => Str::random(80),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => 1,
                'role_id' => 1,
            ]);
    }
}
