<?php

namespace Webkul\Installer\Database\Seeders\SocialLogin;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CustomerSocialAccountTableSeeder::class);
    }
}
