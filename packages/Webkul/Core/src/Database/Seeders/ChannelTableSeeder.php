<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ChannelTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('channels')->delete();

        DB::table('channels')->insert([
                'id' => 1,
                'code' => 'default',
                'name' => 'Default',
                'default_locale_id' => 1,
                'base_currency_id' => 1
            ]);

        DB::table('channel_currencies')->insert([
                'channel_id' => 1,
                'currency_id' => 1,
            ]);
        
        DB::table('channel_locales')->insert([
                'channel_id' => 1,
                'locale_id' => 1,
            ]);
    }
}