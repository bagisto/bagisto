<?php

namespace Webkul\SocialLogin\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSocialAccountTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('core_config')->insert([
            [
                'code'         => 'customer.settings.social_login.enable_facebook',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'customer.settings.social_login.enable_twitter',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'customer.settings.social_login.enable_google',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'customer.settings.social_login.enable_linkedin',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ], [
                'code'         => 'customer.settings.social_login.enable_github',
                'value'        => '1',
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ]);
    }
}
