<?php

namespace Webkul\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\User\Models\Admin;
use Webkul\User\Models\Role;

class AdminsTableSeeder extends Seeder
{
    public function run()
    {
        $role = Role::first();

        $admin = new Admin();
        $admin->name = 'Admin';
        $admin->email = 'admin@example.com';
        $admin->password = bcrypt('admin123');
        $admin->status = true;
        $admin->role_id = $role->id;
        $admin->save();
    }
}