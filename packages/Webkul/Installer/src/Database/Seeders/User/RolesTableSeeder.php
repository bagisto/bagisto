<?php

namespace Webkul\Installer\Database\Seeders\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('admins')->delete();

        DB::table('roles')->delete();

        DB::table('roles')->insert([
            'id'              => 1,
            'name'            => trans('installer::app.seeders.user.roles.name'),
            'description'     => trans('installer::app.seeders.user.roles.description'),
            'permission_type' => 'all',
        ]);
    }
}
