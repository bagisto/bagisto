<?php

namespace Webkul\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\User\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $Role = new Role();
        $Role->name = 'Administrator';
        $Role->description = 'Administrator role';
        $Role->permission_type = 'all';
        $Role->permissions = [];
        $Role->save();
    }
}