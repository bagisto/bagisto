<?php

namespace Webkul\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\User\Models\Admin;
use Webkul\User\Models\Role;

class AdminsTableSeeder extends Seeder
{
    public function run()
    {
        $i=0;
        for ($i=0;$i<10;$i++) {
            $role = Role::first();
            $admin = new Admin();
            $admin->name = random_str(8);
            $admin->email = random_str(10).'@example.com';
            $admin->password = bcrypt('admin123');
            $admin->status = 1;
            $admin->role_id = $role->id;
            $admin->save();
        }
    }
}
