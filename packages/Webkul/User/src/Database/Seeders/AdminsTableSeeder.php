<?php

namespace Webkul\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\User\Models\Admin;
use Webkul\User\Models\Role;

class AdminsTableSeeder extends Seeder
{
    public function run()
    {
        // $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        // $charactersLength = strlen($characters);
        // $randomString = '';
        // for ($i = 0; $i < $length; $i++) {
        //     $randomString .= $characters[rand(0, $charactersLength - 1)];
        // }

        $i=0;
        for ($i=0;$i<10;$i++) {
            $role = Role::first();
            $admin = new Admin();
            $admin->name = str_random(8);
            $admin->email = str_random(10).'@example.com';
            $admin->password = bcrypt('admin123');
            $admin->status = 1;
            $admin->role_id = $role->id;
            $admin->save();
        }
    }
}
